<div>
@props(['showModal', 'roleId', 'roleName', 'rolePermissions', 'allPermissions', 'groupedPermissions', 'collapsedGroups', 'selectAllInGroup', 'selectAllPermissions'])

       <x-flux::modal 
    wire:model="showModal"
    size="2xl"
    class="!p-0 overflow-hidden rounded-3xl shadow-2xl bg-white dark:bg-gray-900"
>


    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-violet-600 via-indigo-600 to-blue-600 px-8 py-7 text-white">

        <div class="flex items-center justify-between">

            <div class="flex items-center gap-4">

                <div class="w-14 h-14 rounded-2xl bg-white/15 backdrop-blur flex items-center justify-center text-2xl shadow-lg">
                    🏢
                </div>

                <div>
                    <h2 class="text-3xl font-bold tracking-tight">
                        {{ $roleId ? 'Edit Role' : 'Create Role' }}
                    </h2>

                    <p class="text-sm text-white/80 mt-1">
                        {{ $roleId
                            ? 'Update role permissions and access controls'
                            : 'Create role permissions and access controls'
                        }}
                    </p>
                </div>

            </div>

        </div>

    </div>



<form wire:submit.prevent="saveRole" class="flex flex-col h-[85vh]">

    {{-- FIXED TOP --}}
    <div class="px-8 pt-4 pb-4 space-y-6">

        {{-- ROLE NAME --}}
        <div>
            <flux:input
                wire:model="roleName"
                label="Role Name"
                placeholder="Enter role name"
            />
        </div>

        {{-- SELECT ALL --}}
        <div class="flex items-center gap-3 px-5 py-4 rounded-2xl border border-violet-200 bg-violet-50">
              <input
                    type="checkbox"
                    wire:model="selectAllPermissions"
                    wire:click="toggleSelectAll"
                    class="w-5 h-5 rounded text-violet-600 border-gray-300 focus:ring-violet-500"
                >

                <span class="font-semibold text-gray-800 dark:text-white">
                    Select All Permissions
                </span>

        </div>

    </div>

    {{-- ONLY THIS SCROLLS --}}
    <div class="flex-1 overflow-y-auto px-8 max-h-[65vh] space-y-4 pr-2">

          @php
                    $groupedPermissions = [];

                    foreach($allPermissions as $permission) {
                        $suffix = Str::plural(Str::afterLast($permission->name, '_'));
                        $groupedPermissions[ucfirst($suffix)][] = $permission;
                    }
                @endphp

                @foreach($groupedPermissions as $groupName => $permissions)

                    @continue($groupName === 'Permissions' && !auth()->user()->hasRole('Superadmin'))

                    <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm overflow-hidden mb-10">

                        {{-- GROUP HEADER --}}
                        <div class="flex items-center justify-between px-6 py-5 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">

                            <button
                                type="button"
                                class="flex items-center gap-3"
                                wire:click="toggleCollapse('{{ $groupName }}')"
                            >

                                <h3 class="text-lg font-bold text-gray-800 dark:text-white">
                                    {{ $groupName }}
                                </h3>

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-4 h-4 text-gray-500 transition-transform duration-300
                                        {{ ($collapsedGroups[$groupName] ?? false) ? '' : 'rotate-180' }}"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>

                            </button>

                            {{-- GROUP SELECT --}}
                            <label class="flex items-center gap-3 px-4 py-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:border-violet-400 transition cursor-pointer">

                                <input
                                    type="checkbox"
                                    wire:model="selectAllInGroup.{{ $groupName }}"
                                    wire:click="toggleSelectGroup('{{ $groupName }}')"
                                    class="w-5 h-5 rounded text-violet-600 border-gray-300 focus:ring-violet-500"
                                >

                                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">
                                    Select All
                                </span>

                            </label>

                        </div>

                        {{-- PERMISSIONS --}}
                        <div
                            @if($collapsedGroups[$groupName] ?? false)
                                style="display:none;"
                            @endif
                            class="p-6"
                        >

                            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">

                                @foreach($permissions as $permission)

                                    <label class="permission-card">

                                        <input
                                            type="checkbox"
                                            value="{{ $permission->name }}"
                                            wire:model="rolePermissions"
                                            class="permission-checkbox"
                                        >

                                        <span class="permission-text">
                                            {{ Str::headline($permission->name) }}
                                        </span>

                                    </label>

                                @endforeach

                            </div>

                        </div>

                    </div>

                @endforeach


    </div>

    {{-- FIXED FOOTER --}}
    <div class="sticky bottom-0 bg-white border-t px-8 py-5 flex justify-end gap-3">
        <flux:button
                type="button"
                wire:click="$set('showModal', false)"
                variant="ghost"
            >
                Cancel
            </flux:button>

            <flux:button
                type="submit"
                variant="primary"
                color="violet"
                wire:loading.attr="disabled"
                wire:loading.class="opacity-60"
                wire:target="saveRole"
                class="px-6"
            >
                {{ $roleId ? 'Update Role' : 'Create Role' }}
            </flux:button>
    </div>

</form>





    {{-- STYLES --}}
    <style>
        .permission-card {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px;
            border-radius: 18px;
            border: 1px solid rgb(229 231 235);
            background: white;
            cursor: pointer;
            transition: all .2s ease;
        }

        .dark .permission-card {
            background: rgb(31 41 55);
            border-color: rgb(55 65 81);
        }

        .permission-card:hover {
            transform: translateY(-2px);
            border-color: rgb(139 92 246);
            background: rgb(245 243 255);
            box-shadow: 0 10px 20px rgba(139,92,246,0.08);
        }

        .dark .permission-card:hover {
            background: rgba(139,92,246,0.10);
        }

        .permission-checkbox {
            width: 18px;
            height: 18px;
            accent-color: #7c3aed;
            border-radius: 6px;
        }

        .permission-text {
            font-size: 14px;
            font-weight: 500;
            color: rgb(55 65 81);
            line-height: 1.5;
        }

        .dark .permission-text {
            color: rgb(229 231 235);
        }
    </style>

</x-flux::modal>



</div>