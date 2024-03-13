<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $record = new Role(['name' => $data['name']]);
        $record->save();

        // Sync Relation Permissions
        $keys = array_keys($data);
        $permissionIds = [];
        foreach ($keys as $key) {
            if ($key == 'name') {
                continue;
            }
            $module = $key;
            $actions = collect($data[$module])
                ->map(fn ($action) => $action . '.' . $module)
                ->toArray();
            $permissions = Permission::select('id')
                ->whereIn('name', $actions)
                ->get()
                ->map(fn ($permission) => $permission->id)
                ->toArray();
            $permissionIds = array_merge($permissionIds, $permissions);
        }
        $record->permissions()->sync($permissionIds);
        return $record;
    }
}
