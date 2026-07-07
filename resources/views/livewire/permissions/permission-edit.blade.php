<div>
    <section class="w-full">

        <!-- Header -->
        <x-settings-header
            title="{{ __('Edit Permission') }}"
            subtitle="{{ __('Edit an existing permission') }}"
        />

        <!-- Form Card -->
        <section class="w-full md:w-1/2 lg:w-1/3 rounded-lg border p-5">

            <form wire:submit.prevent="updatePermission">
                <div class="space-y-6">

                    <!-- Permission Name -->
                    <div>
                        <flux:input
                            id="name"
                            type="text"
                            label="Permission Name"
                            wire:model.defer="name"
                            placeholder="Permission Name"
                        />
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-4">
                        <flux:button
                            variant="primary"
                            type="submit"
                        >
                            {{ __('Update Permission') }}
                        </flux:button>

                        <flux:button
                            href="{{ route('permissions.index') }}"
                            color="gray"
                            wire:navigate
                        >
                            {{ __('Cancel') }}
                        </flux:button>
                    </div>

                </div>
            </form>

        </section>

    </section>
</div>