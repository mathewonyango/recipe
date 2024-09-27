<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Event extends Model
{
    use HasFactory;

    // Define the table name if it's different from the default 'events'
    protected $table = 'events';

    // Specify the fields that can be mass assigned
    protected $fillable = [
        'location',
        'name',
        'day_of_event',
        'time',
        'charges',
        'contact_number',
        'topic_id',
    ];

    // Cast fields to specific data types
    // protected $casts = [
    //     'participating_chefs' => 'array',
    //     'event_recipes' => 'array',
    // ];

    /**
     * Get the participating chefs for the event.
     * Assuming `Chef` is another model with a relationship.
     */
    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }

    // Define relationship to Recipes through Topics
    public function recipes()
{
    return $this->hasMany(Recipe::class, 'topic_id', 'topic_id'); // Link recipes by topic_id
}

    // Define relationship to Chefs (Users) who participated
    public function chefs()
{
    return $this->belongsToMany(User::class, 'event_user', 'event_id', 'user_id')
                ->wherePivot('role', 'chef');
}




}
