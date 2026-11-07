<?php

namespace App\Filament\Resources\Lpks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LpkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('domain')
                    ->default(null),
                Textarea::make('settings')
                    ->default(null)
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('lpk_name')
                    ->required(),
                TextInput::make('legal_name')
                    ->default(null),
                TextInput::make('nib')
                    ->default(null),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('phone')
                    ->tel()
                    ->default(null),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->default(null),
                TextInput::make('website')
                    ->url()
                    ->default(null),
                TextInput::make('instagram')
                    ->default(null),
                TextInput::make('facebook')
                    ->default(null),
                TextInput::make('tiktok')
                    ->default(null),
                TextInput::make('province')
                    ->default(null),
                TextInput::make('city')
                    ->default(null),
                TextInput::make('district')
                    ->default(null),
                Textarea::make('address')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('latitude')
                    ->numeric()
                    ->default(null),
                TextInput::make('longitude')
                    ->numeric()
                    ->default(null),
                TextInput::make('logo')
                    ->default(null),
                TextInput::make('banner')
                    ->default(null),
                Textarea::make('facilities')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
