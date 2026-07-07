<div>
@props(['showModal', 'companyId', 'company_name',  'company_slug', 'company_description', 'company_pan_no', 'company_gst_no', 'company_registration_no', 'company_email', 'company_phone', 'company_website', 'company_address_line_1', 'company_address_line_2', 'company_city', 'company_state', 'company_country', 'company_postal_code', 'company_logo', 'company_logo_old', 'company_favicon', 'company_favicon_old'])

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
                        placeholder="Brief company description..."
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

</div>