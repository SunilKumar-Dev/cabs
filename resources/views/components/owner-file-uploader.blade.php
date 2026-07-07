@props([
    'type'
])

<div class="mb-6">

    {{-- Custom Upload Button --}}
    <label for="{{ $type }}File" class="flex items-center justify-center gap-3 w-full px-6 py-4 rounded-2xl bg-blue-600 text-white font-semibold cursor-pointer hover:bg-blue-700 transition">
        {{ $type === 'owner_photo' ? 'Select Photo' : 'Select ID Proof (PDF)' }}
    </label>

    {{-- Hidden File Input --}}
    <input 
        id="{{ $type }}File" 
        type="file" 
        class="hidden" 
        wire:model="{{ $type }}" 
        accept="{{ $type === 'owner_photo' ? 'image/*' : '.pdf' }}"
    />

    {{-- Loading Indicator --}}
    <div wire:loading wire:target="{{ $type }}" class="mt-4 flex items-center gap-2 text-blue-600">
        <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" />
        </svg>
        Loading preview...
    </div>

    {{-- Preview Container --}}
    @if ($this->{$type})
        <div class="mt-6 rounded-2xl border bg-slate-50 p-4" wire:loading.remove wire:target="{{ $type }}">
            <div class="flex items-center gap-4">
                @if ($type === 'owner_photo')
                    {{-- Image Preview --}}
                    <img src="{{ is_string($this->{$type}) ? asset('storage/' . $this->{$type}) : $this->{$type}->temporaryUrl() }}" 
                         class="w-20 h-20 rounded-xl object-cover border" alt="Preview">
                @else
                    {{-- PDF Placeholder --}}
                    <div class="w-20 h-20 rounded-xl bg-blue-50 flex items-center justify-center text-3xl border border-blue-100">
                        📄
                    </div>
                @endif
                
                 <div>
                @if (is_string($this->{$type}))
                    {{-- Existing file --}}
                    <h4 class="font-semibold text-slate-800">
                        Current {{ $type === 'owner_photo' ? 'Photo' : 'ID Proof' }}
                    </h4>
                    <p class="text-xs text-slate-500">
                        Existing file is attached. Select a new file to replace it.
                    </p>
                @else
                    {{-- New uploaded file --}}
                    <h4 class="font-semibold text-slate-800">
                       File ready for upload
                    </h4>
                    <p class="text-xs text-slate-500">
                        Click save to upload {{ $type === 'owner_photo' ? 'photo' : 'ID Proof' }}.
                    </p>
                @endif
            </div>
                {{-- <div>
                    <h4 class="font-semibold text-slate-800">File ready for upload</h4>
                    <p class="text-xs text-slate-500">Click save to confirm change</p>
                </div> --}}
            </div>
        </div>
    @endif
    
    <flux:error :name="$type" />

</div>


