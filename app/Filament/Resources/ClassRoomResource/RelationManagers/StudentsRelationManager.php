<?php

namespace App\Filament\Resources\ClassRoomResource\RelationManagers;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\RelationManagers\RelationManager;

class StudentsRelationManager extends RelationManager
{
    /**
     * Nama relasi di model ClassRoom
     */
    protected static string $relationship = 'students';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama')
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Siswa')
                    ->searchable(),

                TextColumn::make('nis')
                    ->label('NIS')
                    ->searchable(),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Tambah Siswa')
                    ->recordTitleAttribute('nama')
                    ->preloadRecordSelect(),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->label('Keluarkan'),
            ])
            ->emptyStateHeading('Belum ada siswa')
            ->emptyStateDescription('Silakan tambahkan siswa ke kelas ini');
    }
}
