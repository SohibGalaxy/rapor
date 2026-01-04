<?php

namespace App\Filament\Widgets;

use App\Models\ClassRoom;
use Filament\Widgets\ChartWidget;

class SiswaPerKelasChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Siswa per Kelas';

    protected function getData(): array
    {
        $classes = ClassRoom::withCount('students')->get();
        $labels = [];
        $data = [];

        foreach ($classes as $class) {
            $labels[] = $class->schoolClass->name;
            $data[] = $class->students_count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Siswa',
                    'data' => $data,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.7)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
