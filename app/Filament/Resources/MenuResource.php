<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use App\Models\Menu;
use Filament\Tables;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\Filter;
use App\Filament\Resources\MenuResource\Pages;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Filament\Resources\MenuResource\Relations\MenuItems;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $slug = 'menus';

    protected static ?string $recordTitleAttribute = "title";

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';

    protected static ?string $navigationGroup = 'Settings';

    public static function canViewAny(): bool
    {
        return auth()->user()->hasAccess('view', 'menus');
    }

    public static function getNavigationLabel(): string
    {
        return 'Menus';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Menus';
    }

    public static function getLabel(): ?string
    {
        return 'Menu';
    }

    public static function getModelLabel(): string
    {
        return 'Menu';
    }

    public static function getRelations(): array
    {
        return [
            MenuItems::make()
        ];
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Grid::make(["default" => 3])->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Title')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('key')
                        ->label('Key')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('location')
                        ->label('Location')
                        ->required()
                        ->default('header')
                        ->maxLength(255),
                    Forms\Components\Toggle::make('activated')
                        ->default(true)
                        ->label('Active')
                        ->required(),
                ])
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {

        $table->actions([
            ActionGroup::make([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
            ]),
        ]);

        return $table
            ->recordAction(null)
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('key')
                    ->label('Key')
                    ->view('components/menu-item')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location')
                    ->label('Location')
                    ->sortable(),
                Tables\Columns\BooleanColumn::make('activated')
                    ->label('Active'),
            ])
            ->filters([
                Filter::make('activated')
                    ->label('Active')
                    ->query(fn(Builder $query): Builder => $query->where('activated', true))
            ]);

    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMenus::route('/'),
            'create' => Pages\CreateMenus::route('/create'),
            'edit' => Pages\EditMenus::route('/{record}'),
        ];
    }
}
