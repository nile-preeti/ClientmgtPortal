<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class JobSchedule extends Model
{
    use HasFactory;

    protected $table = 'job_schedules';

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

    public function subCategory()
    {
        return $this->belongsTo(ServiceSubCategory::class, 'sub_category_id');
    }


    public function attendance()
    {
        return $this->hasOne(Attendance::class, 'user_id', 'user_id')
            ->whereRaw('attendances.date = job_schedules.start_date');
    }
}
