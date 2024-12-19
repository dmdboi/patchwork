<?php

namespace TomatoPHP\FilamentMenus\Resources\MenuResource\Relations;

use App\Models\Post;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;


class MenuItems extends RelationManager
{
    protected static string $relationship = 'menuItems';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return trans('filament-menus::messages.title');
    }

    public static function getNavigationLabel(): string
    {
        return trans('filament-menus::messages.title');
    }

    public static function getPluralLabel(): ?string
    {
        return trans('filament-menus::messages.title');
    }

    public static function getLabel(): ?string
    {
        return trans('filament-menus::messages.title');
    }

    public static function getModelLabel(): string
    {
        return trans('filament-menus::messages.title');
    }

    public function form(Form $form): Form
    {
        $routeList = Post::query()->orderBy('slug', 'desc')->get();
        $routeList = $routeList->map(function ($item) {
            if ($item['type'] !== 'page') {
                $item['title'] = '[' . $item['type'] . '] ' . $item['title'];
            }

            return $item;
        });

        $repeaterSchema = [];

        return $form->schema([
            Forms\Components\Grid::make(["default" => 1])->schema(array_merge([
                Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_route')
                    ->default(true)
                    ->label(trans('filament-menus::messages.cols.item.is_route'))
                    ->required()
                    ->live(),
                Forms\Components\TextInput::make('url')
                    ->label(trans('filament-menus::messages.cols.item.url'))
                    ->hidden(fn(Forms\Get $get) => $get('is_route') === true)
                    ->required(fn(Forms\Get $get) => $get('is_route') === false)
                    ->maxLength(255),
                Forms\Components\Select::make('route')
                    ->label(trans('filament-menus::messages.cols.item.route'))
                    ->hidden(fn(Forms\Get $get) => $get('is_route') === false)
                    ->required(fn(Forms\Get $get) => $get('is_route') === true)
                    ->searchable()
                    ->options(collect($routeList)->pluck('title', 'slug')->toArray()),
                Forms\Components\Toggle::make('new_tab')
                    ->label(trans('filament-menus::messages.cols.item.target'))
                    ->required()
            ], $repeaterSchema))
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Tables\Actions\CreateAction::make()
            ])
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Page'),
                Tables\Columns\TextColumn::make('route')
                    ->label('Slug')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
            ])
            ->defaultSort('order', 'asc')
            ->reorderable('order');
    }
}
