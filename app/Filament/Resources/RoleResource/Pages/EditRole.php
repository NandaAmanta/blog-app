<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Const\RoleConst;
use App\Filament\Resources\RoleResource;
use Filament\Actions\Action;
use Filament\Actions;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);
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
            // Sync Relation Permissions
        }
        $record->permissions()->sync($permissionIds);
        return $record;
    }

    public function form(Form $form): Form
    {
        $data = $this->getRecord()->attributesToArray();
        $permissionCheckBox = Permission::all()
            ->map(function ($permission) {
                $exploded = explode('.', $permission->name);
                $module = $exploded[1];
                $action = $exploded[0];
                return [
                    'action' => $action,
                    'module' => $module
                ];
            })
            ->groupBy('module')
            ->map(function ($permissions) use ($data) {
                $module = $permissions[0]['module'] ?? '-';
                $options = [];
                foreach ($permissions as $action) {
                    $options[$action['action']] = $action['action'] . ' ' . $action['module'];
                }
                return CheckboxList::make($module)
                    ->options($options)
                    ->disabled($data['name'] == RoleConst::ADMIN);
            });

        return $form
            ->schema([
                TextInput::make('name')->label('Role')->columnSpanFull()->disabled($data['name'] == RoleConst::ADMIN),
                ...$permissionCheckBox
            ]);
    }

    protected function fillForm(): void
    {
        $data = $this->getRecord()->attributesToArray();
        $activePermissionCheckBox = Permission::query()
            ->whereHas('roles', function ($roleQuery) use ($data) {
                return $roleQuery->where('name', $data['name']);
            })
            ->get()
            ->map(function ($permission) {
                $exploded = explode('.', $permission->name);
                $module = $exploded[1];
                $action = $exploded[0];
                return [
                    'action' => $action,
                    'module' => $module
                ];
            })
            ->groupBy('module')
            ->map(function ($permissions) {
                $action = [];
                foreach ($permissions as $permission) {
                    $action[] = $permission['action'];
                }
                return $action;
            })->toArray();

        $this->fillFormWithDataAndCallHooks([
            ...$activePermissionCheckBox,
            ...$data
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
