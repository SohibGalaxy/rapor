<?php

namespace App\Filament\Resources\ClassRoomResource\RelationManagers;

use App\Models\Score;
use App\Models\FinalScore;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\RelationManagers\RelationManager;

class FinalScoresRelationManager extends RelationManager
{
    protected static string $relationship = 'finalScores';

    protected static ?string $title = 'Nilai Akhir (Final Score)';

    public function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.nama')
                    ->label('Nama Murid')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('subject.name')
                    ->label('Mata Pelajaran')
                    ->sortable(),

                TextColumn::make('semester')
                    ->badge(),

                TextColumn::make('final_score')
                    ->label('Nilai Akhir')
                    ->sortable(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('generateFinalScore')
                    ->label('Generate Final Score')
                    ->icon('heroicon-o-calculator')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->action(function ($livewire) {

                        $classRoom = $livewire->ownerRecord;

                        // Ambil semua score di kelas ini
                        $scores = Score::where('class_room_id', $classRoom->id)->get();

                        foreach ($scores as $score) {

                            $final = 
                                ($score->daily_score * 0.3) +
                                ($score->uts_score   * 0.3) +
                                ($score->uas_score   * 0.4);

                            FinalScore::updateOrCreate(
                                [
                                    'student_id' => $score->student_id,
                                    'class_room_id' => $classRoom->id,
                                    'subject_id' => $score->subject_id,
                                    'semester' => $score->semester,
                                ],
                                [
                                    'final_score' => round($final, 2),
                                ]
                            );
                        }
                    }),
            ])
            ->actions([])
            ->bulkActions([]);
    }

    protected function canCreate(): bool
    {
        return false;
    }

    protected function canEdit($record): bool
    {
        return false;
    }

    protected function canDelete($record): bool
    {
        return false;
    }
}
