<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlatRequest;
use App\Http\Requests\UpdatePlatRequest;
use App\Models\Plate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlateController extends Controller
{
    public function index(Request $request)
    {
        $plates = Plate::where('is_available', true)
            ->with(['category', 'ingredients'])
            ->get()
            ->map(fn($plate) => $this->withRecommendation($plate, $request->user()->id));

        return response()->json($plates);
    }

    public function store(StorePlatRequest $request)
    {
        $this->authorize('create', Plate::class);

        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = Storage::disk('cloudinary')->put('plates', $request->file('image'));
        }

        $ingredientIds = $data['ingredient_ids'] ?? [];
        unset($data['ingredient_ids']);

        $plate = Plate::create($data);
        $plate->ingredients()->sync($ingredientIds);

        return response()->json($plate->load('ingredients', 'category'), 201);
    }

    public function show(Request $request, Plate $plate)
    {
        $plate->load(['category', 'ingredients']);
        return response()->json($this->withRecommendation($plate, $request->user()->id));
    }

    public function update(UpdatePlatRequest $request, Plate $plate)
    {
        $this->authorize('update', $plate);

        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($plate->image) Storage::disk('cloudinary')->delete($plate->image);
            $data['image'] = Storage::disk('cloudinary')->put('plates', $request->file('image'));
        }

        $ingredientIds = $data['ingredient_ids'] ?? null;
        unset($data['ingredient_ids']);

        $plate->update($data);
        if ($ingredientIds !== null) $plate->ingredients()->sync($ingredientIds);

        return response()->json($plate->load('ingredients', 'category'));
    }

    public function destroy(Plate $plate)
    {
        $this->authorize('delete', $plate);
        if ($plate->image) Storage::disk('cloudinary')->delete($plate->image);
        $plate->delete();
        return response()->json(['message' => 'Plate deleted']);
    }

    private function withRecommendation(Plate $plate, int $userId): array
    {
        $rec = $plate->recommendations()->where('user_id', $userId)->latest()->first();

        return array_merge($plate->toArray(), [
            'recommendation' => $rec ? [
                'score'  => $rec->score,
                'label'  => $rec->label,
                'status' => $rec->status,
            ] : ['status' => 'not_analyzed'],
        ]);
    }
}
