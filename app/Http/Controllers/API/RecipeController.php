<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use App\Models\RecipeType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;

class RecipeController extends BaseController
{
    public function index(): JsonResponse
    {
        $recipes = Recipe::all();
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $recipes->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginator = new LengthAwarePaginator($currentItems, count($recipes), $perPage, $currentPage);

        return $this->sendResponse(RecipeResource::collection($paginator), 'Recipes retrieved successfully.');
    }

    public function store(Request $request): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'recipe_type_id' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $recipe = Recipe::create([
            'name' => $input['name'],
            'ingredients' => $input['ingredients'] ?? null,
            'instructions' => $input['instructions'] ?? null,
            'file_name' => $input['file_name'] ?? null,
            'file_path' => $input['file_path'] ?? null,
            'recipe_type_id' => $input['recipe_type_id'],
        ]);

        $recipe->load('recipeType');

        return $this->sendResponse(new RecipeResource($recipe), 'Recipe created successfully.');
    }

    public function show($id): JsonResponse
    {
        $recipe = Recipe::find($id);

        if (is_null($recipe)) {
            return $this->sendError('Recipe not found.');
        }

        return $this->sendResponse(new RecipeResource($recipe), 'Recipe retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recipe $recipe): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $recipe->name = $input['name'] ?? $recipe->name;
        $recipe->ingredients = $input['ingredients'] ?? $recipe->ingredients;
        $recipe->instructions = $input['instructions'] ?? $recipe->instructions;
        $recipe->file_name = $input['file_name'] ?? $recipe->file_name;
        $recipe->file_path = $input['file_path'] ?? $recipe->file_path;
        $recipe->recipe_type_id = $input['recipe_type_id'] ?? $recipe->recipe_type_id;
        $recipe->save();

        return $this->sendResponse(new RecipeResource($recipe), 'Recipe updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recipe $recipe): JsonResponse
    {
        $recipe->delete();

        return $this->sendResponse([], 'Recipe deleted successfully.');
    }

    public function isFeatured(Request $request, Recipe $recipe): JsonResponse
    {
        $recipe->is_featured = $recipe->is_featured ? false : true;
        $recipe->save();

        return $this->sendResponse(new RecipeResource($recipe), 'Recipe updated successfully.');
    }

    public function getTypes(): JsonResponse
    {
        $types = RecipeType::all();

        $types = $types->map(function ($type) {
            return [
                'recipe_type_id' => $type->id,
                'recipe_type_name' => $type->name,
            ];
        });

        return $this->sendResponse($types, 'Recipe types retrieved successfully.');
    }
}
