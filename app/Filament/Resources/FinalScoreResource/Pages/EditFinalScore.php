<?php

namespace App\Filament\Resources\FinalScoreResource\Pages;

use App\Filament\Resources\FinalScoreResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFinalScore extends EditRecord
{
    protected static string $resource = FinalScoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
