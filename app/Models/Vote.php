<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    // Specify the table name if it doesn't follow Laravel's naming convention
    protected $table = 'votes';

    // Define fillable fields to allow mass assignment
    protected $fillable = [
        'recipe_id',
        'user_id',
    ];

    // Relationship to the Recipe model
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    // Relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
