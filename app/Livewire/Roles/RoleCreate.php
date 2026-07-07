<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;

class RoleCreate extends Component
{
    public string $name = '';
    public array $allPermissions = [];
    public array $selectedPermissions = [];

    public function mount()
    {

        $this->allPermissions = Permission::orderBy('id')->get(['name'])->pluck('name')->toArray();
        
    }   

    protected function rules()
    {
        return [
            'name' => 'required|string|unique:roles,name',
            'selectedPermissions' => 'array',
            'selectedPermissions.*' => 'string|exists:permissions,name,guard_name,web',
        ];
    }


    
        public function createRole()
        {
            $this->authorize('create_roles');
            
            $this->validate();

            if ($this->name === 'Superadmin' || $this->name === 'superadmin' || $this->name === 'SUPERADMIN' || $this->name === 'Super admin' || $this->name === 'super admin' || $this->name === 'SUPER ADMIN' ) 
                {
                    session()->flash('error', 'You cannot create Superadmin!');
                    return;
                }
                if ($this->name === 'Admin' || $this->name === 'admin' || $this->name === 'ADMIN') 
                {
                    session()->flash('error', 'You cannot create Admin!');
                    return;
                }
            $role = Role::create([
                'name' => $this->name,
                'guard_name' => 'web',
                'created_by_uuid' => auth()->id(),
            ]);

            $role->syncPermissions($this->selectedPermissions);


         
            $this->reset(['name', 'selectedPermissions']);

            return redirect()->route('roles.index');
        }


    public function render()
    {
        return view('livewire.roles.role-create');
    }
}
