<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlocksResource\Pages;
use App\Filament\Resources\BlocksResource\RelationManagers;
use App\Models\BladeView;
use App\Models\Blocks;
use Dotswan\FilamentCodeEditor\Fields\CodeEditor;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BlocksResource extends Resource
{
    protected static ?string $model = BladeView::class;

    protected static ?string $navigationIcon = 'heroicon-o-code-bracket-square';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('key')
                    ->label('Key')
                    ->columnSpanFull()
                    ->required(),
                CodeEditor::make('content')
                    ->label('Content')
                    ->columnSpanFull()
                    ->minHeight(420)
                    ->darkModeTheme('gruvbox-dark')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at'),
                TextColumn::make('updated_at'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlocks::route('/'),
            'create' => Pages\CreateBlocks::route('/create'),
            'edit' => Pages\EditBlocks::route('/{record}/edit'),
        ];
    }
}
