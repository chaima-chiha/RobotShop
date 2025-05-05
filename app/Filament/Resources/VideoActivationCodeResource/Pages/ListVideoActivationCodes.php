<?php

namespace App\Filament\Resources\VideoActivationCodeResource\Pages;

use App\Filament\Resources\VideoActivationCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVideoActivationCodes extends ListRecords
{
    protected static string $resource = VideoActivationCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
