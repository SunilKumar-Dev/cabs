<section class="w-full">

   <x-settings-header
        title="{{ __('Update User') }}"
        subtitle="{{ __('Update user details') }}"
    />
     <!-- Flash Messages -->
            <div class="mb-3">
                @if (session()->has('error'))
                    <div class="bg-red-100 text-red-700 p-2 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session()->has('success'))
                    <div class="bg-green-100 text-green-700 p-2 rounded">
                        {{ session('success') }}
                    </div>
                @endif
            </div>
        <section class="w-full md:w-1/2 lg:w-1/3 rounded-lg border">
            <form wire:submit.prevent="updateUser" class="p-6 w-full space-y-6">

                <flux:input wire:model="name" :label="__('Full Name')" type="text" required autofocus autocomplete="name" placeholder="Full Name" />

                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" placeholder="email@example.com" />

                <flux:input wire:model="password" :label="__('Password')" type="password" autocomplete="new-password" placeholder="********" />
                <flux:input wire:model="password_confirmation" :label="__('Confirm Password')" type="password" autocomplete="new-password" placeholder="********" />
<div class="space-y-2">

                  {{-- @if(auth()->user()->hasRole('Superadmin'))
                        <label class="block text-sm font-medium">
                            {{ __('Company') }}
                        </label>
                        <div class="flex flex-wrap gap-2">
                       
                        

                           <select wire:model="selectedCompany" class="form-control">

    <option value="">Select Company</option>

    @foreach($companyList as $company)

        <option value="{{ $company->company_uuid }}">
            {{ $company->company_name }}
        </option>

    @endforeach

</select>
                                
                           
                        </div>
                        @error('selectedCompany') 
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                        @endif --}}
                       

                        

                 @if(auth()->user()->hasRole('Superadmin'))
                        <label class="block text-sm font-medium">
                            {{ __('Company') }}
                        </label>

                        <div class="flex flex-wrap gap-2">
                            @foreach($companyList as $company)
                                <label class="flex items-center gap-2">
                                    <input 
                                        type="radio"
                                        wire:model="selectedCompany"
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
                        <input type="hidden" wire:model="selectedCompany">

                        <p class="text-sm text-gray-600">
                            Company: {{ auth()->user()->companies?->company_name }}
                        </p>
                      
                    @endif


                        {{-- @if(!auth()->user()->hasRole('Superadmin')) --}}
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
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror
                        {{-- @endif --}}
                    </div>

               {{-- <div class="space-y-2">
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
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror
                    </div> --}}

              
                    
                <div class="flex items-center gap-4">
                    <flux:button variant="primary" type="submit">{{ __('Update') }}</flux:button>
                    <flux:button href="{{ route('users.index') }}" color="gray" wire:navigate>
                        {{ __('Back') }}
                    </flux:button>  
                </div>
            </form>
        </section>  

