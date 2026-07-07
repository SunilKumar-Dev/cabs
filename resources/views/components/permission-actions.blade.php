<div>
@props([
    'id',
    'editClick',
    'deleteClick'
])

<div class="flex items-center gap-2">
    <!-- Edit Button -->
    <flux:button
        type="button"
        title="Edit"
        wire:click="{{ $editClick }}({{ $id }})"
        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition"
    >
        <svg xmlns="http://www.w3.org/2000/svg"
             class="w-4 h-4 mr-1"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.586 2.586a2 2 0 112.828 2.828L12 14.828l-4 1 1-4 9.586-9.242z"/>
        </svg>
    </flux:button>

    <!-- Delete Button -->
    <flux:button
        type="button"
        title="Delete"
        wire:click="{{ $deleteClick }}({{ $id }})"
        wire:confirm="Are you sure you want to delete this record?"
        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition"
    >
        <svg xmlns="http://www.w3.org/2000/svg"
             class="w-4 h-4 mr-1"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-7 0h8"/>
        </svg>
    </flux:button>
</div>
</div>