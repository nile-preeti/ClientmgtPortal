<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceBreak extends Model
{
    use HasFactory;
protected $fillable=["start_break","end_break","attendance_id",'date','user_id'];
}
