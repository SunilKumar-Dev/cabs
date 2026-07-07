@props([
'vehicles',
'vehicleCounts',
'vehicleTypes',
'financeCounts'
])

<div class="bg-white border rounded-2xl p-5 shadow-sm mb-6">
        {{-- FILTERS CARD --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <flux:input wire:model.live.debounce.500ms="search" label="Vehicle Search"
                placeholder="Search reg no, chassis, owner..."
                icon="magnifying-glass"
            />

            <flux:select wire:model.live="filter_vehicle_type" label="Vehicle Type">
                        <option value="">Select</option>
                       @foreach ($vehicleTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                       @endforeach
                    </flux:select>

            <flux:select wire:model.live="filter_status" label="Vehicle Status">
                <option value="">All Status</option>
                <option>Active</option>
                <option>Inactive</option>

            </flux:select>

             <flux:select wire:model.live="filter_free_fin" label="Loan Status">
                <option value="">Select</option>
                <option value="free">Free</option>
                <option value="fin">Financed</option>
            </flux:select>

               

        </div>
</div>




    {{-- Dashboard Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

        <div class="bg-white border rounded-2xl p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Vehicles</p>
            <h2 class="text-3xl font-bold">
            {{ $this->totalVehicles }}
            </h2>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-2xl p-5">
            <p class="text-sm text-green-700">Active</p>
            <h2 class="text-3xl font-bold text-green-700">
                {{ $vehicles->where('status','Active')->count() }} / {{ $this->ActiveVehicles }}
            </h2>
        </div>

        <div class="bg-red-50 border border-red-200 rounded-2xl p-5">
            <p class="text-sm text-red-700">Inactive</p>
            <h2 class="text-3xl font-bold text-red-700">
                {{ $vehicles->where('status','Inactive')->count() }} / {{ $this->InactiveVehicles }}
            </h2>
        </div>

     
        
        <div class="bg-purpal-50 border border-purpal-200 rounded-2xl p-5">
            <p class="text-sm text-purpal-700">Loaned</p>
            <h2 class="text-3xl font-bold text-purpal-700">
                {{ $vehicles->where('free_fin','fin')->count() }} / {{ $this->FinancedVehicles }}
            </h2>
        </div>


    </div>
