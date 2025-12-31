<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name',
    ];

    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    public function finalScores()
    {
        return $this->hasMany(FinalScore::class);
    }
}
