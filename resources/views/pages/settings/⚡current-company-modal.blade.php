<?php

use Livewire\Component;
use App\Models\Company;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;

new class extends Component
{
    public array $companies = [];
    public ?string $selectedCompany = null;

    public function mount(): void
    {
        $this->companies = Company::pluck('company_name', 'company_uuid')->toArray();
        $this->selectedCompany = session('active_company_uuid');
    }

    public function updatedSelectedCompany($value): void
    {
        if ($value === 'all' || $value === null) {
            session()->forget('active_company_uuid');
            $this->selectedCompany = null;
        } else {
            session()->put('active_company_uuid', $value);
            $this->selectedCompany = $value;
        }

        // optional UI refresh trigger
        $this->dispatch('company-changed');
    }

    // optional (if you want manual refresh support anywhere)
    #[On('company-changed')]
    public function refresh()
    {
        $this->selectedCompany = session('active_company_uuid');
    }
};

?>
<div>
    <section class="mt-10 space-y-6">

        <div class="relative mb-5">
            <flux:heading>Switch Company</flux:heading>
            <flux:subheading>Switch to a different company</flux:subheading>
        </div>

        <flux:select
            wire:model.live="selectedCompany"
            placeholder="Select a company..."
        >
            <option value="all">All Companies</option>

            @foreach ($companies as $uuid => $name)
                <flux:select.option value="{{ $uuid }}">
                    {{ $name }}
                </flux:select.option>
            @endforeach
        </flux:select>

    </section>
</div>