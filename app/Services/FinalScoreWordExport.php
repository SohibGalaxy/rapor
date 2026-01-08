<?php

namespace App\Services;

use App\Models\Student;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\SimpleType\Jc;

class FinalScoreWordExport
{
    public static function export(Student $student, ?array $filters = null, ?\Illuminate\Database\Eloquent\Collection $filteredFinalScores = null): string
    {
        $student->load('finalScores.subject', 'finalScores.classRoom.academicYear', 'finalScores.classRoom.teacher');

        if ($filteredFinalScores !== null) {
            $student->finalScores = $filteredFinalScores;
        }

        $firstFinalScore = $student->finalScores->first();
        $classRoom = $firstFinalScore ? $firstFinalScore->classRoom : null;

        $madrasah = $student->sekolah ?? 'MI Pabean';
        $namaKelas = $classRoom ? $classRoom->schoolClass->name : $student->kelas ?? '-';
        $namaWaliKelas = $classRoom ? $classRoom->teacher->name : 'Nama Wali Kelas';
        $tahunAjaran = $classRoom ? $classRoom->academicYear->name : '-';
        $semester = ucfirst($firstFinalScore->semester ?? 'Ganjil');
        $tanggalRapor = now()->format('d F Y');

        $phpWord = new PhpWord();

        $section = $phpWord->addSection([
            'paperSize'    => 'A4',
            'marginTop'    => Converter::cmToTwip(1.5),
            'marginBottom' => Converter::cmToTwip(2.0),
            'marginLeft'   => Converter::cmToTwip(2.0),
            'marginRight'  => Converter::cmToTwip(2.0),
        ]);

        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(11);

        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 80
        ];

        // ==========================================
        // HEADER / KOP SEKOLAH DENGAN LOGO
        // ==========================================
        $headerTable = $section->addTable(['borderSize' => 0, 'cellMargin' => 0]);
        $headerTable->addRow();
        
        // Sel Kiri untuk Logo (Pastikan path gambar benar)
        $logoPath = public_path('img/mi.png'); // Ganti dengan path logo Anda
        $headerTable->addCell(2000)->addImage($logoPath, [
            'width' => 60, 
            'height' => 60, 
            'alignment' => Jc::CENTER
        ]);

        // Sel Kanan untuk Teks Kop
        $textCell = $headerTable->addCell(8000);
        $textCell->addText('KEMENTERIAN AGAMA REPUBLIK INDONESIA', ['bold' => true, 'size' => 12], ['alignment' => Jc::CENTER]);
        $textCell->addText('MIS AL-KHAIRIYAH PABEAN', ['bold' => true, 'size' => 14], ['alignment' => Jc::CENTER]);
        $textCell->addText('JL. PABEAN NO 03 LINK KARANG TENGAH RT. 010 RW. 004', ['size' => 9], ['alignment' => Jc::CENTER]);
        $textCell->addText('Kecamatan Purwakarta, Kota Cilegon – Banten', ['size' => 9], ['alignment' => Jc::CENTER]);

        // Garis Pemisah (Border Bawah Kop)
        $section->addLine(['weight' => 2, 'width' => 450, 'height' => 0, 'color' => '000000']);
        $section->addTextBreak(1);

        // ==========================================
        // BIODATA SISWA (TANPA BORDER)
        // ==========================================
        // 'borderSize' => 0 memastikan tidak ada garis tabel [cite: 2]
        $bioTable = $section->addTable(['borderSize' => 0, 'cellMargin' => 0, 'width' => 100 * 56.7]);
        $bioData = [
            ['Nama', ': ' . $student->nama, 'Madrasah', ': ' . $madrasah],
            ['NIS/NISN', ': ' . $student->nis, 'Fase', ': A'],
            ['Kelas', ': ' . $namaKelas, 'Semester', ': ' . $semester],
            ['Alamat', ': ' . $student->alamat, 'Tahun Ajaran', ': ' . $tahunAjaran],
        ];

        foreach ($bioData as $row) {
            $bioTable->addRow();
            $bioTable->addCell(1800)->addText($row[0]);
            $bioTable->addCell(4200)->addText($row[1]);
            $bioTable->addCell(1800)->addText($row[2]);
            $bioTable->addCell(2700)->addText($row[3]);
        }

        $section->addTextBreak(1);
        $section->addText('CAPAIAN HASIL BELAJAR', ['bold' => true, 'size' => 12], ['alignment' => Jc::CENTER]);
        $section->addTextBreak(1);

        // ==========================================
        // TABEL NILAI (DENGAN BORDER)
        // ==========================================
        $table = $section->addTable($tableStyle);
        
        $table->addRow();
        $table->addCell(800)->addText('No', ['bold' => true], ['alignment' => Jc::CENTER]);
        $table->addCell(6000)->addText('Mata Pelajaran', ['bold' => true], ['alignment' => Jc::CENTER]);
        $table->addCell(2500)->addText('Nilai Akhir', ['bold' => true], ['alignment' => Jc::CENTER]);

        $no = 1;
        $total = 0;
        foreach ($student->finalScores as $score) {
            $table->addRow();
            $table->addCell(800)->addText($no++, [], ['alignment' => Jc::CENTER]);
            $table->addCell(6000)->addText($score->subject->name);
            $table->addCell(2500)->addText($score->final_score, [], ['alignment' => Jc::CENTER]);
            $total += $score->final_score;
        }

        $table->addRow();
        $table->addCell(6800, ['gridSpan' => 2])->addText('JUMLAH', ['bold' => true], ['alignment' => Jc::CENTER]);
        $table->addCell(2500)->addText($total, ['bold' => true], ['alignment' => Jc::CENTER]);

        $section->addTextBreak(2);

        // ==========================================
        // FOOTER / TANDA TANGAN (TANPA BORDER)
        // ==========================================
        $footerTable = $section->addTable(['borderSize' => 0]);
        $footerTable->addRow();

        $leftCell = $footerTable->addCell(5000);
        $leftCell->addText('Mengetahui,');
        $leftCell->addText('Kepala Madrasah');
        $leftCell->addTextBreak(3);
        $leftCell->addText('SUNAJI, S.Ag', ['bold' => true, 'underline' => 'single']);
        $leftCell->addText('NIP. ...........................');

        $rightCell = $footerTable->addCell(5000);
        $rightCell->addText('Cilegon, ' . $tanggalRapor, [], ['alignment' => Jc::RIGHT]);
        $rightCell->addText('Wali Kelas', [], ['alignment' => Jc::RIGHT]);
        $rightCell->addTextBreak(3);
        $rightCell->addText($namaWaliKelas, ['bold' => true, 'underline' => 'single'], ['alignment' => Jc::RIGHT]);
        $rightCell->addText('NIP. ...........................', [], ['alignment' => Jc::RIGHT]);

        // Save
        $fileName = 'RAPOR_' . $student->nama . '.docx';
        $path = storage_path('app/public/' . $fileName);
        IOFactory::createWriter($phpWord, 'Word2007')->save($path);

        return $path;
    }
}