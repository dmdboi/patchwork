<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use App\Services\FilamentCMSTypes;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use TomatoPHP\FilamentIcons\Components\IconColumn;
use TomatoPHP\FilamentIcons\Components\IconPicker;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        return auth()->user()->hasAccess('view', 'categories');
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Content';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Categories';
    }

    public static function getLabel(): ?string
    {
        return 'Category';
    }

    public static function getNavigationLabel(): string
    {
        return 'Categories';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make([
                    'default' => 1,
                    'sm' => 1,
                    'md' => 6,
                    'lg' => 12,
                ])->schema([
                    Forms\Components\Grid::make()
                        ->schema([
                            Forms\Components\Section::make('Details')
                                ->description('Enter the details of the category.')
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->afterStateUpdated(fn (Forms\Get $get, Forms\Set $set) => $set('slug', Str::of($get('name'))->replace(' ', '-')->lower()->toString()))
                                        ->label('Name')
                                        ->lazy()
                                        ->required(),
                                    Forms\Components\TextInput::make('slug')
                                        ->label('Slug')
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\Textarea::make('description')
                                        ->columnSpanFull()
                                        ->label('Description'),
                                    IconPicker::make('icon')
                                        ->label('Icon'),
                                    Forms\Components\ColorPicker::make('color')
                                        ->label('Color'),
                                ])
                                ->columns(2),
                            Forms\Components\Section::make('Images')
                                ->description('Upload images for the category.')
                                ->schema([
                                    Forms\Components\SpatieMediaLibraryFileUpload::make('feature_image')
                                        ->label('Feature Image')
                                        ->collection('feature_image')
                                        ->image()
                                        ->maxFiles(1)
                                        ->maxSize(2048)
                                        ->maxWidth(1920),
                                    Forms\Components\SpatieMediaLibraryFileUpload::make('cover_image')
                                        ->label('Cover Image')
                                        ->collection('cover_image')
                                        ->image()
                                        ->maxFiles(1)
                                        ->maxSize(2048)
                                        ->maxWidth(1920),
                                ]),
                        ])
                        ->columnSpan([
                            'sm' => 1,
                            'md' => 4,
                            'lg' => 8,
                        ]),
                    Forms\Components\Section::make('Meta')
                        ->description('Enter the meta details of the category.')
                        ->schema([
                            Forms\Components\Select::make('for')
                                ->label('For')
                                ->searchable()
                                ->live()
                                ->options(fn () => FilamentCMSTypes::getOptions()->pluck('label', 'key')->toArray())
                                ->default('post'),
                            Forms\Components\Select::make('type')
                                ->hidden(function (Forms\Get $get) {
                                    $for = FilamentCMSTypes::getOptions()->where('key', $get('for'))->first();
                                    if ($for && count($for->sub)) {
                                        return false;
                                    }
                                })
                                ->label('Type')
                                ->searchable()
                                ->options(fn (Forms\Get $get) => FilamentCMSTypes::getOptions()->where('key', $get('for'))->first()?->getSub()->pluck('label', 'key')->toArray())
                                ->default('category'),
                            Forms\Components\Select::make('parent_id')
                                ->label('Parent')
                                ->searchable()
                                ->options(fn () => Category::query()->pluck('name', 'id')->toArray()),
                            Forms\Components\Toggle::make('is_active')
                                ->label('Active'),
                            Forms\Components\Toggle::make('show_in_menu')
                                ->label('Show in menu'),
                        ])
                        ->columnSpan([
                            'sm' => 1,
                            'md' => 2,
                            'lg' => 4,
                        ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('feature_image')
                    ->label('Feature Image')
                    ->defaultImageUrl(fn (Category $category) => 'https://ui-avatars.com/api/?name='.Str::of($category->slug)->replace('-', '+').'&color=FFFFFF&background=020617')
                    ->square()
                    ->collection('feature_image'),
                Tables\Columns\TextColumn::make('name')
                    ->description(fn (Category $category) => Str::of($category->description)->limit(50))
                    ->label('Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('for')
                    ->state(function (Category $category) {
                        return FilamentCMSTypes::getOptions()->where('key', $category->for)->first()?->label;
                    })
                    ->color(function (Category $category) {
                        return FilamentCMSTypes::getOptions()->where('key', $category->for)->first()?->color;
                    })
                    ->icon(function (Category $category) {
                        return FilamentCMSTypes::getOptions()->where('key', $category->for)->first()?->icon;
                    })
                    ->badge()
                    ->sortable()
                    ->label('For')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->state(function (Category $category) {
                        return FilamentCMSTypes::getOptions()->where('key', $category->for)->first()?->getSub()->where('key', $category->type)->first()?->label;
                    })
                    ->color(function (Category $category) {
                        return FilamentCMSTypes::getOptions()->where('key', $category->for)->first()?->getSub()->where('key', $category->type)->first()?->color;
                    })
                    ->icon(function (Category $category) {
                        return FilamentCMSTypes::getOptions()->where('key', $category->for)->first()?->getSub()->where('key', $category->type)->first()?->icon;
                    })
                    ->badge()
                    ->sortable()
                    ->label('Type')
                    ->searchable(),
                IconColumn::make('icon')
                    ->label('Icon')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\ColorColumn::make('color')
                    ->label('Color')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->sortable()
                    ->label('Active'),
                Tables\Columns\ToggleColumn::make('show_in_menu')
                    ->sortable()
                    ->label('Show in menu'),
                Tables\Columns\TextColumn::make('parent.name')
                    ->sortable()
                    ->label('Parent')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('for')
                    ->form([
                        Forms\Components\Select::make('for')
                            ->label('For')
                            ->searchable()
                            ->live()
                            ->options(fn () => FilamentCMSTypes::getOptions()->pluck('label', 'key')->toArray()),
                        Forms\Components\Select::make('type')
                            ->hidden(function (Forms\Get $get) {
                                $for = FilamentCMSTypes::getOptions()->where('key', $get('for'))->first();
                                if ($for && count($for->sub)) {
                                    return false;
                                }
                            })
                            ->label('Type')
                            ->searchable()
                            ->options(fn (Forms\Get $get) => FilamentCMSTypes::getOptions()->where('key', $get('for'))->first()?->getSub()->pluck('label', 'key')->toArray()),

                    ])
                    ->query(function (Builder $query, array $data) {
                        $query->when(
                            $data['for'],
                            fn (Builder $query, $for) => $query->where('for', $for)
                        )->when(
                            $data['type'],
                            fn (Builder $query, $type) => $query->where('type', $type)
                        );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->iconButton()
                    ->tooltip('View'),
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->tooltip('Edit'),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->tooltip('Delete'),
                Tables\Actions\ForceDeleteAction::make()
                    ->iconButton()
                    ->tooltip('Force delete'),
                Tables\Actions\RestoreAction::make()
                    ->iconButton()
                    ->tooltip('Restore'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
