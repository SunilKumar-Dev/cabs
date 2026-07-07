@props([
    'companies'
])
<div wire:poll.5s>


    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-300 dark:bg-gray-700">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-600 uppercase tracking-wider">
                    Actions
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-600 uppercase tracking-wider">
                    Company Name
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-600 uppercase tracking-wider">
                    Contact
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-600 uppercase tracking-wider">
                    Address
                </th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-600 uppercase tracking-wider">
                    Logo
                </th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-600 uppercase tracking-wider">
                    Favicon
                </th>
            </tr>
        </thead>

       <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($this->companies as $company)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <!-- Actions -->
                   {{-- <td class="px-6 py-4 whitespace-nowrap">
                         <x-action-buttons
                            :id="$company->id"
                            :trashed="$company->trashed()"
                            :viewAction="null"
                            :editAction="'editCompany'"
                            :deleteAction="'deleteCompany'"
                            :restoreAction="'restoreCompany'"
                            :forceDeleteAction="'forceDelCompany'"
                            />
                    </td> --}}


                     @php
                                    $hasTrashed = $company->trashed();
                                  @endphp

 <td class="px-6 py-4 whitespace-nowrap align-top">
                                    <div class="flex space-x-2">

                                            @if($company->trashed())
                                                <flux:button 
                                                    wire:click="restoreCompany('{{ $company->id }}')"
                                                    wire:loading.attr="disabled"
                                                    wire:target="restoreCompany('{{ $company->id }}')"
                                                    size="sm"
                                                    variant="primary"
                                                    color="green"
                                                    icon="arrow-path"
                                                    cursor="pointer"
                                                    title="Restore Company"
                                                />

                                                <flux:button 
                                                    wire:click="forceDelCompany('{{ $company->id }}')"
                                                    wire:loading.attr="disabled"
                                                    wire:target="forceDelCompany('{{ $company->id }}')"
                                                    wire:confirm="Are you sure? This cannot be undone.Permanent deletion will remove all associated data and cannot be recovered."
                                                    color="gray"
                                                    size="sm"
                                                    variant="primary"
                                                    icon="trash"
                                                    cursor="pointer"
                                                    title="Permanently Delete Company"
                                                />

                                            @else
                                  
                                        <!-- Edit -->
                                        <flux:button 
                                            wire:click="editCompany('{{ $company->id }}')"
                                            wire:loading.attr="disabled"
                                            wire:target="editCompany('{{ $company->id }}')"
                                            size="sm"
                                            variant="primary"
                                            color="green"
                                            icon="pencil"
                                            cursor="pointer"
                                            title="Edit Company"
                                        />
                                   
                                      
                                       
                                          <flux:button 
                                            wire:click="confirmDelete('{{ $company->id }}')"
                                            wire:loading.attr="disabled"
                                            wire:target="confirmDelete('{{ $company->id }}')"
                                            wire:confirm="Are you sure?"
                                            size="sm"
                                            variant="danger"
                                            color="red"
                                            icon="trash"
                                            cursor="pointer"
                                            title="Delete Company"
                                        />
                                            @endif
                                            @can('status_users')
                                            <flux:button 
                                                wire:click="toggleStatus({{ $company->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="toggleStatus({{ $company->id }})"
                                                size="sm"
                                                variant="primary"
                                                color="{{ $company->status ?'green' :  'red' }}"
                                                icon="{{ $company->status ? 'check' : 'x-mark' }}"
                                                cursor="pointer"
                                                title="{{ $company->status ? 'Deactivate' : 'Activate' }}"
                                                :disabled="$hasTrashed"
                                                        
                                            />
                                            @else
                                                <flux:button
                                                    size="sm"
                                                    variant="primary"
                                                    color="{{ $company->status ?'gray' :  'gray' }}"
                                                    icon="{{ $company->status ? 'check' : 'x-mark' }}"
                                                    cursor="pointer"
                                                    title="{{ $company->status ? 'Deactivate' : 'Activate' }}"
                                                    disabled
                                                />
                                            @endcan



                                        
                                    </div>
                                </td>


                    <!-- Company Name -->
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div>
                                <h5
                                    class="font-semibold {{ $company->trashed() ? 'text-red-500 dark:text-red-400' : 'text-gray-900 dark:text-gray-100' }}">
                                    {{ $company->company_name }}
                                </h5>

                                @if($company->trashed())
                                    <span class="text-xs text-red-500">
                                        Deleted
                                    </span>
                                @endif
                            </div>
                        </div>
                    </td>

                    <!-- Contact -->
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                        <div>{{ $company->company_email }}</div>

                        @if($company->company_phone)
                            <div>{{ $company->company_phone }}</div>
                        @endif
                    </td>

                    <!-- Address -->
                    <!-- ADDED: min-w-[250px] to prevent mobile squishing -->
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300 min-w-[250px] md:min-w-[300px] max-w-md break-words">
                        {{ $company->company_address_line_1 }}
                        {{ $company->company_address_line_2 ? ', '.$company->company_address_line_2 : '' }},
                        {{ $company->company_city }},
                        {{ $company->company_state }},
                        {{ $company->company_country }}
                        - {{ $company->company_postal_code }}
                    </td>

                    <!-- Logo -->
                    <td class="px-6 py-4 text-center">
                        @if($company->company_logo)
                            <img
                                src="{{ asset('storage/' . $company->company_logo) }}"
                                alt="{{ $company->company_name }} Logo"
                                class="w-16 rounded-full object-cover mx-auto border border-gray-200 dark:border-gray-600"
                            >
                        @else
                            <span class="text-sm text-gray-400">
                                No Logo
                            </span>
                        @endif
                    </td>

                    <!-- Favicon -->
                    <td class="px-6 py-4 text-center">
                        @if($company->company_favicon)
                            <img
                                src="{{ asset('storage/' . $company->company_favicon) }}"
                                alt="{{ $company->company_name }} Favicon"
                                class="w-12 rounded-full object-cover mx-auto border border-gray-200 dark:border-gray-600"
                            >
                        @else
                            <span class="text-sm text-gray-400">
                                No Favicon
                            </span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center">
                        <div class="flex flex-col items-center">
                            <svg
                                class="w-12 h-12 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="M3 7h18M5 7v10a2 2 0 002 2h10a2 2 0 002-2V7M9 11h6"
                                />
                            </svg>

                            <p class="mt-2 text-gray-500 dark:text-gray-400">
                                No companies found.
                            </p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>

    </table>
</div>
</div>