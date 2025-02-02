<?php

namespace App\Filament\Resources;

use App\Events\PostDeleted;
use App\Filament\Resources\PageResource\Pages;
use App\Infolists\Components\MarkdownEntry;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Services\FilamentCMSTypes;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\TextEntry\TextEntrySize;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;

class PageResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 4;

    public static function canViewAny(): bool
    {
        return auth()->user()->hasAccess('view', 'posts');
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Content';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Pages';
    }

    public static function getLabel(): ?string
    {
        return 'Page';
    }

    public static function getNavigationLabel(): string
    {
        return 'Pages';
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
                ])
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema(
                                [
                                    Forms\Components\Section::make('Title')
                                        ->description('Enter details of the Page')
                                        ->schema([
                                            Forms\Components\TextInput::make('title')
                                                ->label('Title')
                                                ->lazy()
                                                ->required(),
                                            Forms\Components\TextInput::make('slug')
                                                ->label('Slug')
                                                ->required()
                                                ->maxLength(255),
                                            Forms\Components\MarkdownEditor::make('body')
                                                ->label('Body')
                                                ->toolbarButtons(config('filament-cms.editor.options'))
                                                ->columnSpanFull(),
                                        ])
                                        ->columns(2),
                                    Forms\Components\Section::make('SEO')
                                        ->description('Enter SEO details')
                                        ->schema([
                                            Forms\Components\TextInput::make('short_description')->label('Short description'),
                                            Forms\Components\Textarea::make('keywords')->autosize()->label('Keywords'),
                                        ]),
                                ]
                            )
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 4,
                                'lg' => 8,
                            ]),
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\Section::make('Metadata')
                                    ->description('Enter metadata of the Page')
                                    ->schema([
                                        Forms\Components\Select::make('author_id')
                                            ->label('Author')
                                            ->options(User::all()->pluck('name', 'id')->toArray())
                                            ->default(
                                                auth()->id()
                                            )
                                            ->selectablePlaceholder(false)
                                            ->searchable(),
                                        Forms\Components\Select::make('categories')
                                            ->hidden(fn (Forms\Get $get) => in_array($get('type'), ['page', 'builder']))
                                            ->relationship('categories', 'name')
                                            ->label('Categories')
                                            ->createOptionForm([
                                                Forms\Components\TextInput::make('name')
                                                    ->label('Name')
                                                    ->required(),
                                            ])
                                            ->createOptionUsing(function (array $data) {
                                                $category = Category::query()->create([
                                                    'name' => $data['name'],
                                                    'slug' => Str::of($data['name'])->replace(' ', '-')->lower()->toString(),
                                                    'for' => 'post',
                                                    'type' => 'category',
                                                ]);

                                                return $category->id;
                                            })
                                            ->editOptionForm([
                                                Forms\Components\TextInput::make('name')
                                                    ->label('Name')
                                                    ->required(),
                                            ])
                                            ->searchable()
                                            ->multiple()
                                            ->preload()
                                            ->options(fn (Forms\Get $get) => Category::where('for', $get('type'))->where('type', 'category')->pluck('name', 'id')->toArray()),
                                        Forms\Components\Select::make('tags')
                                            ->hidden(fn (Forms\Get $get) => $get('type') !== 'post')
                                            ->label('Tags')
                                            ->searchable()
                                            ->multiple()
                                            ->preload()
                                            ->relationship('tags', 'name')
                                            ->createOptionForm([
                                                Forms\Components\TextInput::make('name')
                                                    ->label('Name')
                                                    ->required(),
                                            ])
                                            ->createOptionUsing(function (array $data) {
                                                $category = Category::query()->create([
                                                    'name' => $data['name'],
                                                    'slug' => Str::of($data['name'])->replace(' ', '-')->lower()->toString(),
                                                    'for' => 'post',
                                                    'type' => 'tag',
                                                ]);

                                                return $category->id;
                                            })
                                            ->editOptionForm([
                                                Forms\Components\TextInput::make('name')
                                                    ->label('Name')
                                                    ->required(),
                                            ])
                                            ->options(Category::where('for', 'post')->where('type', 'tag')->pluck('name', 'id')->toArray()),
                                        Forms\Components\Toggle::make('is_published')
                                            ->label('Published')
                                            ->default(true)
                                            ->required(),
                                        Forms\Components\DateTimePicker::make('published_at')->hidden(fn (Forms\Get $get) => in_array($get('type'), ['page', 'builder']))->label('Published At')->default(now()->format('Y-m-d H:i:s')),
                                    ]),
                                Forms\Components\Section::make('Images')
                                    ->description('Upload images for the Page')
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
                                        Forms\Components\SpatieMediaLibraryFileUpload::make('images')
                                            ->label('Images')
                                            ->collection('images')
                                            ->multiple()
                                            ->image()
                                            ->maxSize(2048)
                                            ->maxWidth(1920),
                                    ]),

                            ])
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 2,
                                'lg' => 4,
                            ]),
                    ]),

            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Title')
                ->description('Enter details of the Page')
                ->schema([
                    TextEntry::make('title')
                        ->hiddenLabel()
                        ->size(TextEntrySize::Large),
                    ImageEntry::make('feature_image')
                        ->hiddenLabel()
                        ->default(fn ($record) => $record->getFirstMediaUrl('feature_image')),
                    MarkdownEntry::make('body')
                        ->markdown()
                        ->hiddenLabel(),
                ]),
            Grid::make([
                'sm' => 1,
                'md' => 2,
                'lg' => 4,
            ])->schema([
                Section::make('Metadata')
                    ->description('Enter metadata of the Page')
                    ->schema([
                        TextEntry::make('author.name')
                            ->label('Author')
                            ->default(fn (Post $post) => $post->author?->name),
                        TextEntry::make('type')
                            ->label('Type')
                            ->state(function (Post $post) {
                                return FilamentCMSTypes::getOptions()->where('key', $post->type)->first()?->label;
                            })
                            ->color(function (Post $post) {
                                return FilamentCMSTypes::getOptions()->where('key', $post->type)->first()?->color;
                            })
                            ->icon(function (Post $post) {
                                return FilamentCMSTypes::getOptions()->where('key', $post->type)->first()?->icon;
                            })
                            ->badge(),
                    ])->columnSpan(2),
                Section::make('SEO')
                    ->description('Enter SEO details')
                    ->schema([
                        TextEntry::make('short_description')
                            ->label('Short description'),
                        TextEntry::make('keywords')
                            ->label('Keywords'),
                    ])->columnSpan(2),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        $importActions = [];

        $table = $table
            ->headerActions($importActions)
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->description(fn (Post $post) => Str::of($post->short_description)->limit(50))
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->description(fn (Post $post) => Str::of($post->short_description)->limit(50))
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_published')
                    ->toggleable()
                    ->sortable()
                    ->label('Published')
                    ->onColor('success'),
                Tables\Columns\TextColumn::make('published_at')
                    ->toggleable()
                    ->label('Published At')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('type')
                    ->form([
                        Forms\Components\Select::make('type')
                            ->options(FilamentCMSTypes::getOptions()->pluck('label', 'key')->toArray())
                            ->label('Type')
                            ->searchable(),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['type'],
                                fn (Builder $query, $type): Builder => $query->where('type', '>=', $type),
                            );
                    }),
                Tables\Filters\Filter::make('author_id')
                    ->label('Author')
                    ->form([
                        Forms\Components\Select::make('author_id')
                            ->label('Author')
                            ->options(User::all()->pluck('name', 'id')->toArray())
                            ->searchable(),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['author_id'],
                                fn (Builder $query, $id): Builder => $query->where('author_id', $id),
                            );
                    }),
                Tables\Filters\Filter::make('published_at')
                    ->form([
                        Forms\Components\DatePicker::make('published_at')
                            ->label('Published At'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['published_at'],
                                fn (Builder $query, $publishedAt): Builder => $query->whereDate('published_at', $publishedAt),
                            );
                    }),
                Tables\Filters\Filter::make('is_published')
                    ->form([
                        Forms\Components\Toggle::make('is_published')
                            ->label('Published'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['is_published'],
                                fn (Builder $query, $isPublished): Builder => $query->where('is_published', (bool) $isPublished),
                            );
                    }),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('preview-button')
                    ->iconButton()
                    ->tooltip('Preview')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->action(fn (Post $record) => redirect()->to('/preview/'.$record->slug)),
                Tables\Actions\Action::make('editor-button')
                    ->iconButton()
                    ->tooltip('Edit in Editor')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->color('primary')
                    ->action(fn (Post $record) => redirect()->to('/admin/editor/'.$record->slug)),
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->tooltip('Edit Metadata'),
                Tables\Actions\DeleteAction::make()
                    ->before(fn (Post $record) => Event::dispatch(new PostDeleted($record->toArray())))
                    ->iconButton()
                    ->tooltip('Delete'),
                Tables\Actions\ForceDeleteAction::make()
                    ->before(fn (Post $record) => Event::dispatch(new PostDeleted($record->toArray())))
                    ->iconButton()
                    ->tooltip('Force delete'),
                Tables\Actions\RestoreAction::make()
                    ->iconButton()
                    ->tooltip('Restore'),
            ])
            ->defaultSort('created_at', 'desc')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\BulkAction::make('category')
                        ->label('Category')
                        ->icon('heroicon-o-rectangle-stack')
                        ->form([
                            Forms\Components\Select::make('categories')
                                ->label('Categories')
                                ->searchable()
                                ->multiple()
                                ->options(Category::query()->where('for', 'post')->where('type', 'category')->pluck('name', 'id')->toArray()),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->each(fn ($record) => $record->categories()->sync($data['categories']));

                            Notification::make()
                                ->title('Success')
                                ->body('Posts categories has been changed')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('publish')
                        ->requiresConfirmation()
                        ->label('Published')
                        ->icon('heroicon-o-check-circle')
                        ->action(function (Collection $records) {
                            $records->each(fn ($record) => $record->update(['is_published' => ! $record->is_published]));

                            Notification::make()
                                ->title('Posts Published')
                                ->body('The selected posts have been published.')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);

        return $table->recordUrl(fn (Post $record): string => Pages\EditPage::getUrl([$record->id]));
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        // Filter to show only rows with 'type' = 'post'
        return parent::getEloquentQuery()->where('type', 'page');
    }
}
