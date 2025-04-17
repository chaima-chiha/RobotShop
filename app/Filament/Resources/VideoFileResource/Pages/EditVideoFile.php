<?php

namespace App\Filament\Resources\VideoFileResource\Pages;

use App\Filament\Resources\VideoFileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVideoFile extends EditRecord
{
    protected static string $resource = VideoFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
