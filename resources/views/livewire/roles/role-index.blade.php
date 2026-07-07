

<section class="w-full">

   <x-settings-header
        title="{{ __('Roles') }}"
        subtitle="{{ __('List of all roles.') }}"
    />

        <div class="w-full rounded-lg">
            <livewire:roles.role-table  />
            
        </div>

</section>
