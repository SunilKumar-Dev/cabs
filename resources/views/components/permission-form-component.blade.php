<div>

@props([
    'showModal',
    'permissionId',
    'permissionName'
])

 

<x-flux::modal 
    wire:model="showModal"
    size="3xl"
    class="!p-0 overflow-hidden rounded-2xl shadow-2xl"
>

    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6 text-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-white/20 flex items-center justify-center">
                    🏢
                </div>

                <div>
                    <h2 class="text-3xl font-bold">
                         {{ $permissionId ? 'Edit Permission' : 'Add Permission' }}
                    </h2>
                    <p class="text-blue-100 text-sm">
                        {{ $permissionId ? 'Update permission settings and access controls' : 'Create permission settings and access controls' }}
                    </p>
                </div>
            </div>
        </div>
    </div>


    <!-- Body -->
    <form wire:submit.prevent="{{ $permissionId ? 'updateSavePermission' : 'createSavePermission' }}">

        <div class="p-8 max-h-[75vh] overflow-y-auto space-y-6">

            <div class="grid grid-cols-2 gap-6">

                <!-- Company Name -->
                <div class="col-span-2">
                    <flux:input
                        wire:model.live="permissionName"
                        label="Permission Name"
                        placeholder="Enter Permission name"
                    />
                </div>


            </div>
        </div>


        <!-- Sticky Footer -->
        <div class="sticky bottom-0 bg-white border-t px-8 py-4 flex justify-end gap-3">

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
                color="blue"
                wire:loading.attr="disabled"
                wire:target="savePermission"
            >
                {{ $permissionId ? 'Update Permission' : 'Create Permission' }}
            </flux:button>

        </div>

    </form>

</x-flux::modal>

</div>