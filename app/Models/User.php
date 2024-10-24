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
        'notification_preferences',
        ''
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

    protected $casts = [
        'events_participated' => 'array',  // Automatically casts the field to an array
        'notification_preferences' => 'array', // Cast to array so it's handled correctly
    ];


      // Add the relationship method
      public function recipes()
      {
          return $this->hasMany(Recipe::class, 'user_id'); // Chef can have many recipes
      }

      public function payment()
{
    return $this->hasMany(Payment::class);
}

      // Relationship to Votes
      public function votes()
      {
          return $this->hasMany(Vote::class);
      }

      // Relationship to Events through Topics
      public function events()
    {
        return $this->hasMany(Event::class, 'id', 'events_participated');
    }

      public function attendants()
    {
        return $this->hasMany(User::class, 'event_participated', 'id');
        // Here 'event_participated' is the foreign key in User, and 'id' is the primary key of Event
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
    //   public function chefEvents()
    //   {
    //       return $this->belongsToMany(Event::class, 'event_chef', 'user_id', 'event_id')
    //                   ->where('role', 'chef');
    //   }

      // A user can attend many events (many-to-many)
      public function attendedEvents()
      {
          return $this->belongsToMany(Event::class, 'event_user', 'user_id', 'event_id');
      }

      // A user can make many comments (one-to-many)
      public function comments()
      {
          return $this->hasMany(Comment::class);
      }
      public function views()
    {
        return $this->hasMany(View::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }



    public function feedback()
    {
        return $this->hasMany(Feedback::class);
    }

}
