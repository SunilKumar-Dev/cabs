<div>
  @props([
    'owners',
])

<div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
    {{-- Desktop Table View --}}
     <div class="verflow-x-auto" wire:poll.keep-alive.10s>
        <table class="min-w-full divide-y divide-zinc-200 table-fixed">
            {{-- TABLE HEAD --}}
            {{-- <thead class="bg-zinc-50"> --}}
            <thead class="bg-gray-200 dark:bg-gray-600/50">
                <tr>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Actions</span>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-700 dark:text-gray-600">
                       Name 
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-700 dark:text-gray-600">
                        Mobile
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-700 dark:text-gray-600">
                         ID Proof
                    </th>
                     <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-700 dark:text-gray-600">
                        Company Name
                    </th>
                     <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-700 dark:text-gray-600">
                        Relationship
                    </th>
                             
                </tr>
            </thead>



            {{-- TABLE BODY --}}
            <tbody class="divide-y divide-zinc-100 bg-white">

                        @forelse($owners as $owner)

                            <tr wire:key="owner-{{ $owner->id }}" class="hover:bg-zinc-50 transition">

                                {{-- ACTIONS --}}
                                <td class="px-6 py-4 text-right">

                                    <x-action-buttons
                                        :id="$owner->id"
                                        :trashed="$owner->trashed()"

                                        viewAction="viewOwner"
                                        editAction="editOwner"
                                        deleteAction="deleteOwner"
                                        restoreAction="restoreOwner"
                                        forceDeleteAction="forceDeleteOwner"

                                        viewPermission="view_owners"
                                        editPermission="edit_owners"
                                        deletePermission="delete_owners"
                                        restorePermission="restore_owners"
                                        forceDeletePermission="force_delete_owners"
                                    />

                                </td>
                                {{-- NAME --}}
                                <td class="px-6 py-4">
                                    <div class="font-medium">
                                    
                                        {{ ucwords($owner->name) }}
                                    
                                    </div>
                                </td>

                                {{-- MOBILE --}}
                                <td class="px-6 py-4 text-sm text-zinc-700">
                                    {{ $owner->mobile ?: '-' }}
                                </td>

                                {{-- AADHAAR --}}
                                <td class="px-6 py-4 text-sm text-zinc-700">
                                <div class="flex flex-col ">
                                    <span class="font-medium capitalize">
                                        {{ str_replace('_', ' ', $owner->id_proof) }}
                                    </span>

                                    <span class="text-xs text-zinc-500 uppercase">
                                        {{ $owner->id_proof_number }}
                                    </span>
                                </div>

                                    {{-- {{ $owner->id_proof ? $owner->id_proof . ' - ' . $owner->id_proof_number : '-' }} --}}
                                </td>

                                {{-- COMPANY NAME --}}
                                <td class="px-6 py-4 text-sm text-zinc-700">
                                    {{ $owner->company->company_name ?? '-' }}
                                </td>

                                {{-- RELATIONSHIP --}}
                                <td class="px-6 py-4 text-sm text-zinc-700">
                                    <flux:badge size="sm">
                                        {{ $owner->relationship_with_applicant ? ucwords($owner->relationship_with_applicant) : '-' }}
                                    </flux:badge>
                                
                                </td>

                            

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-sm text-zinc-500">

                                    <div class="flex flex-col items-center gap-3">

                                        <div class="text-4xl">
                                            🧑‍✈️
                                        </div>

                                        <div>
                                            No owners found.
                                        </div>

                                    </div>

                                </td>
                            </tr> 

                        @endforelse

            </tbody>
        </table>
    </div>
</div>

</div>