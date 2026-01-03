<?php

namespace App\Filament\Resources\ClassRoomResource\Pages;

use App\Models\ClassRoom;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;

class BulkInputScores extends Page
{
    protected static string $resource = \App\Filament\Resources\ClassRoomResource::class;
    protected static string $view = 'filament.resources.class-room-resource.pages.bulk-input-scores';

    public ClassRoom $record;

    public function mount(ClassRoom $record): void
    {
        $this->record = $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Kembali')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(fn(): string => static::$resource::getUrl('index')),
        ];
    }
}
