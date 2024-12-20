<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-badge';

    protected static ?string $navigationGroup = 'Access';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('name')
                    ->label('Name')
                    ->required(),
                Section::make('Permissions')
                    ->collapsible()
                    ->columns(3)
                    ->schema(function () {
                        // Fetch all permissions
                        $permissions = Permission::all();

                        // Group permissions by resource
                        $groupedPermissions = $permissions->groupBy(function ($permission) {
                            return Str::after($permission->name, ':'); // Extract resource name
                        });

                        // Generate a CheckboxList for each resource group
                        return $groupedPermissions->map(function ($group, $resource) use ($permissions) {
                            // Filter permissions for this resource

                            $filteredPermissions = $permissions->filter(function ($permission) use ($resource) {
                                return Str::after($permission->name, ':') === $resource;
                            });

                            return CheckboxList::make("permissions_{$resource}")
                                ->label(Str::title($resource))
                                ->options($filteredPermissions->pluck('name', 'id'))
                                ->relationship('permissions', 'name', function ($query) use ($resource) {
                                    // Filter the relationship by the resource name
                                    $query->where('name', 'like', "%:{$resource}");
                                })
                                ->bulkToggleable();
                        })->values()->toArray();
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('users_count')
                    ->badge()
                    ->icon('heroicon-s-user')
                    ->label('Users')
                    ->counts('users')
                    ->sortable(),
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
