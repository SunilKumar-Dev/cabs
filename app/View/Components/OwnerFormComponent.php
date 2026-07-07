<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class OwnerFormComponent extends Component
{
    public $showModal;
    public $ownerId;
    public $owners;
    public $owner_photo;
    public $id_proof;
    public $id_proof_number;
    public $id_proof_pdf;
    public $status;
    public $companies;
    public $relationship_with_applicant;
    public $selectedCompany;
    public $relations;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $showModal = false,
        $ownerId = null,
        $owners = [],
        $owner_photo = null,
        $id_proof = null,
        $id_proof_number = null,
        $id_proof_pdf = null,
        $status = 1,
        $companies = [],
        $relationship_with_applicant = null,
        $selectedCompany = null,
        $relations = []
    ) {
        $this->showModal = $showModal;
        $this->ownerId = $ownerId;
        $this->owners = $owners;
        $this->owner_photo = $owner_photo;
        $this->id_proof = $id_proof;
        $this->id_proof_number = $id_proof_number;
        $this->id_proof_pdf = $id_proof_pdf;
        $this->status = $status;
        $this->companies = $companies;
        $this->relationship_with_applicant = $relationship_with_applicant;
        $this->selectedCompany = $selectedCompany;
        $this->relations = $relations;
    }

    

    /**
     * Get the view that represents the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.owner-form-component');
    }
}