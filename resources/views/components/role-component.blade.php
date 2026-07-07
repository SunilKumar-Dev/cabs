<div>
@props(['roles'])

<div class="grid grid-cols-[repeat(auto-fill,minmax(320px,1fr))] gap-6 w-full">
                    
    @forelse ($this->roles as $role)

        <flux:card wire:key="role-card-{{ $role->id }}" class="p-6 flex flex-col justify-between h-full">
            
            <div>
                {{-- Header Section --}}
                <div class="flex items-start justify-between gap-4 mb-5">
                    <div class="min-w-0">
                        <flux:text class="font-bold text-lg text-gray-900 dark:text-white truncate">
                            {{ $role->name }}
                        </flux:text>

                        <flux:text class="text-xs text-gray-500 mt-0.5 block">
                            {{ $role->permissions->count() }} permissions
                        </flux:text>
                    </div>

                    {{-- Actions Block --}}
                    <div class="flex items-center gap-1.5 shrink-0">
                        <flux:button
                            variant="subtle"
                            size="sm"
                            icon="pencil"
                            square
                            wire:click="editRole('{{ $role->id }}')"
                        />

                        <flux:button
                            variant="subtle"
                            color="red"
                            size="sm"
                            icon="trash"
                            square
                            wire:click="confirmDelete('{{ $role->id }}')"
                            wire:confirm="Are you sure?"
                        />
                    </div>
                </div>

                {{-- Grouping and Cleaning Permission Tags --}}
                @php
                    $groupedPermissions = [];

                    foreach($role->permissions as $permission) {
                        // 1. Last word nikalen group name ke liye (e.g., 'view_any_users' -> 'users')
                        $parts = explode('_', $permission->name);
                        $lastWord = last($parts);
                        $groupName = ucfirst(Str::plural($lastWord));

                        // 2. Clean Action text nikalen (e.g., 'view_any_users' me se 'users' hatakar 'view_any' rakhna)
                        if (count($parts) > 1) {
                            array_pop($parts); // Last word (module name) ko drop kiya
                            $actionText = implode(' ', $parts); // Baki bache words ko join kiya
                        } else {
                            $actionText = $permission->name;
                        }

                        // Display text ko cleanly Headline case kiya (e.g., 'view any')
                        $cleanBadgeName = Str::headline($actionText);
                        
                        $groupedPermissions[$groupName][] = [
                            'id' => $permission->id,
                            'display' => $cleanBadgeName
                        ];
                    }
                @endphp

                {{-- Permissions Body --}}
                <div class="space-y-4">
                    @foreach ($groupedPermissions as $groupName => $permissions)
                        <div>
                            <flux:text size="sm" class="font-semibold text-gray-800 dark:text-gray-200 mb-2 block">
                                {{ $groupName }}
                            </flux:text>
                             

                            <div class="flex flex-wrap gap-1.5">
                                @foreach ($permissions as $item)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-100/50 dark:border-blue-800/20">
                                          {{ $item['display'] }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </flux:card>

    @empty
        <div class="col-span-full bg-white dark:bg-gray-800 rounded-2xl p-10 text-center border border-gray-200 dark:border-gray-700">
            <flux:text class="text-gray-500">No roles found.</flux:text>
        </div>
    @endforelse

</div>