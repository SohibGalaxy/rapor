<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FinalScoreResource\Pages;
use App\Models\FinalScore;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class FinalScoreResource extends Resource
{
    protected static ?string $model = FinalScore::class;

    // ❌ Sembunyikan dari sidebar
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    /**
     * ❌ Tidak ada form (read only)
     */
    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return $form->schema([]);
    }

    /**
     * ✅ Tabel untuk monitoring / debugging
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.nama')
                    ->label('Nama Siswa')
                    ->searchable(),

                TextColumn::make('subject.name')
                    ->label('Mata Pelajaran'),

                TextColumn::make('semester')
                    ->badge(),

                TextColumn::make('final_score')
                    ->label('Nilai Akhir')
                    ->sortable(),
            ])
            ->actions([])       // ❌ tidak bisa edit
            ->bulkActions([]);  // ❌ tidak bisa delete
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFinalScores::route('/'),
        ];
    }
}
