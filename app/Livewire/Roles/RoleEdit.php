<?php

namespace App\Livewire\Roles;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;


class RoleEdit extends Component
{
    public $name;
    public Role $role;
    public array $allPermissions = [];
    public array $selectedPermissions = [];

    public function mount(Role $role)
    {

        $this->role = $role;
        $this->roleId = $role->id;
        $this->name = $role->name;
        $this->allPermissions = Permission::orderBy('id')->pluck('name')->toArray();
        $this->selectedPermissions = $role->permissions()->orderBy('id')->pluck('name')->toArray();
       
    }

    protected function rules()
    {
        return [

            'role.name' => ['required','string', Rule::unique('roles', 'name')->ignore($this->role->id)],
            'selectedPermissions' => 'array',
            'selectedPermissions.*' => 'string|exists:permissions,name',
        ];
    }

    public function updateRole()
    {
        $this->authorize('update_roles');

        if (
            ($this->role->name === 'Admin' || $this->role->name === 'User')
            && auth()->user()->hasRole('Admin')
        ) {

            $this->addError(
                'name',
                'You are not allowed to update this role.'
            );

            return;
        }


        $this->validate();

        $this->role->update(['name' => $this->name]);
        $this->role->syncPermissions($this->selectedPermissions);

        // session()->flash('message', 'Role updated successfully.');

        return redirect()->route('roles.index');
    }

   
    public function render()
    {
        return view('livewire.roles.role-edit');
    }
}
