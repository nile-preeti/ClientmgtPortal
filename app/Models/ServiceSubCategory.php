<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceSubCategory extends Model
{
    protected $fillable = ['category_id', 'sub_category'];

    public function service()
    {
        return $this->belongsTo(Service::class, 'category_id');
    }
}
