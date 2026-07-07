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
            'vehicles-brand.*',
            'vehicles-model.*',
            'vehicles-category.*',
            'vehicles-types.*',
            
        ]);
        
      

        $vehicleDocumentOpen = request()->routeIs([
            'vehicles-documents.*',
            'vehicles-insurance.*',
            'vehicles-permit.*',
            'vehicles-pucc.*',
            'vehicles-fitness.*',
            'vehicles-taxes.*',
            'vehicles-gpstracker.*',
            'vehicles-fuels.*',
        ]);

        $vehicleFinanceOpen = request()->routeIs([
            'vehicles-loans.*',
        ]);
        

        $vehicleInsurancePermitOpen = request()->routeIs([
            'vehicles-insurance.*',
            'vehicles-permit.*',
            'vehicles-pucc.*',
            'vehicles-fitness.*',
            'vehicles-taxes.*',
        ]);

        $vehicleMaintenanceFuelOpen = request()->routeIs([
            'vehicles-maintenance.*',
            'vehicles-brand.*',
            'vehicles-model.*',
            'vehicles-category.*',
            'vehicles-types.*',
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


        <flux:sidebar.group
            expandable
            heading="Vehicle Documents"
            icon="document-text"
            class="grid whitespace-nowrap"
            :expanded="$vehicleDocumentOpen"
        >

            {{-- Vehicle Documents --}}
            <flux:sidebar.item
                icon="document-text"
                :href="route('vehicles-documents.index')"
                :current="request()->routeIs('vehicles-documents.*')"
                wire:navigate
            >
                Vehicle Documents
            </flux:sidebar.item>

        </flux:sidebar.group>
           
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
                heading="Insurance & Permit"
                icon="shield-check"
                class="grid whitespace-nowrap"
                :expanded="$vehicleInsurancePermitOpen"
            >

            {{-- Vehicle Insurance --}}
            <flux:sidebar.item
                icon="shield-check"
                :href="route('vehicles-insurance.index')"
                :current="request()->routeIs('vehicles-insurance.*')"
                wire:navigate
            >
                Vehicle Insurance
            </flux:sidebar.item>

            {{-- Vehicle Permit --}}
            <flux:sidebar.item
                icon="identification"
                :href="route('vehicles-permit.index')"
                :current="request()->routeIs('vehicles-permit.*')"
                wire:navigate
            >
                Vehicle Permit
            </flux:sidebar.item>

            {{-- Vehicle PUCC --}}
            <flux:sidebar.item
                icon="clipboard-document-check"
                :href="route('vehicles-pucc.index')"
                :current="request()->routeIs('vehicles-pucc.*')"
                wire:navigate
            >
                Vehicle PUCC
            </flux:sidebar.item>

            {{-- Vehicle Fitness --}}
            <flux:sidebar.item
                icon="check-badge"
                :href="route('vehicles-fitness.index')"
                :current="request()->routeIs('vehicles-fitness.*')"
                wire:navigate
            >
                Vehicle Fitness
            </flux:sidebar.item>

            {{-- Vehicle Tax --}}
            <flux:sidebar.item
                icon="currency-rupee"
                :href="route('vehicles-taxes.index')"
                :current="request()->routeIs('vehicles-taxes.*')"
                wire:navigate
            >
                Vehicle Tax
            </flux:sidebar.item>

    </flux:sidebar.group>

            <flux:sidebar.group
                expandable
                heading="Maintenance"
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
                Vehicle Brand
            </flux:sidebar.item>

              <flux:sidebar.item
                icon="wrench-screwdriver"
                :href="route('vehicles-category.index')"
                :current="request()->routeIs('vehicles-category.*')"
                wire:navigate
            >
                Vehicle Category
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
                    Fleet Live Tracking
                </flux:sidebar.item>

                <flux:sidebar.item
                    icon="map"
                    :href="route('vehicles-gpstracker.index')"
                    :current="request()->routeIs('vehicles-gpstracker.*')"
                    wire:navigate
                >
                    Vehicle GPS Tracker
                </flux:sidebar.item>
    </flux:sidebar.group>
@endcanany