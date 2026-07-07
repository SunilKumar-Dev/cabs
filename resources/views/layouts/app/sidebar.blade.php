<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <style>
            aside {
    width: 280px;
}
.flux-sidebar-group-heading {
    white-space: nowrap;
}
</style>

    </head>


    <body class="min-h-screen bg-white dark:bg-zinc-800" x-data x-on:company-changed.window="window.location.reload()">
        
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            

<flux:sidebar.header>

    {{-- <div class="flex items-center gap-3">

        @if(auth()->user()->companies?->company_favicon)
            <img
                src="{{ asset('storage/'.auth()->user()->companies->company_favicon) }}"
                alt="Company Logo"
                class="w-10 h-10 rounded object-cover"
            >

        @else

            <div class="w-10 h-10 rounded bg-gray-200 flex items-center justify-center">
                <span class="text-gray-500 text-sm font-semibold">
                    {{ auth()->user()->companies?->company_name ? substr(auth()->user()->companies->company_name, 0, 2) : 'CP' }}
                </span>
            </div>

        @endif

        <span class="font-semibold text-sm">
                    {{ auth()->user()->companies->company_name ?? 'Company Name' }}

               

        </span>

    </div> --}}

     <div class="flex items-center gap-3 p-5 border-b">
         @if(auth()->user()->companies?->company_favicon)
            <img
                src="{{ asset('storage/'.auth()->user()->companies->company_favicon) }}"
                alt="Company Logo"
                class="w-10 h-10 rounded object-cover"
            >

        @else
                <div class="w-10 h-10 bg-indigo-100 text-indigo-600 font-bold flex items-center justify-center rounded-lg">
                   {{ auth()->user()->companies?->company_name ? substr(auth()->user()->companies->company_name, 0, 2) : 'CP' }}
                </div>
        @endif
        <div>
            <h2 class="font-semibold text-gray-800">
           
           
            @if(auth()->user()->hasRole('Superadmin'))
                Master Panel
            @else
                {{ auth()->user()->companies->company_name ?? App\Models\Company::where('company_uuid', session('active_company_uuid'))->value('company_name') }}
            @endif


            
        
        
        </h2>
            <p class="text-xs text-gray-500">{{ auth()->user()->roles->first()->name ?? '' }}</p>
        </div>
</div>             


</flux:sidebar.header>


        

<flux:sidebar.nav>

    {{-- Platform --}}
    <flux:sidebar.group class="grid">


     {{-- Top Admin Banner --}}
                     
      @if(auth()->user()->hasRole('Superadmin'))
<flux:sidebar.item
    icon="arrows-right-left"
    x-on:click="$flux.modal('switch-company').show()"
>
    Switch Company
</flux:sidebar.item>

<flux:modal name="switch-company">
    @livewire('pages.settings.current-company-modal')
</flux:modal>
      @endif




        {{-- Dashboard --}}
        <flux:sidebar.item
            icon="home"
            :href="route('dashboard')"
            :current="request()->routeIs('dashboard')"
            wire:navigate
        >
            {{ __('Dashboard') }}
        </flux:sidebar.item>

        {{-- Users --}}
        @canany(['viewAny_users', 'view_users'])
            <flux:sidebar.item
                icon="users"
                :href="route('users.index')"
                :current="request()->routeIs('users.*')"
                wire:navigate
            >
                {{ __('Users') }}
            </flux:sidebar.item>
        @endcanany

         @if(auth()->user()->hasRole('Superadmin|Admin'))
                @canany(['viewAny_roles', 'view_roles'])
            <flux:sidebar.item
                icon="shield-check"
                :href="route('roles.index')"
                :current="request()->routeIs('roles.*')"
                wire:navigate
            >
                {{ __('Roles') }}
            </flux:sidebar.item>
            @endcanany
        @endif


          @if(auth()->user()->hasRole('Superadmin'))
          
             @canany(['viewAny_permissions', 'view_permissions'])
            <flux:sidebar.item
                icon="key"
                :href="route('permissions.index')"
                :current="request()->routeIs('permissions.*')"
                wire:navigate
            >
                {{ __('Permissions') }}
            </flux:sidebar.item>
            @endcanany
             @canany(['viewAny_companyinfo', 'view_companyinfo'])
            <flux:sidebar.item
                icon="key"
                :href="route('companyinfo.index')"
                :current="request()->routeIs('companyinfo.*')"
                wire:navigate
            >
                {{ __('Company Information') }}
            </flux:sidebar.item>
            @endcanany
        @endif
        
       

           

    </flux:sidebar.group>

    <x-menus.sidebar-menu />





</flux:sidebar.nav>



            <flux:spacer />

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->role }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>    

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Log out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist
       
        @fluxScripts

         <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>
</html>
