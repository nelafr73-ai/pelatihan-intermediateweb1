<?php

namespace App\Filament\Resources\Lpks;

use App\Filament\Resources\Lpks\Pages\CreateLpk;
use App\Filament\Resources\Lpks\Pages\EditLpk;
use App\Filament\Resources\Lpks\Pages\ListLpks;
use App\Filament\Resources\Lpks\Pages\ViewLpk;
use App\Filament\Resources\Lpks\Schemas\LpkForm;
use App\Filament\Resources\Lpks\Schemas\LpkInfolist;
use App\Filament\Resources\Lpks\Tables\LpksTable;
use App\Models\Lpk;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LpkResource extends Resource
{
    protected static ?string $model = Lpk::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'LPK';

    public static function form(Schema $schema): Schema
    {
        return LpkForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LpkInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LpksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLpks::route('/'),
            'create' => CreateLpk::route('/create'),
            'view' => ViewLpk::route('/{record}'),
            'edit' => EditLpk::route('/{record}/edit'),
        ];
    }
}
