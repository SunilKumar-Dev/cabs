<div class="space-y-4">

    <!-- Header -->
    <div class="flex justify-end">
        @can('create_permissions')
            <flux:button
                wire:click="createPermission"
                variant="primary"
                color="blue"
                cursor="pointer"
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

    <!-- Permissions Table -->
    <div class="w-full overflow-x-auto rounded-xl border">
        <table class="min-w-full text-sm text-left">

            <!-- Table Header -->
            <thead class="bg-gray-600 text-gray-200 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 text-left">
                        Permissions
                    </th>
                    <th class="px-6 py-3 text-right">
                        Actions
                    </th>
                </tr>
            </thead>

            <!-- Table Body -->
            <tbody class="divide-y">
                @forelse($permissions as $permission)
                    <tr class="block md:table-row border-b p-4 md:p-0">

                        <!-- Permission Name -->
                        <td class="block md:table-cell px-6 py-4 text-gray-500">
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-100">
                                {{ Str::headline($permission->name) }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="block md:table-cell px-6 py-4 text-gray-500">
                            <div class="flex justify-end gap-2">

                                <!-- Edit -->
                                @auth
                                    @can('edit_permissions')
                                        <flux:button
                                            wire:click="editPermission({{ $permission->id }})"
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
                                @can('delete_permissions')
                                    <flux:button
                                        wire:click="deletePermission({{ $permission->id }})"
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
                        <td colspan="2" class="py-6 text-center text-gray-500">
                            No permissions found 😔
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>