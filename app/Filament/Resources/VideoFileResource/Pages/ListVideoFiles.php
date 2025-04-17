<?php

namespace App\Filament\Resources\VideoFileResource\Pages;

use App\Filament\Resources\VideoFileResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVideoFiles extends ListRecords
{
    protected static string $resource = VideoFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
