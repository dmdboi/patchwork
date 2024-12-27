<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FormResource\Pages;
use App\Filament\Resources\FormResource\RelationManagers;
use App\Models\Form as FormModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class FormResource extends Resource
{
    protected static ?string $model = FormModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    public static function canViewAny(): bool
    {
        return auth()->user()->hasAccess('view', 'forms');
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Forms';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Forms';
    }

    public static function getLabel(): ?string
    {
        return 'Form';
    }

    public static function getNavigationLabel(): string
    {
        return 'Forms';
    }

    public static function form(Form $form): Form
    {
        $formSchema = [
            Forms\Components\Select::make('type')
                ->label('Type')
                ->searchable()
                ->options([
                    'page' => 'Page',
                    'modal' => 'Modal',
                    'slideover' => 'Slideover',
                ])
                ->default('page'),
            Forms\Components\Select::make('method')
                ->label('Method')
                ->searchable()
                ->options([
                    'POST' => 'POST',
                    'GET' => 'GET',
                    'PUT' => 'PUT',
                    'DELETE' => 'DELETE',
                    'PATCH' => 'PATCH',
                ])
                ->default('POST'),
            Forms\Components\TextInput::make('title')
                ->label('Title'),
            Forms\Components\TextInput::make('key')
                ->label('Key')
                ->default(Str::random(6))
                ->unique(ignoreRecord: true)
                ->required()
                ->maxLength(255),
            Forms\Components\Textarea::make('description')
                ->label('Description')
                ->columnSpanFull(),
            Forms\Components\TextInput::make('endpoint')
                ->label('Endpoint')
                ->columnSpanFull()
                ->maxLength(255)
                ->default('/'),
            Forms\Components\Toggle::make('is_active')
                ->label('Active'),
        ];

        return $form
            ->schema(fn ($record) => $record ? [
                Forms\Components\Section::make('Form Details')
                    ->collapsible()
                    ->collapsed(fn ($record) => $record)
                    ->schema($formSchema),
            ] : $formSchema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('key')
                    ->label('Key')
                    ->searchable(),
                Tables\Columns\TextColumn::make('endpoint')
                    ->label('Endpoint')
                    ->searchable(),
                Tables\Columns\TextColumn::make('method')
                    ->label('Method')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\FormFieldsRelation::class,
            RelationManagers\FormRequestsRelation::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListForms::route('/'),
            'edit' => Pages\EditForm::route('/{record}/edit'),
        ];
    }
}
