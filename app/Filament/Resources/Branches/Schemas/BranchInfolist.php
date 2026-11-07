<?php

namespace App\Filament\Resources\Branches\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Schemas\Schema;

class BranchInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextEntry::make('nama')
                    ->label('Nama Cabang'),

                TextEntry::make('alamat')
                    ->label('Alamat')
                    ->columnSpanFull(),

                TextEntry::make('telepon')
                    ->label('Telepon')
                    ->placeholder('-'),

                TextEntry::make('type')
                    ->label('Jenis Lokasi')
                    ->badge(),

                TextEntry::make('latitude')
                    ->label('Latitude')
                    ->numeric(),

                TextEntry::make('longitude')
                    ->label('Longitude')
                    ->numeric(),

                ViewEntry::make('map')
                ->label('Lokasi Cabang')
                ->state(fn ($record) => $record)
                ->view('filament.infolists.entries.branch-map')
                ->columnSpanFull(),

                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),

                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),

            ]);
    }
}
