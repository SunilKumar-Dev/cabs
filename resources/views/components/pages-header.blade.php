@props([
    'title',
    'description',
    'rightbutton'
])

        <div class="relative mb-6 w-full">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-star lg:justify-between">
                <!-- LEFT -->
                <div class="min-w-0">
                    <flux:heading size="xl" level="1">
                      {{ $title }}
                    </flux:heading>
                    <flux:subheading size="lg" class="mt-1">
                       {{ $description }}
                    </flux:subheading>
                    <div class="text-blue-700">
                      {{ $activeCompanyName }}
                    </div>
                </div>
                
                <!-- RIGHT -->
                <div class="w-full lg:w-auto">

                    <div>
                        @if (isset($rightbutton))
                            {{ $rightbutton }}
                        @endif
                    </div>

                </div>

            </div>
            <flux:separator variant="subtle" class="mt-4" />
        </div>


        