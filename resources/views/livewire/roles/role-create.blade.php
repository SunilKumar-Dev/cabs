
<div>

<section class="w-full">

   <x-settings-header
        title="{{ __('Create Role') }}"
        subtitle="{{ __('Create a new role') }}"
    />

        <section class="w-full md:w-1/2 lg:w-1/3 rounded-lg border" style="padding:20px" >  
            
        <form wire:submit.prevent="createRole">

            <div class="space-y-6">
                

                <!-- Name -->
                <div>
                        <flux:input 
                        id="name" 
                        type="text" 
                        label="Role Name"
                        wire:model.defer="name" 
                        placeholder="Role Name"
                    />
                
                </div>
                <div>
                    <!-- Permissions -->
                <flux:checkbox.group label="Permissions" wire:model="selectedPermissions" class="flex flex-wrap space-x-4">

                <flux:checkbox.all label="Select All" />
                <flux:separator class="my-2" />
                @dd($allPermissions);

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                    @foreach($allPermissions as $permission)
                        <flux:checkbox 
                            id="permission-{{ $permission}}" 
                            label="{{ $permission}}"
                            value="{{ $permission}}" 
                            wire:model="selectedPermissions"
                        />
                    @endforeach
                </div>

                </flux:checkbox.group>
            </div>
                <!-- Submit Button -->
                <div class="flex items-center gap-4">
                            <flux:button variant="primary" type="submit">{{ __('Create Role') }}</flux:button>
                            <flux:button href="{{ route('roles.index') }}" color="gray" wire:navigate>
                                {{ __('Cancel') }}
                            </flux:button>  
                        </div>
<!-- 
                {{-- <div>
                    <flux:button 
                        type="submit"
                        variant="primary"
                        color="blue"
                        icon:trailing="plus"
                    >
                        Create Role
                    </flux:button>              
                </div> --}} -->
            </div>
       </form>
        </section>  
</section>
</div>




