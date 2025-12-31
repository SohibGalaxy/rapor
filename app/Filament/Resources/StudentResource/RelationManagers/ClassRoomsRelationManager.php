<?php

namespace App\Filament\Resources\StudentResource\RelationManagers;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\RelationManagers\RelationManager;

class ClassRoomsRelationManager extends RelationManager
{
    protected static string $relationship = 'classRooms';

    protected static ?string $title = 'Kelas Siswa';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitle(fn ($record) =>
                $record->schoolClass->name . ' - ' . $record->academicYear->name
            )
            ->columns([
                TextColumn::make('schoolClass.name')
                    ->label('Nama Kelas'),

                TextColumn::make('academicYear.name')
                    ->label('Tahun Ajaran'),

                TextColumn::make('teacher.name')
                    ->label('Wali Kelas'),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Masukkan ke Kelas')
                    ->recordTitle(fn ($record) =>
                        $record->schoolClass->name . ' - ' . $record->academicYear->name
                    )
                    ->preloadRecordSelect(),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->label('Keluarkan'),
            ])
            ->bulkActions([]);
    }
}
