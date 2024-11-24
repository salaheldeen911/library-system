<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Exception;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::all();

            return $this->success('Categories retrieved successfully', CategoryResource::collection($categories));
        } catch (Exception $e) {
            $this->errorLog($e, 'Failed to retrieve categories');

            return $this->failed('Failed to retrieve categories');
        }
    }

    public function store(StoreCategoryRequest $request)
    {
        try {
            $category = Category::create($request->validated());

            return $this->success('Category created successfully', new CategoryResource($category), 201);
        } catch (Exception $e) {
            $this->errorLog($e, 'Failed to create category');

            return $this->failed('Failed to create category');
        }
    }

    public function show(Category $category)
    {
        try {
            return $this->success('Category retrieved successfully', new CategoryResource($category));
        } catch (Exception $e) {
            $this->errorLog($e, 'Failed to retrieve category');

            return $this->failed('Failed to retrieve category');
        }
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        try {
            $category->update($request->validated());

            return $this->success('Category updated successfully', new CategoryResource($category));
        } catch (Exception $e) {
            $this->errorLog($e, 'Failed to update category');

            return $this->failed('Failed to update category');
        }
    }

    public function destroy(Category $category)
    {
        try {
            $category->delete();

            return $this->success('Category deleted successfully');
        } catch (Exception $e) {
            $this->errorLog($e, 'Failed to delete category');

            return $this->failed('Failed to delete category');
        }
    }
}
