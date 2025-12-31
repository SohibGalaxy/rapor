<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\ClassRoom;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\ClassRoomResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ClassRoomResource\RelationManagers;
use App\Filament\Resources\ClassRoomResource\Pages\EditClassRoom;
use App\Filament\Resources\ClassRoomResource\Pages\ListClassRooms;
use App\Filament\Resources\ClassRoomResource\Pages\CreateClassRoom;

class ClassRoomResource extends Resource
{
    protected static ?string $model = ClassRoom::class;

    
    protected static ?string $navigationGroup = 'Oprasional';
    protected static ?string $navigationLabel = 'Kelas Aktif';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                 Select::make('school_class_id')
                ->relationship('schoolClass', 'name')
                ->required(),
            Select::make('academic_year_id')
                ->relationship('academicYear', 'name')
                ->required(),
            Select::make('teacher_id')
                ->relationship('teacher', 'name')
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                 TextColumn::make('schoolClass.name')->label('Kelas'),
            TextColumn::make('academicYear.name')->label('Tahun'),
            TextColumn::make('teacher.name')->label('Wali Kelas'),
            ])
            ->filters([
                //
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
            RelationManagers\StudentsRelationManager::class,
            RelationManagers\ScoresRelationManager::class,
            RelationManagers\FinalScoresRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClassRooms::route('/'),
            'create' => Pages\CreateClassRoom::route('/create'),
            'edit' => Pages\EditClassRoom::route('/{record}/edit'),
        ];
    }
}
