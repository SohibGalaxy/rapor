<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Student;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\StudentResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StudentResource\RelationManagers;


class StudentResource extends Resource
{
    protected static ?string $model = Student::class;
    protected static ?string $navigationGroup = 'Oprasional';
    protected static ?string $navigationLabel = 'Murid';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    

    public static function form(Form $form): Form
    {
        $isGuru = Auth::user()->isGuru();

        return $form
            ->schema([
                 TextInput::make('nis')
                    ->required()
                    ->disabled($isGuru),
            TextInput::make('nama')
                    ->required()
                    ->disabled($isGuru),
            Select::make('gender')
                    ->options(['L'=>'L','P'=>'P'])
                    ->disabled($isGuru),
            TextInput::make('sekolah')
                    ->disabled($isGuru),
            Textarea::make('alamat')
                    ->disabled($isGuru),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $activeAcademicYear = \App\Models\AcademicYear::where('is_active', true)->first();

                if (Auth::user()?->isGuru()) {
                    $teacherClassRoomIds = Auth::user()->teacher->classRooms()->pluck('id')->toArray();
                    $query->whereHas('classRooms', function (Builder $q) use ($teacherClassRoomIds) {
                        $q->whereIn('class_rooms.id', $teacherClassRoomIds);
                    });
                } elseif ($activeAcademicYear && !Auth::user()?->isGuru()) {
                    $query->with(['classRooms' => function ($q) use ($activeAcademicYear) {
                        $q->where('academic_year_id', $activeAcademicYear->id);
                    }]);
                }
            })
            ->columns([
            TextColumn::make('nis')->searchable(),
            TextColumn::make('nama')->searchable(),
            TextColumn::make('gender'),
            TextColumn::make('active_classroom')
                ->label('Kelas')
                ->badge()
                ->getStateUsing(function ($record) {
                    $activeAcademicYear = \App\Models\AcademicYear::where('is_active', true)->first();
                    if ($activeAcademicYear) {
                        $activeClassRoom = $record->classRooms()
                            ->where('academic_year_id', $activeAcademicYear->id)
                            ->with('schoolClass')
                            ->first();
                        return $activeClassRoom ? $activeClassRoom->schoolClass->name : '-';
                    }
                    return '-';
                }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('academic_year_id')
                    ->label('Tahun Ajaran Aktif')
                    ->query(function (Builder $query, array $data): Builder {
                        if (isset($data['value']) && $data['value']) {
                            $query->whereHas('classRooms', function (Builder $q) use ($data) {
                                $q->whereHas('academicYear', function (Builder $aq) use ($data) {
                                    $aq->where('id', $data['value']);
                                    $aq->where('is_active', true);
                                });
                            });
                        }
                        return $query;
                    })
                    ->options(
                        \App\Models\AcademicYear::where('is_active', true)
                            ->pluck('name', 'id')
                            ->toArray()
                    )
                    ->searchable()
                    ->visible(fn () => Auth::user()->isAdmin()),

                Tables\Filters\SelectFilter::make('class_room_id')
                    ->relationship('classRooms', 'id', modifyQueryUsing: function (Builder $query) {
                        return $query->with(['schoolClass', 'academicYear'])
                            ->whereHas('academicYear', function (Builder $q) {
                                $q->where('is_active', true);
                            });
                    })
                    ->label('Kelas')
                    ->getOptionLabelFromRecordUsing(fn (\App\Models\ClassRoom $record) => 
                        $record->schoolClass->name . ' - ' . $record->academicYear->name
                    )
                    ->preload()
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ClassRoomsRelationManager::class,
            RelationManagers\FinalScoresRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
