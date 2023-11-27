<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'recipe_name' => $this->name,
            'recipe_type' => $this->recipeType->name,
            'ingredients' => $this->ingredients,
            'instructions' => $this->instructions,
            'file_name' => $this->file_name,
            'file_path' => $this->file_path,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
            'is_featured' => $this->is_featured,
        ];
    }
}
