<?php

use Livewire\Component;
// use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

new #[Title('Permission List')] class extends Component
{
    // use WithPagination;

    public $permissionId;
    public $permissionName; 
    public $selectedPermissions = [];
    public $showModal = false;


    public function deletePermission($id)
    {
        $permission = Permission::find($id);

        if (!$permission) {

            session()->flash('error', 'Permission not found!');
            return;
        }

        // Only Superadmin can delete permissions
        if (!auth()->user()->hasRole('Superadmin')) {

            session()->flash(
                'error',
                'You are not authorized!'
            );

            return;
        }   
        
        $protectedGroups = [
            'users',
            'roles',
            'permissions',
        ];


        // Single permission protection
        if (Str::endsWith($permission->name, $protectedGroups)) {
            session()->flash(
                'error',
                Str::headline($permission->name) . ' permission cannot be deleted!'
            );
            return;
        }
   
      // Find which protected group matches the permission suffix
        // $matchedGroup = collect($protectedGroups)
        //     ->first(fn ($group) => Str::endsWith($permission->name, $group));

        // if ($matchedGroup) {
        //     session()->flash(
        //         'error',
        //         ucfirst(Str::singular($matchedGroup)) . ' -permission cannot be deleted!'
        //     );

        //     return;
        // }

        $permission->delete();

        session()->flash(
            'success',
            'Permission deleted successfully!'
        );
    }

    public function with(): array
    {
        return [
            'permissions' => Permission::query()

                // Hide *_permissions for non-superadmin
                ->when(
                    !auth()->user()->hasRole('Superadmin'),
                    fn ($q) => $q->whereNotLike('name', '%_permissions')
                )

                ->latest()
                ->get(),
                // ->paginate(10),
        ];
    }



    public function editPermission($id)
    {
        $this->resetValidation();
        $this->resetErrorBag();
        
        $permission = Permission::find($id);

        if (!$permission) {

            session()->flash('error', 'Permission not found!');
            return;
        }

        $this->permissionId = $permission->id;

        $this->permissionName = $permission->name;

        $this->showModal = true;
    }


    public function createPermission()
    {
            $this->resetValidation();
            $this->resetErrorBag();
            $this->reset(['permissionId', 'permissionName']);
            $this->showModal = true;
    }


    public function createSavePermission()
    {
        $this->validate([
            'permissionName' => 'required|string|max:255',
        ]);

        $module = trim(strtolower($this->permissionName));

        $prefixes = [
            'viewAny_',
            'view_',
            'create_',
            'edit_',
            'update_',
            'delete_',
            'status_',
            // 'restore_',
            // 'forceDelete_',
        ];

        foreach ($prefixes as $prefix) {
            $permissionName = $prefix . $module;

            // Check if permission already exists
            $exists = Permission::where('name', $permissionName)->exists();

        // Create only if not exists
        if (!$exists) {
            Permission::create([
                'name' => $permissionName,
            ]);
        }
    }

        // Clear Spatie permission cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        session()->flash(
            'success',
            'Permissions checked and missing permissions created successfully!'
        );

        $this->reset([
            'permissionId',
            'permissionName',
        ]);

        $this->showModal = false;
    }



    public function updateSavePermission()
    {
            $this->validate([
            'permissionName' => 'required|string|max:255|unique:permissions,name,' . $this->permissionId,
        ]);

        Permission::updateOrCreate(
            ['id' => $this->permissionId],
            [
                'name' => trim($this->permissionName),
            ]
        );

        session()->flash(
            'success',
            $this->permissionId
                ? 'Permission updated successfully!'
                : 'Permission created successfully!'
        );

        // Reset form
        $this->reset([
            'permissionId',
            'permissionName',
        ]);

        $this->showModal = false;
    }


    public function deleteGroup($group = null)
    {
    

        if (!$group) {
            session()->flash('error', 'Invalid group.');
            return;
        }
        // Only Superadmin can delete permissions
            if (!auth()->user()->hasRole('Superadmin')) {

                session()->flash(
                    'error',
                    'You are not authorized!'
                );

                return;
            }

        $protectedGroups = [
            'users',
            'roles',
            'permissions'
        ];
        // Prevent deleting protected groups
        if (in_array(strtolower($group), $protectedGroups)) {
            session()->flash(
                'error',
                'This permission ( ' . ucfirst($group) . ' ) group cannot be deleted.'
            );

            return;
        }

        // Check if group exists
        $exists = Permission::where('name', 'like', '%_' . $group)->exists();

        if (!$exists) {
            session()->flash('error', 'Permission group not found.');
            return;
        }

        // Delete group
        Permission::where('name', 'like', '%_' . $group)->delete();


        app(PermissionRegistrar::class)
            ->forgetCachedPermissions();

        session()->flash(
            'success',
            ucfirst($group) . ' group deleted successfully.'
        );
    }

};

?>
<div>

<section class="w-full">
        
        
        
        <!-- Header Section -->

        <div class="relative mb-6 w-full">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-star lg:justify-between">
                <!-- LEFT -->
                <div class="min-w-0">
                    <flux:heading size="xl" level="1">
                      Role-Permission Management
                    </flux:heading>

                    <flux:subheading size="lg" class="mt-1">
                       Manage system permissions and access control
                    </flux:subheading>
                </div>
                
                <!-- RIGHT -->
                <div class="w-full lg:w-auto">

                    <div>
                         @can('create_permissions')

                            <flux:button
                                wire:click="createPermission"
                                variant="primary"
                                color="blue"
                                icon:trailing="plus"
                            >
                                Create Permission
                            </flux:button>

                        @else

                            <flux:button
                                variant="primary"
                                color="gray"
                                icon:trailing="plus"
                                disabled
                            >
                                Create Permission
                            </flux:button>

                        @endcan
                    </div>

                </div>

            </div>
            <flux:separator variant="subtle" class="mt-4" />
        </div>


    <!-- flash message -->
    <x-flash-message />

    <!-- Table -->
    <x-permission-table-component :permissions="$permissions" />

    <!-- Modal -->
    <x-permission-form-component :showModal="$showModal" :permissionId="$permissionId" :permissionName="$permissionName" />


</section>

</div>