<?php

namespace App\Filament\Resources\FormResource\RelationManagers;

use App\Models\FormRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class FormRequestsRelation extends RelationManager
{
    protected static string $relationship = 'requests';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return 'Form Requests';
    }

    public static function getLabel(): ?string
    {
        return 'Form Requests';
    }

    public static function getModelLabel(): ?string
    {
        return 'Form Request';
    }

    public static function getPluralLabel(): ?string
    {
        return 'Form Requests';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->searchable()
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->columnSpanFull()
                    ->default('pending'),
            ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {

        return $infolist->schema([
            TextEntry::make('description')
                ->label('Description')
                ->columnSpanFull(),
            TextEntry::make('time')
                ->label('Time'),
            TextEntry::make('date')
                ->label('Date'),
            KeyValueEntry::make('payload')
                ->label('Payload')
                ->columnSpanFull()
                ->schema(function (FormRequest $record) {
                    $getEntryText = [];
                    foreach ($record->payload as $key => $value) {
                        $field = $record->form->fields->where('key', $key)->first();
                        $getEntryText[] = TextEntry::make($key)
                            ->label($field->label ?? str($key)->title())
                            ->default($value)
                            ->columnSpanFull();
                    }

                    return $getEntryText;
                })
                ->columns(2),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->state(fn ($record) => match ($record->status) {
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        default => $record->status,
                    })
                    ->icon(fn ($record) => match ($record->status) {
                        'pending' => 'heroicon-s-rectangle-stack',
                        'processing' => 'heroicon-s-arrow-path',
                        'completed' => 'heroicon-s-check-circle',
                        'cancelled' => 'heroicon-s-x-circle',
                        default => 'heroicon-s-x-circle',
                    })
                    ->color(fn ($record) => match ($record->status) {
                        'pending' => 'info',
                        'processing' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'secondary',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description'),
                Tables\Columns\TextColumn::make('date')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('time')
                    ->label('Time'),
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
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->searchable()
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->columnSpanFull(),
            ])
            ->defaultSort('created_at', 'desc')
            ->groups([
                Tables\Grouping\Group::make('status'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
