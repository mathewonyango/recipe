<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;



class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'remember_token',
        'role',
        'status',
        'approval_status',
        'recipes_count',
        'username',
        'profile_picture',
        'experience_level',
        'cuisine_type',
        'location',
        'certification',
        'bio',
        'payment_status',
        'social_media_links',
        'events_participated',
        'push_notification',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
      // Add the relationship method
      public function recipes()
      {
          return $this->hasMany(Recipe::class, 'user_id'); // Chef can have many recipes
      }

      // Relationship to Votes
      public function votes()
      {
          return $this->hasMany(Vote::class);
      }

      // Relationship to Events through Topics
      public function events()
      {
          return $this->hasManyThrough(Event::class, Topic::class, 'user_id', 'topic_id', 'id', 'id');
      }

      public function totalVotes()
      {
          // Sum all votes from the user's recipes
          return $this->recipes()->withCount('votes')->get()->sum('vote');
      }

      // Method to fetch all relevant data of the chef
      public function fetchChefData()
      {
          return [
              'profile' => $this,
              'recipes' => $this->recipes, // Fetches all recipes by this chef
              'votes' => $this->votes, // Fetches all votes by this chef
              'events' => $this->events, // Fetches events participated by this chef
              'recipe_count' => $this->recipes()->count(), // Count of recipes
              'total_votes' => $this->votes()->count(), // Count of votes
          ];
      }

}
