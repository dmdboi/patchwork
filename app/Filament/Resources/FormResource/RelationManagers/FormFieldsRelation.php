<?php

namespace App\Filament\Resources\FormResource\RelationManagers;

use App\Services\FilamentCMSFormBuilder;
use App\Services\FilamentCMSFormFields;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FormFieldsRelation extends RelationManager
{
    protected static string $relationship = 'fields';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return 'Forms';
    }

    public static function getLabel(): ?string
    {
        return 'Forms';
    }

    public static function getModelLabel(): ?string
    {
        return 'Form';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Forms';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make()
                    ->schema([
                        Forms\Components\Tabs\Tab::make('General')
                            ->icon('heroicon-s-information-circle')
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->label('Type')
                                    ->searchable()
                                    ->options(FilamentCMSFormFields::getOptions()->pluck('label', 'name')->toArray())
                                    ->default('text'),
                                Forms\Components\TextInput::make('name')
                                    ->label('Name')
                                    ->live()
                                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $state) {
                                        if (str($state)->contains('email')) {
                                            $set('type', 'email');
                                        }
                                        if (str($state)->contains('phone')) {
                                            $set('type', 'tel');
                                        }
                                        if (str($state)->contains(['is_', 'has_'])) {
                                            $set('type', 'toggle');
                                        }
                                        if (str($state)->contains(['at', 'date'])) {
                                            $set('type', 'date');
                                        }
                                        if (str($state)->contains('password')) {
                                            $set('type', 'password');
                                        }
                                        if (str($state)->contains(['description', 'message'])) {
                                            $set('type', 'textarea');
                                        }
                                        if (str($state)->contains(['body', 'about'])) {
                                            $set('type', 'rich');
                                        }
                                        if (str($state)->contains('price')) {
                                            $set('type', 'number');
                                        }

                                    })
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('group')
                                    ->label('Group')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('default')
                                    ->label('Default'),
                            ])->columns(2),
                        Forms\Components\Tabs\Tab::make('Relation')
                            ->icon('heroicon-s-squares-plus')
                            ->schema([
                                Forms\Components\Toggle::make('is_relation')
                                    ->label('Is Relation')
                                    ->columnSpanFull()
                                    ->live(),
                                Forms\Components\TextInput::make('relation_name')
                                    ->label('Relation Name')
                                    ->hidden(fn (Forms\Get $get) => ! $get('is_relation'))
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('relation_column')
                                    ->label('Relation Column')
                                    ->hidden(fn (Forms\Get $get) => ! $get('is_relation'))
                                    ->maxLength(255),
                            ])->columns(2),
                        Forms\Components\Tabs\Tab::make('Options')
                            ->icon('heroicon-s-rectangle-group')
                            ->schema([
                                Forms\Components\Select::make('sub_form')
                                    ->label('Sub Form')
                                    ->searchable()
                                    ->options(\App\Models\Form::query()->where('id', '!=', $this->getOwnerRecord()->id)->pluck('key', 'id')->toArray()),
                                Forms\Components\Toggle::make('is_multi')
                                    ->label('Is Multi'),
                                Forms\Components\Toggle::make('has_options')
                                    ->label('Has Options')
                                    ->live(),
                                Forms\Components\Repeater::make('options')
                                    ->label('Options')
                                    ->schema([
                                        Forms\Components\TextInput::make('label')->label('Lalue'),
                                        Forms\Components\TextInput::make('value')->label('Value'),
                                    ])
                                    ->hidden(fn (Forms\Get $get) => ! $get('has_options')),
                            ]),
                        Forms\Components\Tabs\Tab::make('Validation')
                            ->icon('heroicon-s-variable')
                            ->schema([
                                Forms\Components\Toggle::make('is_required')
                                    ->label('Required')
                                    ->live(),
                                TextInput::make('required_message')
                                    ->label('Required Message')
                                    ->hidden(fn (Forms\Get $get) => ! $get('is_required')),
                                Forms\Components\Toggle::make('has_validation')
                                    ->label('Has Validation')
                                    ->live(),
                                Forms\Components\Repeater::make('validation')
                                    ->label('Validation')
                                    ->schema([
                                        Forms\Components\TextInput::make('rule')->label('Rule'),
                                        Forms\Components\TextInput::make('rule')->label('Message'),
                                    ])
                                    ->hidden(fn (Forms\Get $get) => ! $get('has_validation')),
                            ]),
                    ]),
            ])->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-s-plus-circle')
                    ->after(function (array $data, $record) {
                        $record->name = Str::of($record->name)->replace(' ', '_')->lower()->toString();
                        $record->save();
                    }),
                Tables\Actions\Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-s-eye')
                    ->color('info')
                    ->form(function () {
                        return FilamentCMSFormBuilder::make($this->getOwnerRecord()->key)->build();
                    })->action(function (array $data) {
                        FilamentCMSFormBuilder::make($this->getOwnerRecord()->key)->send($data);
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->after(function (array $data, $record) {
                    $record->name = Str::of($record->name)->replace(' ', '_')->lower()->toString();
                    $record->save();
                }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->icon(fn ($record) => FilamentCMSFormFields::getOptions()->where('name', $record->type)->first()->icon)
                    ->color(fn ($record) => FilamentCMSFormFields::getOptions()->where('name', $record->type)->first()->color)
                    ->state(fn ($record) => FilamentCMSFormFields::getOptions()->where('name', $record->type)->first()->label)
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_required')
                    ->label('Required')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Tables\Grouping\Group::make('group'),
            ])
            ->defaultSort('created_at')
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->reorderable('order');
    }
}
