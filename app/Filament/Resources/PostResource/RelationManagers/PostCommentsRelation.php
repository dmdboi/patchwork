<?php

namespace App\Filament\Resources\PostResource\RelationManagers;

use App\Models\Comment;
use App\Models\User;
use App\Services\FilamentCMSAuthors;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PostCommentsRelation extends RelationManager
{
    protected static string $relationship = 'comments';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return 'Comments';
    }

    public static function getLabel(): ?string
    {
        return 'Comments';
    }

    public static function getModelLabel(): ?string
    {
        return 'Comment';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Comments';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_type')
                    ->label('User Type')
                    ->options(count(FilamentCMSAuthors::getOptions()) ? FilamentCMSAuthors::getOptions()->pluck('name', 'model')->toArray() : [User::class => 'Users'])
                    ->afterStateUpdated(fn (Forms\Get $get, Forms\Set $set) => $set('user_id', null))
                    ->preload()
                    ->live()
                    ->searchable(),
                Forms\Components\Select::make('user_id')
                    ->label('User ID')
                    ->options(fn (Forms\Get $get) => $get('user_type') ? $get('user_type')::pluck('name', 'id')->toArray() : [])
                    ->searchable(),
                Forms\Components\Textarea::make('comment')
                    ->label('Comment')
                    ->required()
                    ->maxLength(255),
                Forms\Components\ToggleButtons::make('rate')
                    ->options([
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                    ])
                    ->icons([
                        '1' => 'heroicon-s-star',
                        '2' => 'heroicon-s-star',
                        '3' => 'heroicon-s-star',
                        '4' => 'heroicon-s-star',
                        '5' => 'heroicon-s-star',
                    ])
                    ->inline()
                    ->label('Rate')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User ID'),
                Tables\Columns\TextColumn::make('comment')
                    ->label('Comment')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rate')
                    ->label('Rate')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->description(fn (Comment $comment) => $comment->created_at->diffForHumans())
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->filters([
                Tables\Filters\Filter::make('user_id')
                    ->label('User ID')
                    ->form([
                        Forms\Components\Select::make('user_type')
                            ->label('User Type')
                            ->options(count(FilamentCMSAuthors::getOptions()) ? FilamentCMSAuthors::getOptions()->pluck('name', 'model')->toArray() : [User::class => 'Users'])
                            ->afterStateUpdated(fn (Forms\Get $get, Forms\Set $set) => $set('user_id', null))
                            ->live()
                            ->searchable(),
                        Forms\Components\Select::make('user_id')
                            ->label('User ID')
                            ->hidden(fn (Forms\Get $get) => ! $get('user_type'))
                            ->disabled(fn (Forms\Get $get) => ! $get('user_type'))
                            ->options(fn (Forms\Get $get) => $get('user_type') ? $get('user_type')::pluck('name', 'id')->toArray() : [])
                            ->searchable(),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['user_type'],
                                fn (Builder $query, $type): Builder => $query->where('user_type', $type),
                            )
                            ->when(
                                $data['user_id'],
                                fn (Builder $query, $id): Builder => $query->where('user_id', $id),
                            );
                    }),
            ]);
    }
}
