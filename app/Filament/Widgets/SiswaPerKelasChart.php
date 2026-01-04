<?php

namespace App\Filament\Widgets;

use App\Models\ClassRoom;
use Filament\Widgets\ChartWidget;

class SiswaPerKelasChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Siswa per Kelas';

    protected int|string|array $columnSpan = 'full';

    protected static ?string $maxHeight = '320px'; // ✅ INI YANG BERPENGARUH

    protected function getData(): array
    {
        $classes = ClassRoom::withCount('students')->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Siswa',
                    'data' => $classes->pluck('students_count'),
                ],
            ],
            'labels' => $classes->pluck('schoolClass.name'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
