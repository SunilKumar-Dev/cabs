<?php

use Livewire\Component;

use App\Exports\VehiclesExport;
use App\Imports\VehiclesImport;
use App\Models\Driver;
use App\Models\DriverVehicleAssignment;
use App\Models\FuelType;
use App\Models\LoanAccount;
use App\Models\Owner;
use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Models\VehicleOwners;
use App\Models\VehicleBrand;
use App\Models\VehicleCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;


use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Gate;


new #[Title('Vehicle Modal List')] class extends Component
{
    use WithPagination, WithFileUploads;


};
?>

<div>

<section class="w-full">
    
    <x-pages-header 
        title="Vehicle Modal" 
        description="Manage Vehicle Modal"         
    >
        <x-slot:rightbutton>
            @can('create_modals')
                <flux:button wire:click="createModal" icon="plus" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg !bg-blue-600 hover:!bg-blue-700 px-4 py-2 text-sm font-medium !text-white">
                    Add Modal
                </flux:button>
            @endcan

            <flux:button wire:click="exportModals" icon="arrow-down-tray">
                Export
            </flux:button>
        </x-slot:rightbutton>

    </x-pages-header>



    </section>
        
</div>