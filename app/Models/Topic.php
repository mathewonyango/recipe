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
     public function recipes()
     {
         return $this->hasMany(Recipe::class);
     }

     // Method to count the number of recipes for the topic
     public function recipeCount()
     {
         return $this->recipes()->count();
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
}
