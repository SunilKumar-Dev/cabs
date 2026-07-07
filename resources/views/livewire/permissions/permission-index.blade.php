

<section class="w-full">

   <x-settings-header
        title="{{ __('Permissions') }}"
        subtitle="{{ __('List of all permissions.') }}"
    />

        <div class="w-full rounded-lg">
            <livewire:permissions.permission-table />
            
        </div>

</section>
