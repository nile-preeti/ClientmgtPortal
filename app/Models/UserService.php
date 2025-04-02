<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserService extends Model
{
    use HasFactory;

    public function service(){
        return $this->belongsTo(Service::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(ServiceSubCategory::class, 'service_sub_category', 'id');
    }
}
