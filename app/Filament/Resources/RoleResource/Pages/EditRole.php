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
use Spatie\Permission\Models\Permission;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function getSaveFormAction(): Action
    {
        $data = $this->getRecord()->attributesToArray();
        return Action::make('save')
            ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
            ->submit('save')
            ->action(function (): void {
            })
            ->keyBindings(['mod+s']);
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
