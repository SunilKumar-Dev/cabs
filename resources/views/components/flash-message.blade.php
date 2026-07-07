<div>
  {{-- Error Message --}}
@if (session()->has('error'))

    <div class="mb-4 flex items-start justify-between gap-4 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700 shadow-sm" >

        <div class="flex items-start gap-3">

            <div class="mt-0.5">
                ❌
            </div>

            <div>
                <p class="font-medium">
                    Error
                </p>

                <p class="text-sm">
                    {{ session('error') }}
                </p>
            </div>

        </div>

        <button
            @click="show = false"
            class="text-red-500 hover:text-red-700"
        >
            ✕
        </button>

    </div>
@endif


{{-- Success Message --}}
@if (session()->has('success'))
    <div class="mb-4 flex items-start justify-between gap-4 rounded-xl border border-green-200 bg-green-50 p-4 text-green-700 shadow-sm" >

        <div class="flex items-start gap-3">

            <div class="mt-0.5">
                ✅
            </div>

            <div>
                <p class="font-medium">
                    Success
                </p>

                <p class="text-sm">
                    {{ session('success') }}
                </p>
            </div>

        </div>

        <button
            @click="show = false"
            class="text-green-500 hover:text-green-700"
        >
            ✕
        </button>

    </div>
@endif
</div>