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
                        Forms\Components\Select::make('type')
                            ->label(trans('filament-cms::messages.content.posts.sections.type.columns.type'))
                            ->live()
                            ->options(
                                FilamentCMSTypes::getOptions()
                                    ->whereIn('key', config('filament-cms.types'))
                                    ->pluck('label', 'key')->toArray()
                            )
                            ->default(config('filament-cms.types')[0])
                            ->columnSpanFull()
                            ->required()
                            ->selectablePlaceholder(false),
                        Forms\Components\Grid::make()
                            ->schema(
                                [
                                    Forms\Components\Section::make(trans('filament-cms::messages.content.posts.sections.post.title'))
                                        ->description(trans('filament-cms::messages.content.posts.sections.post.description'))
                                        ->schema([
                                            Forms\Components\TextInput::make('title')
                                                ->label(trans('filament-cms::messages.content.posts.sections.post.columns.title'))
                                                ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {

                                                    $titleSlug = Str::of($get('title'))->replace(' ', '-')->lower()->toString();

                                                    if ($get('type') === 'page') {
                                                        $set('slug', $titleSlug);
                                                    }

                                                    if ($get('type') === 'post') {
                                                        $set('slug', 'blog/'.$titleSlug);
                                                    }
                                                })
                                                ->lazy()
                                                ->required(),
                                            Forms\Components\TextInput::make('slug')
                                                ->label(trans('filament-cms::messages.content.posts.sections.post.columns.slug'))
                                                ->required()
                                                ->maxLength(255),
                                            Forms\Components\MarkdownEditor::make('body')
                                                ->label(trans('filament-cms::messages.content.posts.sections.post.columns.body'))
                                                ->toolbarButtons(config('filament-cms.editor.options'))
                                                ->columnSpanFull(),
                                        ])
                                        ->columns(2),
                                    Forms\Components\Section::make(trans('filament-cms::messages.content.posts.sections.seo.title'))
                                        ->description(trans('filament-cms::messages.content.posts.sections.seo.description'))
                                        ->schema([
                                            Forms\Components\TextInput::make('short_description')->label(trans('filament-cms::messages.content.posts.sections.seo.columns.short_description')),
                                            Forms\Components\Textarea::make('keywords')->autosize()->label(trans('filament-cms::messages.content.posts.sections.seo.columns.keywords')),
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
                                Forms\Components\Section::make(trans('filament-cms::messages.content.posts.sections.meta.title'))
                                    ->description(trans('filament-cms::messages.content.posts.sections.status.description'))
                                    ->schema([
                                        Forms\Components\Select::make('author_id')
                                            ->label(trans('filament-cms::messages.content.posts.sections.author.columns.author'))
                                            ->options(User::all()->pluck('name', 'id')->toArray())
                                            ->default(
                                                auth()->id()
                                            )
                                            ->selectablePlaceholder(false)
                                            ->searchable(),
                                        Forms\Components\Select::make('categories')
                                            ->hidden(fn (Forms\Get $get) => in_array($get('type'), ['page', 'builder']))
                                            ->relationship('categories', 'name')
                                            ->label(trans('filament-cms::messages.content.posts.sections.status.columns.categories'))
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
                                            ->label(trans('filament-cms::messages.content.posts.sections.status.columns.tags'))
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
                                            ->label(trans('filament-cms::messages.content.posts.sections.status.columns.is_published'))
                                            ->default(true)
                                            ->required(),
                                        Forms\Components\DateTimePicker::make('published_at')->hidden(fn (Forms\Get $get) => in_array($get('type'), ['page', 'builder']))->label(trans('filament-cms::messages.content.posts.sections.status.columns.published_at'))->default(now()->format('Y-m-d H:i:s')),
                                    ]),
                                Forms\Components\Section::make(trans('filament-cms::messages.content.posts.sections.images.title'))
                                    ->description(trans('filament-cms::messages.content.posts.sections.images.description'))
                                    ->schema([
                                        Forms\Components\SpatieMediaLibraryFileUpload::make('feature_image')
                                            ->label(trans('filament-cms::messages.content.posts.sections.images.columns.feature_image'))
                                            ->collection('feature_image')
                                            ->image()
                                            ->maxFiles(1)
                                            ->maxSize(2048)
                                            ->maxWidth(1920),
                                        Forms\Components\SpatieMediaLibraryFileUpload::make('cover_image')
                                            ->label(trans('filament-cms::messages.content.posts.sections.images.columns.cover_image'))
                                            ->collection('cover_image')
                                            ->image()
                                            ->maxFiles(1)
                                            ->maxSize(2048)
                                            ->maxWidth(1920),
                                        Forms\Components\SpatieMediaLibraryFileUpload::make('images')
                                            ->label(trans('filament-cms::messages.content.posts.sections.images.columns.images'))
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
            Section::make(trans('filament-cms::messages.content.posts.sections.post.title'))
                ->description(trans('filament-cms::messages.content.posts.sections.post.description'))
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
                Section::make(trans('filament-cms::messages.content.posts.sections.status.title'))
                    ->description(trans('filament-cms::messages.content.posts.sections.status.description'))
                    ->schema([
                        TextEntry::make('author.name')
                            ->label(trans('filament-cms::messages.content.posts.sections.author.columns.author'))
                            ->default(fn (Post $post) => $post->author?->name),
                        TextEntry::make('type')
                            ->label(trans('filament-cms::messages.content.posts.sections.status.columns.type'))
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
                Section::make(trans('filament-cms::messages.content.posts.sections.seo.title'))
                    ->description(trans('filament-cms::messages.content.posts.sections.seo.description'))
                    ->schema([
                        TextEntry::make('short_description')
                            ->label(trans('filament-cms::messages.content.posts.sections.seo.columns.short_description')),
                        TextEntry::make('keywords')
                            ->label(trans('filament-cms::messages.content.posts.sections.seo.columns.keywords')),
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
                    ->label(trans('filament-cms::messages.content.posts.sections.post.columns.title'))
                    ->description(fn (Post $post) => Str::of($post->short_description)->limit(50))
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->sortable()
                    ->label(trans('filament-cms::messages.content.posts.sections.status.columns.type'))
                    ->toggleable()
                    ->state(function (Post $post) {
                        return FilamentCMSTypes::getOptions()->where('key', $post->type)->first()?->label;
                    })
                    ->color(function (Post $post) {
                        return FilamentCMSTypes::getOptions()->where('key', $post->type)->first()?->color;
                    })
                    ->icon(function (Post $post) {
                        return FilamentCMSTypes::getOptions()->where('key', $post->type)->first()?->icon;
                    })
                    ->badge()
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_published')
                    ->toggleable()
                    ->sortable()
                    ->label(trans('filament-cms::messages.content.posts.sections.status.columns.is_published'))
                    ->onColor('success'),
                Tables\Columns\TextColumn::make('published_at')
                    ->toggleable()
                    ->label(trans('filament-cms::messages.content.posts.sections.status.columns.published_at'))
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
                Tables\Actions\ViewAction::make()
                    ->iconButton()
                    ->tooltip(__('filament-actions::view.single.label')),
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->tooltip(__('filament-actions::edit.single.label')),
                Tables\Actions\DeleteAction::make()
                    ->before(fn (Post $record) => Event::dispatch(new PostDeleted($record->toArray())))
                    ->iconButton()
                    ->tooltip(__('filament-actions::delete.single.label')),
                Tables\Actions\ForceDeleteAction::make()
                    ->before(fn (Post $record) => Event::dispatch(new PostDeleted($record->toArray())))
                    ->iconButton()
                    ->tooltip(__('filament-actions::force-delete.single.label')),
                Tables\Actions\RestoreAction::make()
                    ->iconButton()
                    ->tooltip(__('filament-actions::restore.single.label')),
            ])
            ->defaultSort('created_at', 'desc')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\BulkAction::make('category')
                        ->label(trans('filament-cms::messages.content.posts.sections.status.columns.categories'))
                        ->icon('heroicon-o-rectangle-stack')
                        ->form([
                            Forms\Components\Select::make('categories')
                                ->label(trans('filament-cms::messages.content.posts.sections.status.columns.categories'))
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
                        ->label(trans('filament-cms::messages.content.posts.sections.status.columns.is_published'))
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

        return $table->recordUrl(fn (Post $record): string => Pages\ViewPage::getUrl([$record->id]));
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
            'view' => Pages\ViewPage::route('/{record}'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        // Filter to show only rows with 'type' = 'post'
        return parent::getEloquentQuery()->where('type', 'page');
    }
}
