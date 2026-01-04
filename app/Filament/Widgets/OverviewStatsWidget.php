<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\ClassRoom;
use App\Models\Subject;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OverviewStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Siswa', Student::count())
                ->description('Jumlah seluruh siswa')
                ->descriptionIcon('heroicon-m-users')
                ->chart([7, 12, 10, 14, 15, 20])
                ->color('primary'),

            Stat::make('Total Guru', Teacher::count())
                ->description('Jumlah seluruh guru')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->chart([5, 8, 10, 12, 15, 18])
                ->color('success'),

            Stat::make('Total Kelas Aktif', ClassRoom::count())
                ->description('Jumlah kelas aktif')
                ->descriptionIcon('heroicon-m-building-library')
                ->chart([2, 4, 6, 8, 10, 12])
                ->color('warning'),

            Stat::make('Total Mata Pelajaran', Subject::count())
                ->description('Jumlah mata pelajaran')
                ->descriptionIcon('heroicon-m-book-open')
                ->chart([3, 6, 9, 12, 15, 18])
                ->color('danger'),
        ];
    }
}
