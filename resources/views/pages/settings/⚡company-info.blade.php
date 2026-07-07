<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

new #[Title('Company Information')] class extends Component 
{
    use WithFileUploads;

    public bool $showModal = false;
    public ?int $companyId = null;

    // Form Fields
    public string $company_name = '';
    public string $company_slug = '';
    public $company_description = 'Supplying expert manpower, managing comfortable cabs, and dispatching rapid ambulance transport.';
    public string $company_pan_no = '';
    public string $company_gst_no = '';
    public string $company_registration_no = '';
    public string $company_email = '';
    public string $company_phone = '';
    public string $company_website = '';
    public string $company_address_line_1 = '';
    public string $company_address_line_2 = '';
    public string $company_city = '';
    public string $company_state = '';
    public string $company_country = '';
    public string $company_postal_code = '';

    // File Uploads
    public $company_logo = null;
    public ?string $company_logo_old = null;
    public $company_favicon = null;
    public ?string $company_favicon_old = null;
  
    public function mount(): void
    {
        $this->authorize('viewAny', Company::class);
    }

    /**
     * Computed Property: Access in Blade via $this->companies
     */
    public function getCompaniesProperty()
    {
        return Company::withTrashed()
            ->with('creator')
            ->latest()
            ->get();
    }

    public function updatedCompanyName(): void
    {
        $this->company_slug = Str::slug($this->company_name);
    }

    public function addCompany(): void
    {
        $this->resetValidation();
        $this->resetErrorBag();
        $this->authorize('create', Company::class);
        
        $this->resetFormFields();
        $this->companyId = null;
        $this->showModal = true;
    }

    public function editCompany(int $id): void
    {
        $this->resetValidation();
        $this->resetErrorBag();
        
        $company = Company::findOrFail($id);
        $this->authorize('update', $company);

        $this->companyId = $company->id;
        $this->company_name = $company->company_name;
        $this->company_slug = $company->company_slug;
        $this->company_description = $company->company_description ?? '';
        $this->company_pan_no = $company->company_pan_no ?? '';
        $this->company_gst_no = $company->company_gst_no ?? '';
        $this->company_registration_no = $company->company_registration_no ?? '';
        $this->company_email = $company->company_email;
        $this->company_phone = $company->company_phone;
        $this->company_website = $company->company_website ?? '';
        $this->company_address_line_1 = $company->company_address_line_1;
        $this->company_address_line_2 = $company->company_address_line_2 ?? '';
        $this->company_city = $company->company_city;
        $this->company_state = $company->company_state;
        $this->company_country = $company->company_country;
        $this->company_postal_code = $company->company_postal_code;

        $this->company_logo = null;
        $this->company_favicon = null;
        $this->company_logo_old = $company->company_logo;
        $this->company_favicon_old = $company->company_favicon;

        $this->showModal = true;
    }

    protected function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255', Rule::unique('companies', 'company_name')->ignore($this->companyId)],
            'company_slug' => ['required', 'string', 'max:255', Rule::unique('companies', 'company_slug')->ignore($this->companyId)],
            'company_description' => ['nullable', 'string'],
            'company_pan_no' => ['nullable', 'string', 'max:50', 'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/', Rule::unique('companies', 'company_pan_no')->ignore($this->companyId)],
            'company_gst_no' => ['nullable', 'string', 'max:50', 'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[A-Z0-9]{1}Z[A-Z0-9]{1}$/', Rule::unique('companies', 'company_gst_no')->ignore($this->companyId)],
            'company_registration_no' => ['nullable', 'string', 'max:100', 'regex:/^[A-Z]{1}[0-9]{5}[A-Z]{2}[0-9]{4}[A-Z]{3}[0-9]{6}$/', Rule::unique('companies', 'company_registration_no')->ignore($this->companyId)],
            'company_email' => ['required', 'email', 'max:255', Rule::unique('companies', 'company_email')->ignore($this->companyId)],
            'company_phone' => ['required', 'string', 'max:20', 'regex:/^\+?[0-9\s\-]{7,20}$/', Rule::unique('companies', 'company_phone')->ignore($this->companyId)],
            'company_website' => ['nullable', 'url', 'max:255'],
            'company_address_line_1' => ['required', 'string', 'max:255'],
            'company_address_line_2' => ['nullable', 'string', 'max:255'],
            'company_city' => ['required', 'string', 'max:100'],
            'company_state' => ['required', 'string', 'max:100'],
            'company_country' => ['required', 'string', 'max:100'],
            'company_postal_code' => ['required', 'string', 'max:20', 'regex:/^[0-9A-Za-z\s\-]{4,20}$/'],
            'company_logo' => ['nullable', 'sometimes', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:1024'],
            'company_favicon' => ['nullable', 'sometimes', 'image', 'mimes:png,ico', 'max:20', 'dimensions:max_width=64,max_height=64'],
        ];
    }

    public function saveCompany(): void
    {
        if ($this->company_website && !Str::startsWith($this->company_website, ['http://', 'https://'])) {
            $this->company_website = 'https://' . $this->company_website;
        }

        $this->validate();

        $existingCompany = $this->companyId ? Company::find($this->companyId) : null;

        // Image upload storage management
        $logoPath = $this->company_logo_old;
        if ($this->company_logo instanceof TemporaryUploadedFile) {
            if ($this->company_logo_old) {
                Storage::disk('public')->delete($this->company_logo_old);
            }
            $logoPath = $this->company_logo->store('logos', 'public');
        }

        $faviconPath = $this->company_favicon_old;
        if ($this->company_favicon instanceof TemporaryUploadedFile) {
            if ($this->company_favicon_old) {
                Storage::disk('public')->delete($this->company_favicon_old);
            }
            $faviconPath = $this->company_favicon->store('favicons', 'public');
        }

        Company::updateOrCreate(
            ['id' => $this->companyId],
            [
                'company_uuid' => $existingCompany?->company_uuid ?? (string) Str::uuid(),
                'company_name' => trim($this->company_name),
                'company_slug' => trim($this->company_slug),
                'company_description' => trim($this->company_description),
                'company_pan_no' => strtoupper(trim($this->company_pan_no)),
                'company_gst_no' => strtoupper(trim($this->company_gst_no)),
                'company_registration_no' => strtoupper(trim($this->company_registration_no)),
                'company_email' => trim($this->company_email),
                'company_phone' => trim($this->company_phone),
                'company_website' => trim($this->company_website),
                'company_address_line_1' => trim($this->company_address_line_1),
                'company_address_line_2' => trim($this->company_address_line_2),
                'company_city' => trim($this->company_city),
                'company_state' => trim($this->company_state),
                'company_country' => trim($this->company_country),
                'company_postal_code' => trim($this->company_postal_code),
                'company_logo' => $logoPath,
                'company_favicon' => $faviconPath,
                'created_by_uuid' => $existingCompany?->created_by_uuid ?? auth()->user()->uuid,
                'status' => 1,
            ]
        );

        session()->flash('success', 'Company saved successfully');
        $this->resetFormFields();
        $this->showModal = false;
    }

    public function deleteCompany(int $id): void
    {
        $company = Company::findOrFail($id);            
        $this->authorize('delete', $company);   
        
        $company->delete();

        User::where('company_uuid', $company->company_uuid)
            ->update([
                'status' => 0,
                'deleted_at' => now()
            ]);

        session()->flash('success', 'Company deleted successfully');
    }

    public function confirmDelete(int $id): void
    {
        $company = Company::findOrFail($id);            
        $this->authorize('delete', $company);   
        
        $company->delete();

        User::where('company_uuid', $company->company_uuid)
            ->update([
                'status' => 0,
                'deleted_at' => now()
            ]);

        session()->flash('success', 'Company deleted successfully');
    }

    public function restoreCompany(int $id): void
    {
        $company = Company::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $company);

        $company->restore();

        User::withTrashed()
            ->where('company_uuid', $company->company_uuid)
            ->each(function ($user) {
                $user->update(['status' => 1]);
                $user->restore();
            });

        session()->flash('success', 'Company restored successfully');
    }

    public function forceDelCompany(int $id): void
    {
        $company = Company::withTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $company);   
        
        if ($company->company_logo) Storage::disk('public')->delete($company->company_logo);
        if ($company->company_favicon) Storage::disk('public')->delete($company->company_favicon);

        $company->forceDelete();

        session()->flash('success', 'Company permanently deleted successfully');
    }

    public function toggleStatus(int $id): void
    {
        if (!auth()->user()->hasRole('Superadmin')) {
            abort(403, 'Unauthorized action.');
        }

        $company = Company::findOrFail($id);
        $company->status = !$company->status;
        $company->save();

        session()->flash('success', $company->status ? 'Company activated successfully.' : 'Company hidden successfully.');
    }

    private function resetFormFields(): void
    {
        $this->reset([
            'company_name', 'company_slug', 'company_description',
            'company_pan_no', 'company_gst_no', 'company_registration_no',
            'company_email', 'company_phone', 'company_website',
            'company_address_line_1', 'company_address_line_2',
            'company_city', 'company_state', 'company_country', 'company_postal_code',
            'company_logo', 'company_logo_old', 'company_favicon', 'company_favicon_old'
        ]);
    }

    public function messages(): array
    {
        return [
            'company_name.required' => 'Company name is required.',
            'company_pan_no.regex' => 'Enter a valid PAN number (ABCDE1234F).',
            'company_gst_no.regex' => 'Enter a valid GST number (22AAAAA0000A1Z5).',
            'company_registration_no.regex' => 'Enter a valid company registration number (CIN).',
            'company_favicon.dimensions' => 'Favicon dimensions must not exceed 64x64 pixels.',
        ];
    }
};


