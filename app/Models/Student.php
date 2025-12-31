<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'nis',
        'nama',
        'sekolah',
        'alamat',
        'gender',
    ];

    /**
     * Relasi siswa ↔ kelas (pivot: student_class_rooms)
     */
    public function classRooms()
    {
        return $this->belongsToMany(
            ClassRoom::class,
            'student_class_rooms', // pivot table
            'student_id',          // FK student
            'class_room_id'        // FK classroom
        )->withTimestamps();
    }

    /**
     * Nilai per mapel
     */
    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    /**
     * Nilai akhir (hasil generate)
     */
    public function finalScores()
    {
        return $this->hasMany(FinalScore::class);
    }


}

