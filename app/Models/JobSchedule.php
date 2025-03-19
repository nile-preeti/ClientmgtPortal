<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class JobSchedule extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function customer(){
        return $this->belongsTo(Customer::class);
    }
    public function service(){
        return $this->belongsTo(Service::class);
    }

    public function userService()
    {
        return $this->hasOne(UserService::class, 'service_id', 'service_id')
            ->where('user_id', Auth::id()); // Ensure it matches the logged-in user
    }
}