?>


<div>

<section class="w-full">
        
        
        
        <!-- Header Section -->

        <div class="relative mb-6 w-full">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-star lg:justify-between">
                <!-- LEFT -->
                <div class="min-w-0">
                    <flux:heading size="xl" level="1">
                     Company Management
                    </flux:heading>

                    <flux:subheading size="lg" class="mt-1">
                       Manage your company details and business settings
                    </flux:subheading>
                </div>
                
                <!-- RIGHT -->
                <div class="w-full lg:w-auto">

                    <div>
                        @if (auth()->user()->hasRole('Superadmin'))
                           
                            <flux:button
                                wire:click="addCompany"
                                variant="primary"
                                color="blue"
                                icon:trailing="plus"
                            >
                                Create Company
                            </flux:button>

                        @else

                            <flux:button
                                variant="primary"
                                color="gray"
                                icon:trailing="plus"
                                disabled
                            >
                                Create Company
                            </flux:button>
                        @endif
                        
                    </div>

                     


                </div>

            </div>
            <flux:separator variant="subtle" class="mt-4" />
        </div>


    <!-- flash message -->
    <x-flash-message />

    <!-- Table -->

<x-company-table-component :companies="$this->companies" />

    <!-- Modal -->



<x-flux::modal 
    wire:model="showModal"
    size="3xl"
    class="!p-0 overflow-hidden rounded-2xl shadow-2xl"
