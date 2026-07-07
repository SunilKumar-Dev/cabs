<section class="w-full">

   <x-settings-header
        title="{{ __('Create User') }}"
        subtitle="{{ __('Create a new user') }}"
    />

        <section class="w-full md:w-1/2 lg:w-1/3 rounded-lg border">
            <form wire:submit.prevent="createUser" class="p-6 w-full space-y-6">

              

                <flux:input wire:model="name" :label="__('Full Name')" type="text" autofocus autocomplete="name" placeholder="Full Name" />

                <flux:input wire:model="email" :label="__('Email')" type="email" autocomplete="email" placeholder="email@example.com" />

                <flux:input wire:model="password" :label="__('Password')" type="password" autocomplete="new-password" placeholder="********" />
                <flux:input wire:model="password_confirmation" :label="__('Confirm Password')" type="password" autocomplete="new-password" placeholder="********" />

               <div class="space-y-2">

                 @if(auth()->user()->hasRole('Superadmin'))
                        <label class="block text-sm font-medium">
                            {{ __('Company') }}
                        </label>

                        <div class="flex flex-wrap gap-2">
                            @foreach($companies as $company)
                                <label class="flex items-center gap-2">
                                    <input 
                                        type="radio"
                                        {{-- wire:model="company_uuid" --}}
                                        wire:model.live="company_uuid"
                                        value="{{ $company->company_uuid }}"
                                        class="form-radio h-4 w-4 text-indigo-600"
                                    >
                                    <span class="text-sm">
                                        {{ $company->company_name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>

                    @elseif(auth()->user()->hasRole('Admin'))

                        {{-- Admin: fixed company (no selection) --}}
                        <input type="hidden" wire:model="company_uuid">

                        <p class="text-sm text-gray-600">
                            Company: {{ auth()->user()->companies?->company_name }}
                        </p>

                    @endif
                   
                     @error('company_uuid') 
                            <div role="alert" aria-live="polite" aria-atomic="true" class="mt-3 text-sm font-medium text-red-500 dark:text-red-400" data-flux-error="">
                                                    <svg class="shrink-0 [:where(&amp;)]:size-5 inline" data-flux-icon="" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"></path>
                            </svg> 
                            {{ $message }}
                        </div>
                        @enderror
                        @if(!auth()->user()->hasRole('Superadmin'))
                        <label class="block text-sm font-medium">
                            {{ __('Roles') }}
                        </label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($rolesList as $role)
                                <label class="flex items-center gap-2">
                                    <input 
                                        type="radio"
                                        wire:model="roles"
                                        value="{{ $role->name }}"
                                        class="form-radio h-4 w-4 text-indigo-600"
                                    >
                                    <span class="text-sm">
                                        {{ $role->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div> 
                        @error('roles')
                            <div role="alert" aria-live="polite" aria-atomic="true" class="mt-3 text-sm font-medium text-red-500 dark:text-red-400" data-flux-error="">
                                                    <svg class="shrink-0 [:where(&amp;)]:size-5 inline" data-flux-icon="" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"></path>
                            </svg> 
                            {{ $message }}
                        </div>
                        @enderror
                        @endif
                    </div>

              
                    
                <div class="flex items-center gap-4">
                    <flux:button variant="primary" type="submit">{{ __('Create') }}</flux:button>
                    <flux:button href="{{ route('users.index') }}" color="gray" wire:navigate>
                        {{ __('Back') }}
                    </flux:button>  
                </div>
            </form>
        </section>  

