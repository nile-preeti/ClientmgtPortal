<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    public function userServices()
    {
        return $this->hasMany(UserService::class, 'service_id');
    }


    public function subCategories()
    {
        return $this->hasMany(ServiceSubCategory::class, 'category_id');
    }
}
