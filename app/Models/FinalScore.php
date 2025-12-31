<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinalScore extends Model
{
    protected $fillable = [
        'student_id',
        'subject_id',
        'class_room_id',
        'semester',
        'final_score',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class);
    }
}
