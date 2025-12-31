<?php

namespace App\Filament\Resources\ClassRoomResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class ScoresRelationManager extends RelationManager
{
    protected static string $relationship = 'scores';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
            Select::make('student_id')->relationship('student', 'nama'),
            Select::make('subject_id')->relationship('subject', 'name'),
            Select::make('semester')->options([
                'ganjil'=>'Ganjil',
                'genap'=>'Genap'
            ]),
            TextInput::make('daily_score')->numeric(),
            TextInput::make('uts_score')->numeric(),
            TextInput::make('uas_score')->numeric(),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('student_id')
            ->columns([
            TextColumn::make('student.nama'),
            TextColumn::make('subject.name'),
            TextColumn::make('semester'),
            TextColumn::make('daily_score'),
            TextColumn::make('uts_score'),
            TextColumn::make('uas_score'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
