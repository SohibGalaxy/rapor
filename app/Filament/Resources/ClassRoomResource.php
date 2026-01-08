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
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\ClassRoomResource\Pages;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ClassRoomResource\RelationManagers;
use App\Filament\Resources\ClassRoomResource\Pages\EditClassRoom;
use App\Filament\Resources\ClassRoomResource\Pages\ListClassRooms;
use App\Filament\Resources\ClassRoomResource\Pages\CreateClassRoom;
use App\Filament\Resources\ClassRoomResource\Pages\BulkInputScores;
use App\Filament\Resources\ClassRoomResource\Pages\PromoteClassRoom;

class ClassRoomResource extends Resource
{
    protected static ?string $model = ClassRoom::class;


    protected static ?string $navigationGroup = 'Oprasional';
    protected static ?string $navigationLabel = 'Kelas Aktif';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function canCreate(): bool
    {
        return Auth::user()?->isAdmin() ?? false;
    }

    public static function canEdit($record): bool
    {
        if (Auth::user()?->isAdmin()) {
            return true;
        }

        return Auth::user()?->teacher?->id === $record->teacher_id;
    }

    public static function canDelete($record): bool
    {
        if (Auth::user()?->isAdmin()) {
            return true;
        }

        return Auth::user()?->teacher?->id === $record->teacher_id;
    }

    public static function canViewAny(): bool
    {
        return true;
    }

    public static function canView($record): bool
    {
        if (Auth::user()?->isAdmin()) {
            return true;
        }

        return Auth::user()?->teacher?->id === $record->teacher_id;
    }

    public static function form(Form $form): Form
    {
        $isGuru = Auth::user()?->isGuru();

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
                ->default($isGuru ? Auth::user()->teacher->id : null)
                ->disabled($isGuru)
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                if (Auth::user()?->isGuru()) {
                    $query->where('teacher_id', Auth::user()?->teacher?->id);
                }
            })
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
                Action::make('bulkInput')
                    ->label('Input Nilai')
                    ->icon('heroicon-o-document-plus')
                    ->color('warning')
                    ->url(fn(ClassRoom $record): string => ClassRoomResource::getUrl('bulk-input-scores', ['record' => $record])),
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
            'bulk-input-scores' => Pages\BulkInputScores::route('/{record}/bulk-input-scores'),
        ];
    }
}
