<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentClassRoom extends Model
{
    protected $fillable = [
        'student_id',
        'class_room_id',
    ];
}
