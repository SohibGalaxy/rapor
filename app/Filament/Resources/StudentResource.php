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
        return $form
            ->schema([
                 TextInput::make('nis')->required(),
            TextInput::make('nama')->required(),
            Select::make('gender')->options(['L'=>'L','P'=>'P']),
            TextInput::make('sekolah'),
            Textarea::make('alamat'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                if (Auth::user()?->isGuru()) {
                    $classRoomIds = Auth::user()->teacher->classRooms()->pluck('id')->toArray();
                    $query->whereHas('classRooms', function (Builder $q) use ($classRoomIds) {
                        $q->whereIn('class_rooms.id', $classRoomIds);
                    });
                }
            })
            ->columns([
            TextColumn::make('nis')->searchable(),
            TextColumn::make('nama')->searchable(),
            TextColumn::make('gender'),
            TextColumn::make('classRooms.schoolClass.name')
                ->label('Kelas')
                ->badge()
                ->separator(', '),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('class_room_id')
                    ->relationship('classRooms', 'id', modifyQueryUsing: function (Builder $query) {
                        return $query->with(['schoolClass', 'academicYear']);
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
