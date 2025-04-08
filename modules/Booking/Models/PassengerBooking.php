<?php

namespace Modules\Booking\Models;

use App\BaseModel;


class PassengerBooking extends BaseModel
{

    protected $table = 'passenger_booking_details';

    protected $fillable = [
        'code', 'passenger', 'title', 'first_name', 'last_name', 'dob', 'passport_number',
        'issue_date', 'expiry_date', 'country', 'city', 'pan_number', 'meal_preference','passport_front','passport_back','pan_card','create_user','update_user','created_at','updated_at'
    ];
}