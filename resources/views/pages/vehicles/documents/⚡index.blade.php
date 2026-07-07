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


new #[Title('Vehicle Documents List')] class extends Component
{
    use WithPagination, WithFileUploads;


};
?>

<div>

<section class="w-full">
    
    <x-pages-header 
        title="Documents" 
        description="Manage Vehicle Documents"         
    >
        <x-slot:rightbutton>
            <flux:button wire:click="exportDocuments" icon="arrow-down-tray">
                Export
            </flux:button>
        </x-slot:rightbutton>

    </x-pages-header>



    </section>
        
</div>