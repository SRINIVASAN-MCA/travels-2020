<?php

namespace App\Models; // Correct namespace for default Laravel models

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'first_name', 'last_name', 'dob', 'passport_number',
        'issue_date', 'expiry_date', 'country', 'city', 'pan_number', 'meal_preference'
    ];
}
