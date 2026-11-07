<?php

namespace App\Filament\Resources\Lpks\Pages;

use App\Filament\Resources\Lpks\LpkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLpks extends ListRecords
{
    protected static string $resource = LpkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
