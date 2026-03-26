<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Plate;
use App\Models\Recommendation;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function stats(Request $request)
    {
        $this -> authorize('admin');
        $mostRecommended = Recommendation::where('status', 'ready')
            ->selectRaw('plate_id, AVG(score) as avg_score')
            ->groupBy('plate_id')
            ->orderByDesc('avg_score')
            ->with('plate')
            ->first();

        $leastRecommended = Recommendation::where('status', 'ready')
            ->selectRaw('plate_id, AVG(score) as avg_score')
            ->groupBy('plate_id')
            ->orderBy('avg_score')
            ->with('plate')
            ->first();

        $topCategory = Category::withCount('plates')
            ->orderByDesc('plates_count')
            ->first();

        return response()->json([
            'total_plates'          => Plate::count(),
            'total_categories'      => Category::count(),
            'total_ingredients'     => Ingredient::count(),
            'total_recommendations' => Recommendation::count(),
            'most_recommended'      => $mostRecommended?->plate?->name,
            'least_recommended'     => $leastRecommended?->plate?->name,
            'top_category'          => $topCategory?->name,
        ]);
    }
}
