<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'phone_number',
        'amount',
        'description',
        'status',
        'pesapal_transaction_id',
    ];
}
