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
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Auth;

class FinalScoresRelationManager extends RelationManager
{
    protected static string $relationship = 'finalScores';

    protected static ?string $title = 'Nilai Akhir';

    public function table(Table $table): Table
    {
        $student = $this->getOwnerRecord();
        $classRoomOptions = \App\Models\ClassRoom::whereHas('students', function (Builder $q) use ($student) {
            $q->where('student_id', $student->id);
        })->with(['schoolClass', 'academicYear'])->get()->mapWithKeys(function ($room) {
            return [$room->id => $room->schoolClass->name . ' - ' . $room->academicYear->name];
        })->toArray();

        return $table
            ->modifyQueryUsing(function (Builder $query) {
                if (Auth::user()?->isGuru()) {
                    $guruClassRoomIds = Auth::user()->teacher->classRooms()->pluck('id')->toArray();
                    $query->whereIn('class_room_id', $guruClassRoomIds);
                }
            })
            ->columns([
                TextColumn::make('subject.name')
                    ->label('Mata Pelajaran'),

                TextColumn::make('semester')
                    ->label('Semester')
                    ->badge(),

                TextColumn::make('final_score')
                    ->label('Nilai Akhir'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('semester')
                    ->options([
                        'Ganjil' => 'Ganjil',
                        'Genap' => 'Genap',
                    ])
                    ->label('Semester'),

                Tables\Filters\SelectFilter::make('class_room_id')
                    ->query(function (Builder $query, array $data): Builder {
                        if (isset($data['value'])) {
                            return $query->where('class_room_id', $data['value']);
                        }
                        return $query;
                    })
                    ->options($classRoomOptions)
                    ->label('Riwayat Kelas')
                    ->visible(fn () => Auth::user()->isAdmin()),
            ])
            ->headerActions([
               Action::make('exportExcel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function (RelationManager $livewire) {
                        $student = $livewire->getOwnerRecord();
                        $query = $livewire->getRelationship()->getQuery();

                        $semesterFilter = $livewire->getTableFilterState('semester');
                        if ($semesterFilter && isset($semesterFilter['value']) && !empty($semesterFilter['value'])) {
                            $query->where('semester', $semesterFilter['value']);
                        }

                        $classRoomFilter = $livewire->getTableFilterState('class_room_id');
                        if ($classRoomFilter && isset($classRoomFilter['value']) && !empty($classRoomFilter['value'])) {
                            $query->where('class_room_id', $classRoomFilter['value']);
                        }

                        $filteredFinalScores = $query->with('subject', 'classRoom.academicYear', 'classRoom.teacher')->get();

                        return Excel::download(
                            new FinalScoresExport($student, null, $filteredFinalScores),
                            'RAPOR_' . $student->nama . '.xlsx'
                        );
                    }),

                Action::make('export_word')
                    ->label('Export Word')
                    ->icon('heroicon-o-document-text')
                    ->color('primary')
                    ->action(function (RelationManager $livewire) {
                        $student = $livewire->getOwnerRecord();
                        $query = $livewire->getRelationship()->getQuery();

                        $semesterFilter = $livewire->getTableFilterState('semester');
                        if ($semesterFilter && isset($semesterFilter['value']) && !empty($semesterFilter['value'])) {
                            $query->where('semester', $semesterFilter['value']);
                        }

                        $classRoomFilter = $livewire->getTableFilterState('class_room_id');
                        if ($classRoomFilter && isset($classRoomFilter['value']) && !empty($classRoomFilter['value'])) {
                            $query->where('class_room_id', $classRoomFilter['value']);
                        }

                        $filteredFinalScores = $query->with('subject', 'classRoom.academicYear', 'classRoom.teacher')->get();

                        $path = FinalScoreWordExport::export($student, null, $filteredFinalScores);

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
