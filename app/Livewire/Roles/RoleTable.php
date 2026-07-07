<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleTable extends Component
{
    public $roles;
    public $selectedPermissions = [];
    public $allPermissions = [];

//  public function mount()
// {
//     // Superadmin can see all roles
//     if (auth()->user()->hasRole('Superadmin')) {

//         $this->roles = Role::all();

//     } else {

//     $this->roles = Role::where('name', '!=', 'Superadmin')->get();

//     }

//     $this->allPermissions = Permission::all()
//         ->pluck('name')
//         ->toArray();
// }

public function mount()
{
    if (auth()->user()->hasRole('Superadmin')) {

       $this->roles = Role::whereNotIn('name', ['Superadmin'])->get();

    } else {

        $this->roles = Role::where('name', '!=', 'Superadmin')->get();
    }

    $this->allPermissions = Permission::all()
        ->pluck('name')
        ->toArray();
}

    // ✅ AUTO REFRESH DATA (NO manual refresh needed)
    public function getRolesProperty()
    {
        return Role::query()
            ->with('permissions')
            ->where('name', '!=', 'Superadmin')
            ->latest()
            ->get();
    }


public function deleteRole($roleId)
{
    $role = Role::find($roleId);

    if (!$role) {

        $this->dispatch(
            'toast',
            message: 'Role not found!',
            type: 'error'
        );

        return;
    }

    $user = auth()->user();

       if ($user->id === auth()->id()) {
            session()->flash('error', 'You cannot delete yourself.');
            return;
        }

        

    // Only Superadmin can delete
    if ($user->hasRole('Superadmin')) {

        $role->delete();

        $this->roles = $this->roles->filter(
            fn($r) => $r->id !== $roleId
        );
            session()->flash('success', 'Role deleted successfully!');
            return;
    } else {

         session()->flash('error', 'You are not authorized to delete this role!');
        return;
    }
}

    // public function deleteRole($roleId)
    // {
    //     $role = Role::find($roleId);

    // if (auth()->user()->hasRole('Superadmin')) {
    
    //         if ($role) {
    //             $role->delete();
    //             $this->roles = $this->roles->filter(fn($r) => $r->id !== $roleId);
    //         }

    //     }else{
    //                     $this->dispatch('toast', [
    //                 'message' => 'You are not authorized to delete this role!',
    //                 'type' => 'error'
    //             ]);
    //     }
 
    // }
    
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
    
    public function createRole()
    {
        $this->authorize('create_roles');
        return redirect()->route('roles.create');
    }

    public function editRole($roleId)
    {
        $this->authorize('edit_roles');
       return redirect()->route('roles.edit', $roleId);
    }

    public function render()
    {
        return view('livewire.roles.role-table', ['roles' =>  $this->roles]);
    }


 
    


}
