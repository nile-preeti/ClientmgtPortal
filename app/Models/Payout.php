<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    use HasFactory;
    protected $fillable = [
        'driver_id',
        'start_date',
        'end_date',
        'payment_date',
        'total_amount',
        'admin_trip_charges',
        'driver_trip_charges',
        'status',
    ];
}
