<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'recipe_id',
        'comment',
        'rating',
    ];

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // A comment belongs to a user
    }

    // Relationship to Recipe
    public function recipe()
    {
        return $this->belongsTo(Recipe::class, 'recipe_id'); // A comment belongs to a recipe
    }
}
