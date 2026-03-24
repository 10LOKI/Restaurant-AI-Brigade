<?php

namespace App\Jobs;

use App\Models\Plate;
use App\Models\Recommendation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AnalyzePlateCompatibility implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Plate $plate,
        public Recommendation $recommendation
    ) {}

    public function handle(): void
    {
        $dietaryTags = $this->user->dietary_tags ?? [];
        $ingredients = $this->plate->ingredients;
        $conflicting = [];

        $tagMap = [
            'vegan'          => 'contains_meat',
            'no_sugar'       => 'contains_sugar',
            'no_cholesterol' => 'contains_cholesterol',
            'gluten_free'    => 'contains_gluten',
            'no_lactose'     => 'contains_lactose',
        ];

        foreach ($dietaryTags as $diet) {
            $ingredientTag = $tagMap[$diet] ?? null;
            if (!$ingredientTag) continue;

            foreach ($ingredients as $ingredient) {
                if (in_array($ingredientTag, $ingredient->tags ?? [])) {
                    $conflicting[] = $ingredientTag;
                }
            }
        }

        $conflicting = array_values(array_unique($conflicting));
        $totalTags   = count($dietaryTags);
        $conflicts   = count($conflicting);

        $score = $totalTags > 0
            ? (int) round((($totalTags - $conflicts) / $totalTags) * 100)
            : 100;

        $this->recommendation->update([
            'score'            => $score,
            'label'            => $this->getLabel($score),
            'warning_message'  => $score < 50
                ? 'Not compatible with your dietary profile due to: ' . implode(', ', $conflicting)
                : null,
            'conflicting_tags' => $conflicting,
            'status'           => 'ready',
        ]);
    }

    private function getLabel(int $score): string
    {
        if ($score >= 80) return 'Highly Recommended';
        if ($score >= 50) return 'Recommended with notes';
        return 'Not Recommended';
    }
}
