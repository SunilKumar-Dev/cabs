<?php

use Livewire\Component;

use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Models\Company;
use App\Models\Owner;
use App\Models\RelationshipWithApplicant;
use App\Exports\VehicleOwnerExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

new #[Title('Vehicle Owners List')] class extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $showViewModal = false;

    public ?string $selectedCompany = null; 

    public $ownerId = null;
    public $selectedOwner = null;

    public $name, $mobile, $address;
    public $id_proof, $id_proof_number;
    public $relationship_with_applicant;

    public $status = 'active';

    public $owner_photo = null;
    public $id_proof_pdf = null;

    public array $companies = [];
    public $filteredOwners = [];


    public function exportOwner()
    {
        return Excel::download(
            new VehicleOwnerExport(), 
            'vehicle_owners.xlsx'
            );
    }

     public function getOwnersProperty()
    {

        $user = auth()->user();

        if ($user->hasRole('Superadmin')) {
           $companyUuid = session('active_company_uuid') ?? null;
        } else {
            $companyUuid = $user->companies?->company_uuid ?? session('active_company_uuid');
        }
        
       // dd($companyUuid);

       if ($companyUuid === null) {

            $query = Owner::query();

            if ($user->hasRole('Superadmin')) {
                $query->withTrashed();
            }

            return $query
                ->with('company', 'currentAssignment.vehicle')
                ->where('status', 'active')
                ->latest()
                ->get();
        }

       return Owner::with('company', 'currentAssignment.vehicle')
        ->when($companyUuid, function($query) use ($companyUuid) {
            return $query->where('company_uuid', $companyUuid);
        })
        ->where('status', 'active')
        ->latest()
        ->get();
    }

     protected function rules()
{

    return [
        'name' => [
            'required',
            'min:2',
            'max:35',
            'regex:/^[A-Za-z]+(?:\s[A-Za-z]+)*$/',
        ],

        'mobile' => [
            'required',
            'digits:10',
            'regex:/^[6-9]\d{9}$/',
            Rule::unique('owners', 'mobile')->ignore($this->ownerId),
        ],

        'address' => 'nullable|string',

        'id_proof' => 'required',

        'id_proof_number' => [
        'required',
        Rule::unique('owners', 'id_proof_number')->ignore($this->ownerId),
        ],

        'relationship_with_applicant' => [
            'required',
            'regex:/^[A-Za-z]+(?:\s[A-Za-z]+)*$/',
        ],

        'owner_photo' => 'nullable',
        'id_proof_pdf' => 'nullable',
    ];
}

    public function closeModal()
    {
        $this->resetValidation();
        $this->reset();
        $this->showModal = false;
    }



    // public function getCompaniesProperty()
    // {
    //     $user = auth()->user();

    //     if ($user->hasRole('Superadmin')) {
    //         return Company::all();
    //     }

    //     return Company::where('company_uuid', $user->companies?->company_uuid)->get();
    // }

    public function getRelationsProperty()
    {          
        return RelationshipWithApplicant::all();
    }



    /*
    |--------------------------------------------------------------------------
    | New Create Actions
    |--------------------------------------------------------------------------
    */
    public function createOwner()
    {
        if($this->selectedCompany == 'all') {
            session()->flash('error', 'Please select a company.');
            return;
        }
        // Backend security
        Gate::authorize('create_owners', Owner::class);
        
        $this->showModal = true;
    }

    
    /*
    |--------------------------------------------------------------------------
    | Save / Create Operations
    |--------------------------------------------------------------------------
    */

    public function createSave()
    {
        Gate::authorize('create_owners', Owner::class);
        $data = $this->validate();

        // 1. Determine Company UUID logic
        $user = auth()->user();
        
        if ($user->hasRole('Superadmin')) {
            // SuperAdmin ke case mein $this->company_uuid (input field) se lein
            // Ensure kijiye ki aapne form mein 'company_uuid' field binding di hai
            $data['company_uuid'] = session('active_company_uuid') ?? null;
        } else {
            // Normal Admin ke case mein user relation ya session se lein
            $data['company_uuid'] = $user->companies?->company_uuid ?? session('active_company_uuid');
        }

        // 2. Validation Check
        if (empty($data['company_uuid'])) {
            session()->flash('error', 'Please select a valid company.');
            $this->closeModal();
            return;
        }

        // 3. Metadata assignment
        $data['created_by_uuid'] = $user->uuid;
        $data['uuid'] = (string) Str::uuid();

        // dd($data);

        // 4. Save
        Owner::create($data);
        
        session()->flash('success', 'Owner created successfully.');
        $this->closeModal();
    }


  

     public function editOwner($id)
    {
        Gate::authorize('edit_owners', Owner::class);
        $owner = Owner::findOrFail($id);
      
        $this->ownerId = $id;
        $this->name = $owner->name;
        $this->mobile = $owner->mobile;
        $this->address = $owner->address;

        $this->id_proof = $owner->id_proof;
        $this->id_proof_number = $owner->id_proof_number;

        $this->relationship_with_applicant = $owner->relationship_with_applicant;

        $this->owner_photo = $owner->owner_photo;
        $this->id_proof_pdf = $owner->id_proof_pdf;

        $this->status = $owner->status; 
        $this->showModal = true;
    }


    
    public function updateSave()
    {
        Gate::authorize('update_owners', Owner::class);

        $owner = Owner::findOrFail($this->ownerId);
        $data = $this->validate();

        if ($this->owner_photo instanceof \Illuminate\Http\UploadedFile) {
            if ($owner->owner_photo) Storage::disk('public')->delete($owner->owner_photo);
            $data['owner_photo'] = $this->owner_photo->store('owners', 'public');
        }

        if ($this->id_proof_pdf instanceof \Illuminate\Http\UploadedFile) {
            if ($owner->id_proof_pdf) Storage::disk('public')->delete($owner->id_proof_pdf);
            $data['id_proof_pdf'] = $this->id_proof_pdf->store('owners', 'public');
        }

 
        // $data['uuid'] = $owner->uuid ?? (string) str()->uuid();
        $data['updated_by_uuid'] = auth()->user()->uuid;

        // dd($data);

        $owner->update($data);
        session()->flash('success', 'Owner updated successfully.');
        $this->closeModal();
    }


    
    public function viewOwner($id)
    {
        Gate::authorize('view_owners', Owner::class);

        $this->selectedOwner =
            Owner::withTrashed()->findOrFail($id);

        $this->showViewModal = true;
    }

    public function deleteOwner($id)
    {
        Gate::authorize('delete_owners', Owner::class);

        Owner::findOrFail($id)->delete();

        session()->flash(
            'success',
            'Owner deleted successfully.'
        );
    }

    public function restoreOwner($id)
    {
        Gate::authorize('restore_owners', Owner::class);
        
        Owner::onlyTrashed()
            ->findOrFail($id)
            ->restore();

        session()->flash(
            'success',
            'Owner restored successfully.'
        );
    }

    public function forceDeleteOwner($id)
    {
        Gate::authorize('forceDelete_owners', Owner::class);
        
        Owner::withTrashed()
            ->findOrFail($id)
            ->forceDelete();

        session()->flash(
            'success',
            'Owner permanently deleted successfully.'
        );
    }

};

?>

<div>

<section class="w-full">
    
    <x-pages-header 
        title="Owners" 
        description="Manage Vehicle Owners"         
    >
        <x-slot:rightbutton>
            @can('create_owners')
                <flux:button wire:click="createOwner" icon="plus" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg !bg-blue-600 hover:!bg-blue-700 px-4 py-2 text-sm font-medium !text-white">
                    Add Owner
                </flux:button>
            @endcan

            <flux:button wire:click="exportOwner" icon="arrow-down-tray">
                Export
            </flux:button>
        </x-slot:rightbutton>

    </x-pages-header>

     <x-flash-message />

   <x-owner-form-component 
        :showModal="$showModal" 
        :ownerId="$ownerId" 
        :name="$name" 
        :mobile="$mobile" 
        :address="$address" 
        :id_proof="$id_proof" 
        :id_proof_number="$id_proof_number" 
        :relationship_with_applicant="$relationship_with_applicant" 
        :owner_photo="$owner_photo" 
        :id_proof_pdf="$id_proof_pdf" 
        :companies="$this->companies" 
        :relations="$this->relations"  />

    </section>
        
</div>