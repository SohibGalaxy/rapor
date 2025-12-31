<?php

namespace App\Filament\Resources\FinalScoreResource\Pages;

use App\Filament\Resources\FinalScoreResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFinalScores extends ListRecords
{
    protected static string $resource = FinalScoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
