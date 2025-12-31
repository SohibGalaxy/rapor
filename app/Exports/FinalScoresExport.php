<?php

namespace App\Exports;

use App\Models\FinalScore;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FinalScoresExport implements FromCollection, WithHeadings
{
    protected int $studentId;

    public function __construct(int $studentId)
    {
        $this->studentId = $studentId;
    }

    public function collection()
    {
        return FinalScore::with('subject')
            ->where('student_id', $this->studentId)
            ->get()
            ->map(function ($row) {
                return [
                    'Mata Pelajaran' => $row->subject->name,
                    'Semester'       => $row->semester,
                    'Nilai Akhir'    => $row->final_score,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Mata Pelajaran',
            'Semester',
            'Nilai Akhir',
        ];
    }
}
