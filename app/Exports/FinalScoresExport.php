<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class FinalScoresExport implements FromArray, WithEvents, WithStyles
{
    protected Student $student;
    protected int $tableStartRow;
    protected int $tableEndRow;
    protected $classRoom;
    protected $semester;

    public function __construct(Student $student)
    {
        $this->student = $student->load('finalScores.subject', 'finalScores.classRoom.academicYear', 'finalScores.classRoom.teacher');
        $firstFinalScore = $this->student->finalScores->first();
        $this->classRoom = $firstFinalScore ? $firstFinalScore->classRoom : null;
        $this->semester = $firstFinalScore ? $firstFinalScore->semester : null;
    }

    public function array(): array
    {
        $rows = [];

        $madrasah = $this->student->sekolah ?? 'MI Pabean';
        $namaKelas = $this->classRoom ? $this->classRoom->schoolClass->name : $this->student->kelas ?? '-';
        $namaWaliKelas = $this->classRoom ? $this->classRoom->teacher->name : 'Nama Wali Kelas';
        $tahunAjaran = $this->classRoom ? $this->classRoom->academicYear->name : '-';
        $semester = ucfirst($this->semester ?? 'Ganjil');
        $tanggalRapor = now()->format('d F Y');

        /* ================= KOP (Baris 1-4) ================= */
        $rows[] = ['', 'KEMENTERIAN AGAMA REPUBLIK INDONESIA'];
        $rows[] = ['', 'MIS AL-KHAIRIYAH PABEAN'];
        $rows[] = ['', 'JL. PABEAN NO 03 LINK KARANG TENGAH RT. 010 RW. 004'];
        $rows[] = ['', 'Kecamatan Purwakarta, Kota Cilegon – Banten'];
        $rows[] = [''];

        /* ================= BIODATA ================= */
        $rows[] = ['Nama', ': ' . $this->student->nama];
        $rows[] = ['NIS/NISN', ': ' . $this->student->nis];
        $rows[] = ['Kelas', ': ' . $namaKelas];
        $rows[] = ['Alamat', ': ' . $this->student->alamat];
        $rows[] = ['Madrasah', ': ' . $madrasah];
        $rows[] = ['Fase', ': A'];
        $rows[] = ['Semester', ': ' . $semester];
        $rows[] = ['Tahun Ajaran', ': ' . $tahunAjaran];
        $rows[] = [''];

        /* ================= JUDUL ================= */
        $rows[] = ['CAPAIAN HASIL BELAJAR'];
        $rows[] = [''];

        /* ================= TABEL NILAI ================= */
        $this->tableStartRow = count($rows) + 1;

        $rows[] = ['No', 'Mata Pelajaran', 'Nilai Akhir'];

        $no = 1;
        $total = 0;
        foreach ($this->student->finalScores as $score) {
            $rows[] = [$no++, $score->subject->name, $score->final_score];
            $total += $score->final_score;
        }

        $rows[] = ['', 'JUMLAH', $total];
        $this->tableEndRow = count($rows);
        $rows[] = [''];
        $rows[] = [''];

        /* ================= TANDA TANGAN ================= */
        $rows[] = ['Mengetahui,', '', 'Cilegon, ' . $tanggalRapor];
        $rows[] = ['Kepala Madrasah,', '', 'Wali Kelas,'];
        $rows[] = ['', '', ''];
        $rows[] = ['', '', ''];
        $rows[] = ['SUNAJI, S.Ag', '', $namaWaliKelas];
        $rows[] = ['NIP.', '', 'NIP.'];

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        // Set Font Global seperti di Word
        $sheet->getParent()->getDefaultStyle()->getFont()->setName('Times New Roman');
        $sheet->getParent()->getDefaultStyle()->getFont()->setSize(11);

        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
            2 => ['font' => ['bold' => true, 'size' => 14]],
            17 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->setShowGridlines(false);

                $drawing = new Drawing();
                $drawing->setName('Logo');
                $drawing->setPath(public_path('img/mi.png'));
                $drawing->setHeight(60);
                $drawing->setCoordinates('A1');
                $drawing->setOffsetX(20);
                $drawing->setWorksheet($sheet);

                $sheet->getColumnDimension('A')->setWidth(16);
                $sheet->getColumnDimension('B')->setWidth(40);
                $sheet->getColumnDimension('C')->setWidth(22);

                $sheet->mergeCells('B1:C1');
                $sheet->mergeCells('B2:C2');
                $sheet->mergeCells('B3:C3');
                $sheet->mergeCells('B4:C4');
                $sheet->getStyle('B1:C4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle('A4:C4')->getBorders()->getBottom()
                    ->setBorderStyle(Border::BORDER_THICK);

                $sheet->mergeCells('A17:C17');
                $sheet->getStyle('A17')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $start = $this->tableStartRow;
                $end = $this->tableEndRow;
                $sheet->getStyle("A{$start}:C{$end}")->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                $sheet->getStyle("A{$start}:C{$start}")->getFont()->setBold(true);
                $sheet->getStyle("A{$start}:A{$end}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("C{$start}:C{$end}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle("A{$end}:C{$end}")->getFont()->setBold(true);

                $footerStart = $end + 3;
                $nameRow = $footerStart + 4;

                $sheet->getStyle("A{$nameRow}")->getFont()->setBold(true)->setUnderline(true);
                $sheet->getStyle("C{$nameRow}")->getFont()->setBold(true)->setUnderline(true);

                $sheet->getStyle("C" . ($footerStart) . ":C" . ($nameRow + 1))
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                foreach (range(7, 16) as $row) {
                    $sheet->getStyle("A{$row}:C{$row}")->getFont()->setSize(11);
                }
            }
        ];
    }
}