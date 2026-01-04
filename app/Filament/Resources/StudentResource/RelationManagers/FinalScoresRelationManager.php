<?php

namespace App\Filament\Resources\StudentResource\RelationManagers;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FinalScoresExport;
use App\Services\FinalScoreWordExport;

class FinalScoresRelationManager extends RelationManager
{
    protected static string $relationship = 'finalScores';

    protected static ?string $title = 'Nilai Akhir';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subject.name')
                    ->label('Mata Pelajaran'),

                TextColumn::make('semester')
                    ->label('Semester')
                    ->badge(),

                TextColumn::make('final_score')
                    ->label('Nilai Akhir'),
            ])
            ->headerActions([
               Action::make('exportExcel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success') // 🟢 WARNA HIJAU
                    ->action(function (RelationManager $livewire) {

                        // 🔑 SATU-SATUNYA CARA YANG BENAR
                        $student = $livewire->getOwnerRecord();

                        return Excel::download(
                            new FinalScoresExport($student),
                            'RAPOR_' . $student->nama . '.xlsx'
                        );
                    }),

                Action::make('export_word')
                    ->label('Export Word')
                    ->icon('heroicon-o-document-text')
                    ->color('primary')
                    ->action(function () {
                        $student = $this->getOwnerRecord();

                        $path = FinalScoreWordExport::export($student);

                        return response()->download($path)->deleteFileAfterSend();
                    }),
            ])
            ->defaultSort('subject_id')
            ->actions([])       // read-only
            ->bulkActions([]);  // read-only
    }

    protected function canCreate(): bool { return false; }
    protected function canEdit($record): bool { return false; }
    protected function canDelete($record): bool { return false; }
}
