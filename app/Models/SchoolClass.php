<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $table = 'school_classes';

    protected $fillable = [
        'name',
    ];

    public function classRooms()
    {
        return $this->hasMany(ClassRoom::class, 'class_id');
    }
}
