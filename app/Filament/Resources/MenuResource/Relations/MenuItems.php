<?php

namespace App\Filament\Resources\MenuResource\Relations;

use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class MenuItems extends RelationManager
{
    protected static string $relationship = 'menuItems';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return 'Menu Items';
    }

    public static function getNavigationLabel(): string
    {
        return 'MenuItems';
    }

    public static function getPluralLabel(): ?string
    {
        return 'MenuItems';
    }

    public static function getLabel(): ?string
    {
        return 'MenuItem';
    }

    public static function getModelLabel(): string
    {
        return 'MenuItem';
    }

    public function form(Form $form): Form
    {
        $routeList = Post::query()->orderBy('slug', 'desc')->get();
        $routeList = $routeList->map(function ($item) {
            if ($item['type'] !== 'page') {
                $item['title'] = '['.$item['type'].'] '.$item['title'];
            }

            return $item;
        });

        $repeaterSchema = [];

        return $form->schema([
            Forms\Components\Grid::make(['default' => 1])->schema(array_merge([
                Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_route')
                    ->default(true)
                    ->label('Is Route')
                    ->required()
                    ->live(),
                Forms\Components\TextInput::make('url')
                    ->label('URL')
                    ->hidden(fn (Forms\Get $get) => $get('is_route') === true)
                    ->required(fn (Forms\Get $get) => $get('is_route') === false)
                    ->maxLength(255),
                Forms\Components\Select::make('route')
                    ->label('Route')
                    ->hidden(fn (Forms\Get $get) => $get('is_route') === false)
                    ->required(fn (Forms\Get $get) => $get('is_route') === true)
                    ->searchable()
                    ->options(collect($routeList)->pluck('title', 'slug')->toArray()),
                Forms\Components\Toggle::make('new_tab')
                    ->label('Open in new tab')
                    ->required(),
            ], $repeaterSchema)),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Page'),
                Tables\Columns\TextColumn::make('route')
                    ->label('Slug'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('order', 'asc')
            ->reorderable('order');
    }
}
