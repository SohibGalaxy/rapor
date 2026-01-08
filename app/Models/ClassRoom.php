<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    protected $fillable = [
        'school_class_id',
        'academic_year_id',
        'teacher_id',
        'is_active',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
        // otomatis pakai school_class_id
    }


    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function students()
    {
        return $this->belongsToMany(
            Student::class,
            'student_class_rooms'
        )->withTimestamps();
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    public function finalScores()
    {
        return $this->hasMany(FinalScore::class);
    }
    
}
