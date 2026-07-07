<div class="space-y-4">
    <!-- 🔍 Search + Header -->
    <div class="flex items-center justify-between">
                 <div class="w-full flex justify-end">
                    @auth
                    @can('create_roles')
                                        <!-- Create Button -->
                                        <flux:button 
                                            wire:click="createRole"
                                            variant="primary"
                                            color="blue"
                                            cursor="pointer"
                                            icon:trailing="plus"
                                        >
                                            Create Role
                                        </flux:button>
                                        @else
                                    
                        <flux:button
                            variant="primary"
                            color="gray"
                            icon:trailing="plus"
                            disabled
                        >
                        Create Role
                        </flux:button> 
                    @endcan
                    @endauth
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
            <div class="w-full overflow-x-auto  rounded-xl">
                <table class="min-w-full text-sm text-left">

                    <!-- Header -->
                    <thead class="bg-gray-600 text-gray-200 uppercase text-xs">
                        <tr>
                            <th class="block md:table-cell px-6 py-3 lg:table-cell text-left">Role</th>
                            <th class="block md:table-cell px-6 py-3 text-left">Permissions22</th>
                            <th class="block md:table-cell px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>

  
                    <!-- Body -->
                    <tbody class="divide-y">
                    @forelse($roles as $role)
                       <tr class="block md:table-row border-b md:border-1 p-4 md:p-0 dark:text-white hover:bg-gray-100 transition-colors">
                       
                         <td class="block md:table-cell px-6 py-4 text-gray-500 md:table-cell">
                                            {{ $role->name }}<br>
                                        </td>

                                    

                                        <td class="block md:table-cell px-6 py-4 text-left text-gray-500 md:table-cell">
                                            {!! formatPermissions($role->permissions) !!}
                                        </td>


                            
                                {{-- <td class="px-6 py-4 text-right space-x-2"> --}}
                                <td class="block md:table-cell px-6 py-4 text-gray-500 md:table-cell">
                                    <div class="flex justify-end gap-2">
                                
                                    <!-- Edit -->
                                    @auth
                                    @can('edit_roles')
                                    <flux:button 
                                        wire:click="editRole({{ $role->id }})"
                                        size="sm"
                                        variant="primary"
                                        color="green"
                                        icon="pencil"
                                    />
                                    @else
                                    <flux:button 
                                        variant="primary"
                                        color="gray"
                                        icon="pencil"
                                        disabled
                                    />
                                    @endcan
                                @endauth
                            
                                    <!-- Delete -->
                                    @can('delete_roles')
                                    <flux:button 
                                        wire:click="deleteRole({{ $role->id }})"
                                        wire:confirm="Are you sure?"
                                        size="sm"
                                        variant="danger"
                                        color="red"
                                        icon="trash"
                                    />
                                    @else
                                    <flux:button 
                                        variant="primary"
                                        color="gray"
                                        icon="trash"
                                        disabled
                                    />
                                    @endcan
                                
                                </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-6 text-gray-500 text-gray-500 md:table-cell">
                                            No roles found 😔
                                        </td>
                            
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
</div>