>

    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6 text-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-white/20 flex items-center justify-center">
                    🏢
                </div>

                <div>
                    <h2 class="text-3xl font-bold">
                        {{ $companyId ? 'Edit Company' : 'Create Company' }}
                    </h2>
                    <p class="text-blue-100 text-sm">
                        Manage your organization and access controls
                    </p>
                </div>
            </div>
        </div>
    </div>


    <!-- Body -->
    <form wire:submit.prevent="saveCompany" enctype="multipart/form-data">

        <div class="p-8 max-h-[75vh] overflow-y-auto space-y-6">

            <div class="grid grid-cols-2 gap-6">

                <!-- Company Name -->
                <div class="col-span-2">
                    <flux:input
                        wire:model.live="company_name"
                        label="Company Name"
                        placeholder="Enter company name"
                    />
                </div>

                <!-- Slug -->
                <div class="col-span-2">
                    <flux:input
                        wire:model="company_slug"
                        label="Company Slug"
                        readonly
                        placeholder="Auto generated"
                    />
                </div>

                <!-- Description -->
             <div class="col-span-2">
                    <flux:textarea
                        wire:model="company_description"
                        label="Description"
                        rows="4"
                        placeholder="Supplying expert manpower, managing comfortable cabs, and dispatching rapid ambulance transport."
                    />
                </div>

                <!-- PAN -->
                <div>
                    <flux:input
                        wire:model="company_pan_no"
                        label="PAN Number"
                        placeholder="ABCDE1234F"
                    />
                </div>

                <!-- GST -->
                <div>
                    <flux:input
                        wire:model="company_gst_no"
                        label="GST Number"
                        placeholder="22ABCDE1234F1Z5"
                    />
                </div>

                <!-- Registration -->
                <div class="col-span-2">
                    <flux:input
                        wire:model="company_registration_no"
                        label="Registration Number"
                        placeholder="L12345AB1234CDE123456"
                    />
                </div>

                <!-- Email -->
                <div>
                    <flux:input
                        wire:model="company_email"
                        label="Company Email"
                        type="email"
                        placeholder="company@email.com"
                    />
                </div>

                <!-- Phone -->
                <div>
                    <flux:input
                        wire:model="company_phone"
                        label="Company Phone"
                        placeholder="+91 9876543210"
                    />
                </div>

                <!-- Website -->
                <div class="col-span-2">
                    <flux:input
                        wire:model="company_website"
                        label="Website"
                        placeholder="https://example.com"
                    />
                </div>

                <!-- Address -->
                <div class="col-span-2">
                    <flux:input
                        wire:model="company_address_line_1"
                        label="Address Line 1"
                    />
                </div>

                <div class="col-span-2">
                    <flux:input
                        wire:model="company_address_line_2"
                        label="Address Line 2"
                    />
                </div>

                <!-- City -->
                <div>
                    <flux:input wire:model="company_city" label="City" />
                </div>

                <!-- State -->
                <div>
                    <flux:input wire:model="company_state" label="State" />
                </div>

                <!-- Country -->
                <div>
                    <flux:input wire:model="company_country" label="Country" />
                </div>

                <!-- Postal -->
                <div>
                    <flux:input wire:model="company_postal_code" label="Postal Code" />
                </div>


                <!-- Logo Upload -->
                <div class="col-span-2 bg-gray-50 rounded-xl p-5 border">



                    <label
                        for="companyLogo"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg cursor-pointer hover:bg-blue-700"
                    >
                        📁 Upload Company Logo
                    </label>

                    <input
                        id="companyLogo"
                        type="file"
                        wire:model="company_logo"
                        accept=".jpg,.jpeg,.png"
                        class="hidden"
                    />


                    <div wire:loading wire:target="company_logo"
                        class="mt-3 text-blue-600 text-sm">
                        Uploading logo...
                    </div>

                    <div class="mt-4">
                        @if ($company_logo instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                            <img src="{{ $company_logo->temporaryUrl() }}"
                                class="w-20 h-20 rounded-xl border shadow">
                        @elseif ($company_logo_old)
                            <img src="{{ asset('storage/'.$company_logo_old) }}"
                                class="w-20 h-20 rounded-xl border shadow">
                        @endif
                    </div>




                </div>


                <!-- Favicon Upload -->
                <div class="col-span-2 bg-gray-50 rounded-xl p-5 border">
                     <label
                        for="companyFavicon"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg cursor-pointer hover:bg-blue-700"
                    >
                        📁 Upload Company Favicon
                    </label>

                    <input
                        id="companyFavicon"
                        type="file"
                        wire:model="company_favicon"
                        accept=".jpg,.jpeg,.png"
                        class="hidden"
                    />


                    <div wire:loading wire:target="company_favicon"
                        class="mt-3 text-blue-600 text-sm">
                        Uploading favicon...
                    </div>

                    <div class="mt-4">
                        @if ($company_favicon instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                            <img src="{{ $company_favicon->temporaryUrl() }}"
                                class="w-16 h-16 rounded-md border shadow">
                        @elseif ($company_favicon_old)
                            <img src="{{ asset('storage/'.$company_favicon_old) }}"
                                class="w-16 h-16 rounded-md border shadow">
                        @endif
                    </div>
                </div>

            </div>
        </div>


        <!-- Sticky Footer -->
        <div class="sticky bottom-0 bg-white border-t px-8 py-4 flex justify-end gap-3">

            <flux:button
                type="button"
                wire:click="$set('showModal', false)"
                variant="ghost"
            >
                Cancel
            </flux:button>

            <flux:button
                type="submit"
                variant="primary"
                color="blue"
                wire:loading.attr="disabled"
                wire:target="saveCompany"
            >
                {{ $companyId ? 'Update Company' : 'Create Company' }}
            </flux:button>

        </div>

    </form>

</x-flux::modal>



</section>

</div>