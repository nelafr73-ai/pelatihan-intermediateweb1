<?php

namespace App\Filament\Resources\Branches\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Schema;

class BranchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('nama')
                    ->label('Nama')
                    ->required(),

                Textarea::make('alamat')
                    ->label('Alamat')
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('telepon')
                    ->label('Telepon')
                    ->tel(),

                Select::make('type')
                    ->label('Jenis Lokasi')
                    ->options([
                        'toko' => 'Toko',
                        'bengkel' => 'Bengkel',
                    ])
                    ->required()
                    ->default('toko'),

                TextInput::make('latitude')
                    ->label('Latitude')
                    ->required()
                    ->numeric()
                    ->minValue(-90)
                    ->maxValue(90)
                    ->helperText('Contoh: -6.1754'),

                TextInput::make('longitude')
                    ->label('Longitude')
                    ->required()
                    ->numeric()
                    ->minValue(-180)
                    ->maxValue(180)
                    ->helperText('Contoh: 106.8272'),

                ViewField::make('map_picker')
                    ->label('Peta Lokasi Cabang')
                    ->view('filament.forms.components.branch-map-picker')
                    ->dehydrated(false)
                    ->columnSpanFull(),

            ]);
    }
}
