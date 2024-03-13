<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'User Management';

    public static function form(Form $form): Form
    {
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
            ->map(function ($permissions) {
                $module = $permissions[0]['module'] ?? '-';
                $options = [];
                foreach($permissions as $action){
                    $options[$action['action']] = $action['action'] .' '. $action['module']; 
                }
                return CheckboxList::make($module)
                    ->options($options);
            });

        return $form
            ->schema([
                TextInput::make('name')->label('Role')->columnSpanFull(),
                ...$permissionCheckBox
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                'role' => TextColumn::make('name'),
                'permission_count' => TextColumn::make('permission_count')->counts('permissions')->default('0'),
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
