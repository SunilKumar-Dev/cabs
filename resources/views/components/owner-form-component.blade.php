<div>
<x-flux::modal
    wire:model="showModal"
    size="3xl"
    class="!p-0 overflow-hidden rounded-2xl shadow-2xl flex flex-col max-h-[90vh]"
>

    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6 text-white shrink-0">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-white/20 flex items-center justify-center">
                🏢
            </div>

            <div>
                <h2 class="text-3xl font-bold">
                    {{ $ownerId ? 'Edit Owner' : 'Add Owner' }}
                </h2>
                <p class="text-blue-100 text-sm">
                    {{ $ownerId ? 'Update Owner details' : 'Create new Owner' }}
                </p>
            </div>
        </div>
    </div>

    {{-- FORM --}}
    <form
        wire:submit.prevent="{{ $ownerId ? 'updateSave' : 'createSave' }}"
        class="flex flex-col flex-1 min-h-0"
    >

        {{-- BODY --}}
        <div class="p-8 flex-1 overflow-y-auto space-y-8">

            {{-- OWNER DETAILS --}}
            <div>
                <h3 class="text-lg font-semibold border-b pb-2 mb-4">
                    👤 Owner Details
                </h3>

                <div class="grid grid-cols-2 gap-6">


                    {{-- Name --}}
                    <flux:input wire:model="name" label="Owner Name" />

                    {{-- Mobile --}}
                    <flux:input wire:model="mobile" label="Owner Mobile" mask="9999999999" max="10"/>

                     {{-- Relationship With Applicant --}}  
                    <flux:select wire:model="relationship_with_applicant" label="Relationship With Applicant">
                        <option value="">Select Relationship</option>
                        @foreach($relations as $relation)
                            <option value="{{ $relation->name }}">{{ $relation->name }}</option>
                        @endforeach
                    </flux:select>

                    {{-- Address --}}
                    <div class="col-span-2">
                        <flux:textarea wire:model="address" label="Owner Address" />
                    </div>

                    {{-- Uploaders --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">Owner Photo</label>
                        <x-owner-file-uploader type="owner_photo" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">ID Proof File</label>
                        <x-owner-file-uploader type="id_proof_pdf" />
                    </div>
 <div>
                    {{-- ID Proof Type --}}
                    <flux:select wire:model.live="id_proof" label="ID Proof" class="col-span-2">
                        <option value="">Select ID Proof</option>
                        <option value="aadhar">Aadhaar Card</option>
                        <option value="pan">PAN Card</option>
                        <option value="voter_id">Voter ID</option>
                        <option value="passport">Passport</option>
                        <option value="driving_license">Driving License</option>
                    </flux:select>
</div>
<div wire:key="id-proof-{{ $id_proof }}">
                    {{-- Dynamic ID Number --}}
                     @if($id_proof === 'aadhar')
                                    <flux:input
                                        wire:model.live="id_proof_number"
                                        label="Aadhaar Number (1234-5678-9012)"
                                        placeholder="1234-5678-9012"
                                        mask="9999-9999-9999"
                                        maxlength="14"
                                    />
                                @endif

                                @if($id_proof === 'pan')
                                    <flux:input
                                        wire:model.live="id_proof_number"
                                        x-on:input="$el.value = $el.value.toUpperCase()"
                                        label="PAN Number (e.g. ABCDE1234F)"
                                        placeholder="ABCDE1234F"
                                        mask="aaaaa9999a"
                                        maxlength="10"
                                    />
                                @endif

                                @if($id_proof === 'voter_id')
                                    <flux:input
                                        wire:model.live="id_proof_number"
                                        x-on:input="$el.value = $el.value.toUpperCase()"
                                        label="Voter ID Number (e.g. ABC1234567)"
                                        placeholder="ABC1234567"
                                        mask="aaa9999999"
                                        maxlength="10"
                                    />
                                @endif

                                @if($id_proof === 'passport')
                                    <flux:input
                                        wire:model.live="id_proof_number"
                                        x-on:input="$el.value = $el.value.toUpperCase()"
                                        label="Passport Number (e.g. A1234567)"
                                        placeholder="A1234567"
                                        mask="a9999999"
                                        maxlength="8"
                                    />
                                @endif

                                @if($id_proof === 'driving_license')
                                    <flux:input
                                        wire:model.live="id_proof_number"
                                        x-on:input="$el.value = $el.value.toUpperCase()"
                                        label="Driving License Number (e.g. UP0120230001234)"
                                        placeholder="UP0120230001234"
                                        mask="aa9999999999999"
                                        maxlength="15"
                                    />
                                @endif

</div>
                    <div>
                    {{-- Status --}}
                    <flux:select wire:model="status" label="Status" class="col-span-2">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </flux:select>
                    </div>

                </div>
            </div>

        </div>

        {{-- FOOTER --}}
        <div class="shrink-0 bg-white border-t px-8 py-4 flex justify-end gap-3">

            <flux:button
                type="button"
                wire:click="closeModal"
                variant="ghost"
            >
                Cancel
            </flux:button>

            <flux:button
                type="submit"
                variant="primary"
                color="blue"
            >
                {{ $ownerId ? 'Update Owner' : 'Save Owner' }}
            </flux:button>

        </div>

    </form>

</x-flux::modal>

</div>