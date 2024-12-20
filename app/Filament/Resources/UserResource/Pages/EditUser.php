<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\EditRecord;
use Spatie\Permission\Models\Role;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('assignRole')
                ->label('Assign Role')
                ->form([
                    Select::make('role')
                        ->label('Role')
                        ->options(Role::query()->pluck('name', 'id'))
                        ->required(),
                ])
                ->action(function (array $data, User $record): void {
                    $role = Role::find($data['role']);
                    $record->syncRoles($role);
                }),

        ];
    }
}
