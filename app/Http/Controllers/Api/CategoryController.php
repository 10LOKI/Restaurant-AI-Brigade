<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategorieRequest;
use App\Http\Requests\UpdateCategorieRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->query('active') === 'false') {
            $query->where('is_active', false);
        } else {
            $query->where('is_active', true);
        }

        return response()->json($query->get());
    }

    public function store(StoreCategorieRequest $request)
    {
        $this->authorize('create', Category::class);
        return response()->json(Category::create($request->validated()), 201);
    }

    public function show(Category $category)
    {
        return response()->json($category->load('plates'));
    }

    public function update(UpdateCategorieRequest $request, Category $category)
    {
        $this->authorize('update', $category);
        $category->update($request->validated());
        return response()->json($category);
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        if ($category->plates()->where('is_available', true)->exists()) {
            return response()->json(['message' => 'Cannot delete a category with active plates'], 422);
        }

        $category->delete();
        return response()->json(['message' => 'Category deleted']);
    }

    public function plates(Category $category)
    {
        $plates = $category->plates()
            ->where('is_available', true)
            ->with('ingredients')
            ->get();

        return response()->json($plates);
    }
}
