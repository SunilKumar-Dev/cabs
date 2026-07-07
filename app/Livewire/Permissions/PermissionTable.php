<?php

namespace App\Livewire\Permissions;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class PermissionTable extends Component
{
     public $permissions;
     public $selectedPermissions = [];
    
    public function deletePermission($permissionId)
    {
         abort_unless(auth()->user()->can('delete_permissions'), 403);
        $permission = Permission::find($permissionId);
        if ($permission) {
            $permission->delete();
            $this->permissions = $this->permissions->filter(fn($p) => $p->id !== $permissionId);
        }
    }
    public function createPermission()
    {
         abort_unless(auth()->user()->can('create_permissions'), 403);
        return redirect()->route('permissions.create');
    }
    public function editPermission($permissionId)
    {
        abort_unless(auth()->user()->can('edit_permissions'), 403);
       return redirect()->route('permissions.edit', $permissionId);
    }
    public function toggleAllPermissions()
    {
        if (count($this->selectedPermissions) === count($this->allPermissions)) {
            // Unselect all
            $this->selectedPermissions = [];
        } else {
            // Select all
            $this->selectedPermissions = $this->allPermissions;
        }
    }

    public function togglePermission($permissionName)
    {
        if (in_array($permissionName, $this->selectedPermissions)) {
            // Unselect the permission
            $this->selectedPermissions = array_diff($this->selectedPermissions, [$permissionName]);
        } else {
            // Select the permission
            $this->selectedPermissions[] = $permissionName;
        }
    }

    public function toggleRolePermissions($roleId)
    {
        $role = Role::find($roleId);
        if (!$role) return;

        $rolePermissions = $role->permissions()->pluck('name')->toArray();

        if (count(array_intersect($this->selectedPermissions, $rolePermissions)) === count($rolePermissions)) {
            // Unselect all permissions of the role
            $this->selectedPermissions = array_diff($this->selectedPermissions, $rolePermissions);
        } else {
            // Select all permissions of the role
            $this->selectedPermissions = array_unique(array_merge($this->selectedPermissions, $rolePermissions));
        }
    }
    
    
    public function mount()
    {
      $this->permissions = Permission::orderBy('id')->get();
    }
    
    public function render()
    {
        return view('livewire.permissions.permission-table');
    }
}
