<?php

namespace App\Livewire\Permissions;

use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class PermissionEdit extends Component
{
        public $permission;
        public $name;
        public $selectedRoles = [];

         public function mount(Permission $permission)
    {
        $this->permission = $permission;
        $this->name = $permission->name;
        $this->allPermissions = Permission::pluck('name')->toArray();
        $this->selectedPermissions = $permission->permissions()->pluck('name')->toArray();
    }


    protected function rules()
    {        return [
            'name' => ['required','string', Rule::unique('permissions', 'name')->ignore($this->permission->id)],
        ];
    }

    public function updatePermission()
    {
        abort_unless(auth()->user()->can('edit_permissions'), 403);
        $this->validate();

        $this->permission->update(['name' => $this->name]);

        return redirect()->route('permissions.index');
    }


    public function render()
    {
        return view('livewire.permissions.permission-edit');
    }
}
