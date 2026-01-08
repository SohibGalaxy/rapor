<?php

namespace App\Filament\Resources\ClassRoomResource\Pages;

use App\Filament\Resources\ClassRoomResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EditClassRoom extends EditRecord
{
    protected static string $resource = ClassRoomResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('school_class_id')
                    ->relationship('schoolClass', 'name')
                    ->required()
                    ->disabled(fn () => Auth::user()->isGuru()),
                Forms\Components\Select::make('academic_year_id')
                    ->relationship('academicYear', 'name')
                    ->required()
                    ->disabled(fn () => Auth::user()->isGuru()),
                Forms\Components\Select::make('teacher_id')
                    ->relationship('teacher', 'name')
                    ->required()
                    ->disabled(fn () => Auth::user()->isGuru()),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('promote')
                ->label('Promote Class')
                ->icon('heroicon-o-arrow-up-circle')
                ->color('success')
                ->visible(fn () => Auth::user()->isAdmin())
                ->form([
                    Forms\Components\Section::make('Promote ke Kelas Baru')
                        ->description('Pindahkan semua murid ke kelas baru')
                        ->schema([
                            Forms\Components\Placeholder::make('current_class')
                                ->label('Kelas Saat Ini')
                                ->content(fn () => $this->record->schoolClass->name),
                            Forms\Components\Placeholder::make('current_year')
                                ->label('Tahun Ajaran')
                                ->content(fn () => $this->record->academicYear->name),
                            Forms\Components\Placeholder::make('current_teacher')
                                ->label('Wali Kelas')
                                ->content(fn () => $this->record->teacher->name),
                        ])
                        ->columns(3),

                    Forms\Components\Section::make('Konfigurasi Baru')
                        ->schema([
                            Forms\Components\Select::make('targetSchoolClassId')
                                ->label('Kelas Tujuan')
                                ->options(\App\Models\SchoolClass::all()->pluck('name', 'id'))
                                ->required()
                                ->searchable(),

                            Forms\Components\Select::make('targetAcademicYearId')
                                ->label('Tahun Ajaran Baru')
                                ->options(\App\Models\AcademicYear::all()->pluck('name', 'id'))
                                ->required()
                                ->searchable(),

                            Forms\Components\Checkbox::make('changeTeacher')
                                ->label('Ubah Wali Kelas')
                                ->reactive(),

                            Forms\Components\Select::make('newTeacherId')
                                ->label('Wali Kelas Baru')
                                ->options(\App\Models\Teacher::all()->pluck('name', 'id'))
                                ->required(fn (Forms\Get $get) => $get('changeTeacher'))
                                ->searchable()
                                ->hidden(fn (Forms\Get $get) => !$get('changeTeacher')),
                        ])
                        ->columns(2),
                ])
                ->action(function (array $data) {
                    DB::beginTransaction();

                    try {
                        $teacherId = $data['changeTeacher']
                            ? $data['newTeacherId']
                            : $this->record->teacher_id;

                        $newClassRoom = \App\Models\ClassRoom::create([
                            'school_class_id' => $data['targetSchoolClassId'],
                            'academic_year_id' => $data['targetAcademicYearId'],
                            'teacher_id' => $teacherId,
                            'is_active' => true,
                        ]);

                        $students = $this->record->students()->get();

                        foreach ($students as $student) {
                            $newClassRoom->students()->attach($student->id);
                        }

                        $this->record->update(['is_active' => false]);

                        DB::commit();

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Promote Berhasil')
                            ->body(count($students) . ' siswa telah dipindahkan ke kelas baru.')
                            ->send();

                        return redirect(ClassRoomResource::getUrl('edit', ['record' => $newClassRoom->id]));
                    } catch (\Exception $e) {
                        DB::rollBack();

                        \Filament\Notifications\Notification::make()
                            ->danger()
                            ->title('Promote Gagal')
                            ->body($e->getMessage())
                            ->send();
                    }
                })
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Promote Class')
                ->modalDescription('Apakah Anda yakin ingin mempromote kelas ini? Kelas lama akan di-archived dan murid akan dipindahkan ke kelas baru.')
                ->modalSubmitActionLabel('Ya, Promote'),

            Actions\DeleteAction::make(),
        ];
    }
}
