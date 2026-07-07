<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Services\RoleService;




new #[Title('Roles settings')] class extends Component
{
   use WithPagination;

   public $roleId;
    public $roleName;
    public $allPermissions;          // Collection of all permissions
    public $groupedPermissions = []; // group Permission
    public $rolePermissions = [];    // Selected permissions

    public $selectAllPermissions = false;   // Global Select All
    public $selectAllInGroup = [];          // Select All per group
    public $collapsedGroups = [];           // Collapsible groups


    public $showModal = false;

  
public function mount($role = null)
{
    $isSuperAdmin = auth()->user()->hasRole('Superadmin');

    /*
    |--------------------------------------------------------------------------
    | Load Permissions
    |--------------------------------------------------------------------------
    */

    $this->allPermissions = Permission::all();

    if (!$isSuperAdmin) {

        $this->allPermissions = $this->allPermissions->filter(function ($permission) {

            $name = $permission->name;

            if (Str::endsWith($name, '_permissions')) {
                return false;
            }

            if (
                Str::endsWith($name, ['_users', '_roles']) &&
                (
                    Str::startsWith($name, 'restore_') ||
                    Str::startsWith($name, 'forceDelete_')
                )
            ) {
                return false;
            }

            return true;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Group Permissions
    |--------------------------------------------------------------------------
    */

    $this->groupedPermissions = $this->allPermissions
        ->groupBy(function ($permission) {

            return ucfirst(
                Str::plural(
                    Str::afterLast($permission->name, '_')
                )
            );
        })
        ->toArray();

    /*
    |--------------------------------------------------------------------------
    | Edit Mode
    |--------------------------------------------------------------------------
    */

    if ($role) {

        $this->roleId = $role->id;

        $this->roleName = $role->name;

        $this->rolePermissions = $role->permissions
            ->pluck('name')
            ->toArray();
    }
}




     // ✅ AUTO REFRESH DATA (NO manual refresh needed)
    public function getRolesProperty()
    {
          return Role::query()
            ->with('permissions')
            ->where('name', '!=', 'Superadmin')
            ->get();
    }


    // Toggle global Select All
    public function toggleSelectAll()
    {
        if ($this->selectAllPermissions) {
            $this->rolePermissions = $this->allPermissions->pluck('name')->toArray();
            // Mark all group select all as true
            foreach ($this->allPermissions as $permission) {
                $suffix = Str::afterLast($permission->name, '_');
                $this->selectAllInGroup[$suffix] = true;
            }
        } else {
            $this->rolePermissions = [];
            $this->selectAllInGroup = [];
        }
    }



    public function toggleSelectGroup($groupName)
    {

        // Get all permissions of this group
        $groupPermissions = collect($this->allPermissions)
            ->filter(function ($permission) use ($groupName) {
                return ucfirst(Str::plural(Str::afterLast($permission->name, '_'))) === $groupName;
            })
            ->pluck('name')
            ->toArray();

        if (!empty($this->selectAllInGroup[$groupName])) {
            // ✅ Add all group permissions
            $this->rolePermissions = array_unique(array_merge(
                $this->rolePermissions,
                $groupPermissions
            ));
        } else {
            // ❌ Remove group permissions
            $this->rolePermissions = array_values(array_diff(
                $this->rolePermissions,
                $groupPermissions
            ));
        }
    }

    // Toggle collapse per group
    public function toggleCollapse($groupName)
    {
        $this->collapsedGroups[$groupName] = !($this->collapsedGroups[$groupName] ?? false);
    }


      // ✅ OPEN ADD MODAL
    public function addRole()
    {
        $this->resetValidation();
        $this->resetErrorBag();
        $this->reset(['roleId', 'roleName', 'rolePermissions']);
        $this->selectAllPermissions = false;
        $this->showModal = true;
    }

    // ✅ OPEN EDIT MODAL
    public function editRole($id)
    {
        $this->resetValidation();
        $this->resetErrorBag();
        
        $role = Role::findOrFail($id);

        $this->roleId = $role->id;
        $this->roleName = $role->name;
        $this->rolePermissions = $role->permissions->pluck('name')->toArray();

        $this->selectAllPermissions =
            count($this->rolePermissions) === $this->allPermissions->count();

        $this->showModal = true;
    }


    public function confirmDelete($roleId)
    {
        $role = Role::find($roleId);

         // Protected roles
        $protectedRoles = ['Superadmin', 'Admin', 'User'];

        if (!$role) {
            session()->flash('error', 'Role not found.');
            return;
        }

        // Superadmin check
        if (!auth()->user()->hasRole('Superadmin')) {

            // Protected roles update nahi kar sakta
            if (in_array($role->name, $protectedRoles)) {

                session()->flash('error', 'You are not authorized.');
                $this->showModal = false;
                return;
            }

            // Sirf apne created roles update kar sakta
            if ($role->created_by_uuid != auth()->id()) {

                session()->flash('error', 'You can only edit your own roles.');
                $this->showModal = false;
                return;
            }
        }else{
            // Protected roles update nahi kar sakta
            if (in_array($role->name, $protectedRoles)) {

                session()->flash('error', 'You are not authorized.');
                $this->showModal = false;
                return;
            }
        }

            // Update role
            $role->delete();

            session()->flash(
            'success',
            'Role deleted successfully!'
        );

    }
    

     // ✅ DELETE
    // public function confirmDelete($id)
    // {
    //     Role::findOrFail($id)->delete();
    //     session()->flash('success', 'Role deleted successfully');
    // }

    
  

// public function confirmDelete($roleId)
// {
//     $role = Role::find($roleId);

//     if (!$role) {

//          session()->flash('success', 'Role not found!');
//             return;

//     }

//     $user = auth()->user();

//     // Only Superadmin can delete
//     if ($user->hasRole('Superadmin')) {

//         $this->roles === 'Superadmin' ||  $this->roles === 'Admin' ? 
//         session()->flash('error', 'You cannot delete this role!') : 
//         $role->id === auth()->id() ? session()->flash('error', 'You cannot delete yourself!') :

//         $role->delete();
//         session()->flash('success', 'Role deleted successfully!');
//        return;

//        } else {

//          session()->flash('error', 'You are not authorized to delete this role!');
//         return;
//     }
// }


public function saveRole()
{
    $this->validate([
        'roleName' => [
            'required',
            'string',
            'max:255',
            Rule::unique('roles', 'name')->ignore($this->roleId),
        ],
        'rolePermissions' => 'required|array|min:1',
    ]);

    // Protected roles
    $protectedRoles = ['Superadmin', 'Admin', 'User'];

    /*
    |--------------------------------------------------------------------------
    | UPDATE ROLE
    |--------------------------------------------------------------------------
    */
    if ($this->roleId) {

        $role = Role::find($this->roleId);

        if (!$role) {
            session()->flash('error', 'Role not found.');
            return;
        }

        // Superadmin check
        if (!auth()->user()->hasRole('Superadmin')) {

            // Protected roles update nahi kar sakta
            if (in_array($role->name, $protectedRoles)) {

                session()->flash('error', 'You are not authorized.');
                $this->showModal = false;
                return;
            }

            // Sirf apne created roles update kar sakta
            if ($role->created_by_uuid != auth()->id()) {

                session()->flash('error', 'You can only edit your own roles.');
                $this->showModal = false;
                return;
            }
        }

        // Update role
        $role->update([
            'name' => $this->roleName,
        ]);

    } 
    
    /*
    |--------------------------------------------------------------------------
    | CREATE ROLE
    |--------------------------------------------------------------------------
    */
    else {

        // Superadmin ke alawa koi protected role create nahi kar sakta
        if (!auth()->user()->hasRole('Superadmin')) {

            if (in_array($this->roleName, $protectedRoles)) {

                session()->flash('error', 'You are not authorized to create this role.');
                $this->showModal = false;
                return;
            }
        }

        // Create role
        $role = Role::create([
            'name' => $this->roleName,
            'guard_name' => 'web',
            'created_by_uuid' => auth()->id(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SYNC PERMISSIONS (Create + Update dono me)
    |--------------------------------------------------------------------------
    */
    $role->syncPermissions($this->rolePermissions);

    /*
    |--------------------------------------------------------------------------
    | RESET FORM
    |--------------------------------------------------------------------------
    */
    $this->reset([
        'roleId',
        'roleName',
        'rolePermissions'
    ]);

    $this->selectAllPermissions = false;
    $this->showModal = false;

    session()->flash('success', 'Role saved successfully.');
}


    // public function saveRole()
    // {

    //     $role = Role::find($this->roleId);

    //         // Role exist nahi karta
    //         if (!$role) {
    //             session()->flash('error', 'Role not found.');
    //             return;
    //         }

    //         // Superadmin check
    //         if (!auth()->user()->hasRole('Superadmin')) {

    //             // Admin protected roles edit nahi kar sakta
    //             if (in_array($role->name, ['Superadmin', 'Admin', 'User'])) {

    //                 session()->flash('error', 'You are not authorized.');
    //                 $this->showModal = false;
    //                 return;
    //             }

    //             // Admin sirf apne created roles edit kare
    //             if ($role->created_by_uuid != auth()->id()) {

    //                 session()->flash('error', 'You can only edit your own roles.');
    //                 $this->showModal = false;
    //                 return;
    //             }
    //         }



  

    //     $this->validate([
    //         'roleName' => [
    //             'required',
    //             'string',
    //             'max:255',
    //             Rule::unique('roles', 'name')->ignore($this->roleId),
    //         ],
    //         'rolePermissions' => 'required|array|min:1',
    //     ]);

    //     $isUpdate = !empty($this->roleId);

    //     if(!empty($role->name)){
    //      // Prevent deleting protected roles
    //         if (in_array($role->name, ['Superadmin', 'Admin', 'User'])) {

    //             session()->flash(
    //                 'error',
    //                 'You cannot update this role!'
    //             );

    //             return;
    //         }
    //     }



    //     $role = Role::updateOrCreate(
    //         ['id' => $this->roleId],
    //         [
    //             'name' => trim($this->roleName),
    //         ]
    //     );

    //     $role->syncPermissions($this->rolePermissions);

    //     // Reset form
    //     $this->reset(['roleId', 'roleName', 'rolePermissions']);

    //     $this->selectAllPermissions = false;
    //     $this->showModal = false;

    //     session()->flash(
    //         'success',
    //         $isUpdate
    //             ? 'Role updated successfully!'
    //             : 'Role saved successfully!'
    //     );
    // }
    

    

    public function updatedRolePermissions()
    {
        foreach ($this->groupedPermissions as $groupName => $permissions) {

            $groupPermissionNames = collect($permissions)->pluck('name')->toArray();

            $this->selectAllInGroup[$groupName] =
                empty(array_diff($groupPermissionNames, $this->rolePermissions));
        }
    }


}
?>
    

<div>

<section class="w-full">
        
        <!-- Header Section -->

        <div class="relative mb-6 w-full">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-star lg:justify-between">
                <!-- LEFT -->
                <div class="min-w-0">
                    <flux:heading size="xl" level="1">
                       Role Management
                    </flux:heading>

                    <flux:subheading size="lg" class="mt-1">
                       Manage roles and permissions
                    </flux:subheading>
                </div>
                
                <!-- RIGHT -->
                <div class="w-full lg:w-auto">

                    <div>
                        <flux:button
                            wire:click="addRole"
                            variant="primary"
                            color="blue"
                            icon:trailing="plus"
                            class="rounded-xl">
                            Create Role
                        </flux:button>
                    </div>

                </div>

            </div>
            <flux:separator variant="subtle" class="mt-4" />
        </div>



   
    <!-- Flash Message -->
    <x-flash-message />

    <!-- Table Section -->
    <x-role-component :roles="$this->roles" />

    <!-- Form Section -->
   <x-role-form-component :showModal="$showModal" :roleId="$roleId" :roleName="$roleName" :rolePermissions="$rolePermissions" :allPermissions="$allPermissions" :groupedPermissions="$groupedPermissions" :collapsedGroups="$collapsedGroups" :selectAllInGroup="$selectAllInGroup" :selectAllPermissions="$selectAllPermissions"/>

</section>



</div>