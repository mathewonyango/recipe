<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
    ];

     // Relationship with Recipe
    // Relationship with Recipe
    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    // Method to count the number of recipes for the topic
    public function recipeCount()
    {
        return $this->recipes()->count();
    }

    // Method to sum all votes for recipes in this topic
    public function totalVotes()
    {
        return $this->recipes()->sum('vote'); // Sum the 'vote' column in recipes
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id'); // Assuming event_id is the foreign key
    }

    // Method to get summary (if you mean latest recipe or summary of recipes)
    public function recipeSummary()
    {
        // Example: Get the latest recipe for the topic
        return $this->recipes()->latest()->first();
    }

    // Additional methods...

    public function totalChefs()
    {
        return $this->recipes()->distinct('user_id')->count('user_id');
    }

    public function totalComments()
    {
        return $this->recipes()->withCount('comments')->get()->sum('comments_count');
    }

    public function comments()
    {
        return $this->hasManyThrough(Comment::class, Recipe::class);
    }

    public function averageRatings()
    {
        return $this->comments()->avg('rating');
    }

    public function winner()
    {
        return $this->recipes()->withCount('votes')->orderBy('votes_count', 'desc')->first();
    }

    public function topChefs()
    {
        return $this->recipes()
            ->withCount('votes')
            ->orderBy('votes_count', 'desc')
            ->take(3)
            ->get()
            ->map(function ($recipe) {
                return [
                    'chef' => $recipe->chef, // Assuming chef method exists in Recipe model
                    'votes' => $recipe->votes_count,
                ];
            });
    }

    public function chefRankings()
    {
        return $this->topChefs()->map(function ($chef, $index) {
            return [
                'rank' => $index + 1,
                'chef' => $chef['chef'],
                'votes' => $chef['votes'],
            ];
        });
    }

    public function status($status)
    {
        return $this->recipes()->where('status', $status)->count();
    }

}
