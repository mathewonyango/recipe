<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'topic_id',
        'servings',
        'prep_time',
        'cook_time',
        'total_time',
        'ingredients',
        'instructions',
        'status',
    ];

    // Relationships with Topic
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function chef()  // or user() if you prefer
    {
        return $this->belongsTo(User::class, 'user_id'); // Or 'chef_id' if that's the column name in recipes
    }
    public function votes()
{
    return $this->hasMany(Vote::class);
}

public function events()
{
    return $this->belongsToMany(Event::class, 'event_recipe', 'recipe_id', 'event_id');
}
public function event()
{
    return $this->hasOneThrough(Event::class, Topic::class, 'id', 'id', 'topic_id', 'event_id');
}
public function user()
{
    return $this->belongsTo(User::class, 'user_id'); // Recipe belongs to a chef
}

}
