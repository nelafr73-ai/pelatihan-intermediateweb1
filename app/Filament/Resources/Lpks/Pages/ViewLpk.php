<?php

namespace App\Filament\Resources\Lpks\Pages;

use App\Filament\Resources\Lpks\LpkResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLpk extends ViewRecord
{
    protected static string $resource = LpkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
