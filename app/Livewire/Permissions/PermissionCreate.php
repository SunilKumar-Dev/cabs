<?php

namespace App\Livewire\Permissions;

use Livewire\Component;
use Spatie\Permission\Models\Permission;

class PermissionCreate extends Component
{
    public string $name = '';

    protected function rules()
    {
        return [
            'name' => 'required|string|unique:permissions,name',
        ];
    }
    
    public function createPermission()
    {
        abort_unless(auth()->user()->can('create_permissions'), 403);

        $this->validate();

        Permission::create(['name' => $this->name]);
        $this->reset('name');

        return redirect()->route('permissions.index');
    }
    
    public function render()
    {
        return view('livewire.permissions.permission-create');
    }
}
