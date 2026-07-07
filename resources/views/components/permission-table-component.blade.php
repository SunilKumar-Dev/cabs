<div>
    @props(['permissions'])

    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
        <table class="w-full border-collapse">
            <thead class="bg-gray-200 dark:bg-gray-600/50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-600">
                        Permission
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-600">
                        Module
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-600">
                        Description
                    </th>
                    <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-600">
                        Actions
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
    @php
        $groupedPermissions = $permissions->groupBy(function ($permission) {
            $lastWord = last(explode('_', $permission->name));
            return ucfirst(Str::plural($lastWord));
        });
    @endphp

    @forelse($groupedPermissions as $group => $items)
        <tr x-data="{ open: false }">
            <td colspan="4" class="p-0">

                {{-- Group Header --}}
                <div
                    @click="open = !open"
                    class="flex items-center justify-between px-6 py-4 cursor-pointer bg-slate-50 dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 border-y border-gray-200 dark:border-gray-700"
                >
                    <div class="flex items-center gap-3">
                        <svg
                            class="w-4 h-4 transition-transform"
                            :class="{ 'rotate-90': open }"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5l7 7-7 7"/>
                        </svg>

                        <h3 class="font-bold text-base text-gray-800 dark:text-slate-200">
                            {{ $group }}
                        </h3>

                        <span class="text-xs px-2 py-1 rounded-full bg-slate-100 dark:bg-slate-800">
                            {{ $items->count() }} permissions
                        </span>
                    </div>

                    @can('delete_permissions')
                        <flux:button
                            wire:click.stop="deleteGroup('{{ $group }}')"
                            wire:confirm="Delete all {{ $group }} permissions?"
                            size="sm"
                            variant="subtle"
                            color="red"
                            icon="trash"
                        >
                            Delete Group
                        </flux:button>
                    @endcan
                </div>

                {{-- Permissions --}}
                <div x-show="open" x-collapse>
                    <table class="w-full">
                        @foreach($items as $permission)
                            @php
                                $parts = explode('_', $permission->name);

                                $description = match($parts[0]) {
                                    'view'   => 'Allows viewing records',
                                    'create' => 'Allows creating records',
                                    'edit'   => 'Allows editing records',
                                    'update' => 'Allows updating records',
                                    'delete' => 'Allows deleting records',
                                    default  => 'System permission',
                                };
                            @endphp

                            <tr class="border-t border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="px-6 py-3 w-1/3">
                                    <div class="font-medium text-gray-900 dark:text-white">
                                        {{ Str::headline($permission->name) }}
                                    </div>
                                    <div class="text-xs text-gray-400 font-mono">
                                        {{ $permission->name }}
                                    </div>
                                </td>

                                <td class="px-6 py-3 w-1/4">
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                                        {{ $group }}
                                    </span>
                                </td>

                                <td class="px-6 py-3 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $description }}
                                </td>

                                <td class="px-6 py-3 text-right">
                                    <div class="flex justify-end gap-2">
                                        @can('edit_permissions')
                                            <flux:button
                                                wire:click="editPermission({{ $permission->id }})"
                                                size="sm"
                                                variant="subtle"
                                                icon="pencil"
                                                square
                                            />
                                        @endcan

                                        @can('delete_permissions')
                                            <flux:button
                                                wire:click="deletePermission({{ $permission->id }})"
                                                wire:confirm="Delete this permission?"
                                                size="sm"
                                                variant="subtle"
                                                color="red"
                                                icon="trash"
                                                square
                                            />
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>

            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                No permissions found
            </td>
        </tr>
    @endforelse
</tbody>

         
        </table>
    </div>

  
</div>