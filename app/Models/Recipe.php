<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ingredients',
        'instructions',
        'file_name',
        'file_path',
        'recipe_type_id',
    ];

    public function recipeType()
    {
        return $this->belongsTo(RecipeType::class);
    }
}
