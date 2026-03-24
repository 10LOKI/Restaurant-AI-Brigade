<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\AnalyzePlateCompatibility;
use App\Models\Plate;
use App\Models\Recommendation;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function analyze(Request $request, Plate $plate)
    {
        $recommendation = Recommendation::updateOrCreate(
            ['user_id' => $request->user()->id, 'plate_id' => $plate->id],
            ['status' => 'processing', 'score' => null, 'label' => null, 'warning_message' => null, 'conflicting_tags' => null]
        );

        AnalyzePlateCompatibility::dispatch($request->user(), $plate, $recommendation);

        return response()->json(['status' => 'processing'], 202);
    }

    public function index(Request $request)
    {
        $recommendations = Recommendation::where('user_id', $request->user()->id)
            ->with('plate')
            ->latest()
            ->get();

        return response()->json($recommendations);
    }

    public function show(Request $request, Plate $plate)
    {
        $recommendation = Recommendation::where('user_id', $request->user()->id)
            ->where('plate_id', $plate->id)
            ->latest()
            ->first();

        if (!$recommendation) {
            return response()->json(['message' => 'No recommendation found for this plate'], 404);
        }

        return response()->json([
            'score'            => $recommendation->score,
            'label'            => $recommendation->label,
            'warning_message'  => $recommendation->warning_message,
            'conflicting_tags' => $recommendation->conflicting_tags,
            'status'           => $recommendation->status,
        ]);
    }
}
