<section class="w-full">
    <x-settings-header
        title="{{ __('Users') }}"
        subtitle="{{ __('List of all users.') }}"
    />
    <div class="w-full rounded-lg">
        <div class="space-y-4">

            <!-- 🔍 Search + Header -->
          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">

            <!-- Search -->
            <div class="w-full md:w-1/3">
                <flux:input
                    wire:model.live="search"
                    placeholder="Search by name or email..."
                    icon="magnifying-glass"
                />
            </div>

            <!-- Buttons -->
            <div class="w-full flex flex-col sm:flex-row gap-2 md:justify-end">

                @can('create_users')
                    <flux:button
                        wire:click="createUser"
                        variant="primary"
                        color="blue"
                        icon:trailing="plus"
                        class="w-full sm:w-auto"
                    >
                        Create User
                    </flux:button>
                @else
                    <flux:button
                        variant="primary"
                        color="gray"
                        icon:trailing="plus"
                        disabled
                        class="w-full sm:w-auto"
                    >
                        Create User
                    </flux:button>
                @endcan

                <flux:button
                    wire:click="exportUser"
                    variant="primary"
                    color="blue"
                    icon:trailing="arrow-down-tray"
                    class="w-full sm:w-auto"
                >
                    Export Data
                </flux:button>

            </div>

            
        </div>



            <!-- Flash Messages -->
            <div class="mb-3">
                @if (session()->has('error'))
                    <div class="bg-red-100 text-red-700 p-2 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session()->has('success'))
                    <div class="bg-green-100 text-green-700 p-2 rounded">
                        {{ session('success') }}
                    </div>
                @endif
            </div>



            <!-- 📋 Table -->
                <div wire:poll.5s>
                    <div class="w-full overflow-x-auto  rounded-xl border">

            
                        
                        <table class="min-w-full text-sm text-left">

                            <!-- Header -->
                            <thead class="bg-gray-600 text-gray-200 uppercase text-xs">
                                <tr>
                                    <th class="block md:table-cell px-6 py-3">Name</th>
                                    <th class="block md:table-cell px-6 py-3 md:table-cell">Email</th>
                                    <th class="block md:table-cell px-6 py-3 lg:table-cell">Role</th>
                                    <th class="block md:table-cell px-6 py-3 lg:table-cell">Company</th>
                                    <th class="block md:table-cell px-6 py-3">Status</th>
                                    <th class="block md:table-cell px-6 py-3">Actions</th>
                                </tr>
                            </thead>

                            <!-- Body -->
                            <tbody class="divide-y">
                                @forelse ($users as $user)

                                  @php
                                    $hasCompany = !is_null($user->companies);
                                    $hasTrashed = $user->trashed();
                                  @endphp
                               
                                <tr class="block md:table-row border-b md:border-0 p-4 md:p-0 dark:text-white hover:bg-gray-100 transition-colors  {{ $user->trashed() ? 'bg-red-50' : '' }}">
                                        

                                        <td class="block md:table-cell px-6 py-4 text-gray-500 md:table-cell">
                                            {{ $user->name }}<br>
                                            {{ $user->trashed() ? '(Deleted)' : '' }}
                                        </td>

                                        <td class="block md:table-cell px-6 py-4 text-gray-500 md:table-cell">
                                            {{ $user->email }}
                                        </td>

                                        <td class="block md:table-cell px-6 py-4 lg:table-cell">
                                            <!-- role names -->
                                            @if ($user->roles->isNotEmpty())
                                                @foreach ($user->roles as $role)
                                                    <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $role->name }}
                                                    </span>
                                                @endforeach
                                            @endif
                                        </td>

                                        <td class="block md:table-cell px-6 py-4 lg:table-cell text-gray-500 md:table-cell">
                                            {{ $user->companies?->company_name ?? 'N/A' }}
                                        </td>

                                        <td class="block md:table-cell px-6 py-4">

                                        @can('status_users')
                                            <flux:button 
                                                wire:click="toggleStatus({{ $user->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="toggleStatus({{ $user->id }})"
                                                size="sm"
                                                variant="primary"
                                                color="{{ $user->status ?'green' :  'red' }}"
                                                icon="{{ $user->status ? 'check' : 'x-mark' }}"
                                                cursor="pointer"
                                                title="{{ $user->status ? 'Deactivate' : 'Activate' }}"
                                                :disabled="!$hasCompany"
                                                :disabled="$hasTrashed"
                                                        
                                            />
                                            @else
                                                <flux:button
                                                    size="sm"
                                                    variant="primary"
                                                    color="{{ $user->status ?'gray' :  'gray' }}"
                                                    icon="{{ $user->status ? 'check' : 'x-mark' }}"
                                                    cursor="pointer"
                                                    title="{{ $user->status ? 'Deactivate' : 'Activate' }}"
                                                    disabled
                                                />
                                            @endcan

                                                
                                                

                                        
                                        </td>
                                           <td class="block md:table-cell py-2 md:py-4">

                                            <div class="flex items-center gap-2 md:justify-start">

                                            @if($user->trashed())

                                          

                                            
                                                <flux:button 
                                                    wire:click="restoreUser('{{ $user->id }}')"
                                                    wire:loading.attr="disabled"
                                                    wire:target="restoreUser('{{ $user->id }}')"
                                                    size="sm"
                                                    variant="primary"
                                                    color="green"
                                                    icon="arrow-path"
                                                    cursor="pointer"
                                                    title="Restore User"
                                                    :disabled="!$hasCompany"
                                                />

                                                
                                                        <flux:button 
                                                            wire:click="forceDelUser({{ $user->id }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="forceDelUser({{ $user->id }})"
                                                            wire:confirm="Are you sure? This cannot be undone.Permanent deletion will remove all associated data and cannot be recovered."
                                                            size="sm"
                                                            variant="danger"
                                                            color="black"
                                                            icon="cog"
                                                            cursor="pointer"
                                                            title="Permanently Delete User"
                                                            :disabled="!$hasCompany"
                                                        />
                                                       

                                            @else
                                                    @can('edit_users')
                                                        <!-- Edit -->
                                                        <flux:button 
                                                            wire:click="editUser({{ $user->id }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="editUser({{ $user->id }})"
                                                            size="sm"
                                                            variant="primary"
                                                            color="green"
                                                            icon="pencil"
                                                            cursor="pointer"
                                                            title="Edit User"
                                                        />
                                                        @else
                                                        <flux:button
                                                            size="sm"
                                                            variant="primary"
                                                            color="gray"
                                                            icon="pencil"
                                                            cursor="pointer"
                                                            title="No permission"
                                                            disabled
                                                        />
                                                    @endcan

                                                    @can('delete_users')
                                                        <!-- Delete -->
                                                        <flux:button 
                                                            wire:click="deleteUser({{ $user->id }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="deleteUser({{ $user->id }})"
                                                            wire:confirm="Are you sure?"
                                                            size="sm"
                                                            variant="danger"
                                                            color="red"
                                                            icon="trash"
                                                            cursor="pointer"
                                                            title="Delete User"
                                                        />
                                                        @else
                                                        <flux:button
                                                            size="sm"
                                                            variant="primary"
                                                            color="gray"
                                                            icon="trash"
                                                            cursor="pointer"
                                                            title="No permission"
                                                            disabled
                                                        />
                                                    @endcan
                                            @endif
                                        </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-6 text-gray-500">
                                            No users found 😔
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>
        </div>
    </div>


   @script
<script>
    $wire.on('company-required', () => {

        Swal.fire({
            icon: 'warning',
            title: 'First Create Company',
            text: 'Redirecting in 5 seconds...',
            timer: 5000,
            showConfirmButton: false
        });

        setTimeout(() => {
            window.location.href = '/settings/companyinfo';
        }, 5000);

    });
</script>
@endscript
</section>