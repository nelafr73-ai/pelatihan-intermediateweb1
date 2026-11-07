<?php

namespace App\Filament\Resources\Lpks\Pages;

use App\Filament\Resources\Lpks\LpkResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditLpk extends EditRecord
{
    protected static string $resource = LpkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
