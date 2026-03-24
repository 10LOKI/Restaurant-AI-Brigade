<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIngredientRequest;
use App\Http\Requests\UpdateIngredientRequest;
use App\Models\Ingredient;

class IngredientController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Ingredient::class);
        return response()->json(Ingredient::all());
    }

    public function store(StoreIngredientRequest $request)
    {
        $this->authorize('create', Ingredient::class);
        return response()->json(Ingredient::create($request->validated()), 201);
    }

    public function update(UpdateIngredientRequest $request, Ingredient $ingredient)
    {
        $this->authorize('update', $ingredient);
        $ingredient->update($request->validated());
        return response()->json($ingredient);
    }

    public function destroy(Ingredient $ingredient)
    {
        $this->authorize('delete', $ingredient);
        $ingredient->delete();
        return response()->json(['message' => 'Ingredient deleted']);
    }
}
