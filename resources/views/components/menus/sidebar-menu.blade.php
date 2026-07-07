    @php
        $vehicleMenuOpen = request()->routeIs([
            'vehicles-documents.*',
            'vehicles-loans.*',
            'vehicles-insurance.*',
            'vehicles-permit.*',
            'vehicles-pucc.*',
            'vehicles-fitness.*',
            'vehicles-taxes.*',
            'vehicles-gpstracker.*',
            'vehicles-maintenance.*',
            'vehicles-fuels.*',
            'vehicles-fleet.*',
            'vehicles-gpstracker.*',
            'vehicles-driver.*',
            'vehicles-owner.*',
            'vehicles-dashboard.*',
            'vehicles-category.*',
            'vehicles-types.*',
            'vehicles-brand.*',
            'vehicles-modal.*',
            'vehicles-variant.*',
        ]);
        
      


        $vehicleFinanceOpen = request()->routeIs([
            'vehicles-loans.*',
        ]);
        

        $vehicleMaintenanceFuelOpen = request()->routeIs([
            'vehicles-brand.*',
            'vehicles-modal.*',
            'vehicles-variant.*',
        ]);

        $vehicleFuelManagementOpen = request()->routeIs([
            'vehicles-fuels.*',
        ]);

        $vehicleFleetOpen = request()->routeIs([
            'vehicles-fleet.*',
            'vehicles-gpstracker.*',
        ]);
    @endphp


@canany(['viewAny_vehicles', 'view_vehicles'])
        <flux:sidebar.group
            expandable
            heading="Vehicle Management"
            icon="truck"
            class="grid whitespace-nowrap"
            :expanded="$vehicleMenuOpen"
        >

        {{-- Vehicle Dashboard --}}
        <flux:sidebar.item
            icon="truck"
            :href="route('vehicles-dashboard.index')"
            :current="request()->routeIs('vehicles-dashboard.*')"
            wire:navigate
        >
            Vehicle Dashboard
        </flux:sidebar.item>

        {{-- Vehicle Owner --}}
        <flux:sidebar.item
            icon="user"
            :href="route('vehicles-owner.index')"
            :current="request()->routeIs('vehicles-owner.*')"
            wire:navigate
        >
            Vehicle Owner
        </flux:sidebar.item>

        {{-- Vehicle Driver --}}
        <flux:sidebar.item
            icon="users"
            :href="route('vehicles-driver.index')"
            :current="request()->routeIs('vehicles-driver.*')"
            wire:navigate
        >
            Vehicle Driver
        </flux:sidebar.item>

        
            {{-- Vehicle Documents --}}
            <flux:sidebar.item
                icon="document-text"
                :href="route('vehicles-documents.index')"
                :current="request()->routeIs('vehicles-documents.*')"
                wire:navigate
            >
                Vehicle Documents
            </flux:sidebar.item>



    
         <flux:sidebar.group
            expandable
            heading="Vehicle Finance"
            icon="banknotes"
            class="grid whitespace-nowrap"
            :expanded="$vehicleFinanceOpen"
        >
                {{-- Vehicle Loan --}}
                <flux:sidebar.item
                    icon="banknotes"
                    :href="route('vehicles-loans.index')"
                    :current="request()->routeIs('vehicles-loans.*')"
                    wire:navigate
                >
                    Vehicle Loan
                </flux:sidebar.item>

        </flux:sidebar.group>

 

            <flux:sidebar.group
                expandable
                heading="Vehicle Info"
                icon="wrench-screwdriver"
                class="grid whitespace-nowrap"
                :expanded="$vehicleMaintenanceFuelOpen"
            >

            {{-- Vehicle Maintenance --}}
            <flux:sidebar.item
                icon="wrench-screwdriver"
                :href="route('vehicles-brand.index')"
                :current="request()->routeIs('vehicles-brand.*')"
                wire:navigate
            >
                Make / Brand
            </flux:sidebar.item>

              <flux:sidebar.item
                icon="wrench-screwdriver"
                :href="route('vehicles-modal.index')"
                :current="request()->routeIs('vehicles-modal.*')"
                wire:navigate
            >
                Vehicle Modal
            </flux:sidebar.item>

            
              <flux:sidebar.item
                icon="wrench-screwdriver"
                :href="route('vehicles-variant.index')"
                :current="request()->routeIs('vehicles-variant.*')" 
                wire:navigate
            >
                Model Variant
            </flux:sidebar.item>
            

        </flux:sidebar.group>

         <flux:sidebar.group
                expandable
                heading="Fuel Management"
                icon="fire"
                class="grid whitespace-nowrap"
                :expanded="$vehicleFuelManagementOpen"
            >
              {{-- Vehicle Fuel --}}
            <flux:sidebar.item
                icon="fire"
                :href="route('vehicles-fuels.index')"
                :current="request()->routeIs('vehicles-fuels.*')"
                wire:navigate
            >
                Vehicle Fuel
            </flux:sidebar.item>

        </flux:sidebar.group>

            <flux:sidebar.group
                    expandable
                    heading="Fleet Management"
                    icon="wrench-screwdriver"
                    class="grid whitespace-nowrap"
                    :expanded="$vehicleFleetOpen"
                >
                    {{-- Fleet Management --}}
                <flux:sidebar.item
                        icon="map"
                        :href="route('vehicles-fleet.index')"
                        :current="request()->routeIs('vehicles-fleet.*')"
                        wire:navigate
                >
                    Live Tracking
                </flux:sidebar.item>

    </flux:sidebar.group>
@endcanany