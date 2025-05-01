<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    public function state(){
        return $this->belongsTo(State::class);
    }

    public function jobSchedules()
    {
        return $this->hasMany(JobSchedule::class, 'customer_id', 'id');
    }
}
