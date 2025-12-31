<?php

namespace App\Services;

use App\Models\Student;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class FinalScoreWordExport
{
    public static function export(Student $student): string
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addText(
            "LAPORAN NILAI SISWA",
            ['bold' => true, 'size' => 14]
        );

        $section->addText("Nama Siswa : {$student->nama}");
        $section->addText("NIS : {$student->nis}");
        $section->addTextBreak();

        $table = $section->addTable(['borderSize' => 6]);

        $table->addRow();
        $table->addCell()->addText('Mata Pelajaran');
        $table->addCell()->addText('Semester');
        $table->addCell()->addText('Nilai Akhir');

        foreach ($student->finalScores()->with('subject')->get() as $score) {
            $table->addRow();
            $table->addCell()->addText($score->subject->name);
            $table->addCell()->addText($score->semester);
            $table->addCell()->addText($score->final_score);
        }

        $fileName = 'rapor_' . $student->nama . '.docx';
        $path = storage_path('app/public/' . $fileName);

        IOFactory::createWriter($phpWord, 'Word2007')->save($path);

        return $path;
    }
}
