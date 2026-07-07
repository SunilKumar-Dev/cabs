<?php

namespace App\Exports;

use App\Models\User;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;

class UsersExport implements FromCollection, WithHeadings, WithStyles
{

protected $authUser;
protected $companyUuid;
protected $search;

public function __construct($authUser, $companyUuid, $search)
{
    $this->authUser = $authUser;
    $this->companyUuid = $companyUuid;
    $this->search = $search;
}

public function collection()
{
    $query = User::query()->with('roles');

    if ($this->authUser->hasRole('Superadmin')) {
        $query->withTrashed();
    }

    $companyUuid = $this->companyUuid === 'all'
        ? null
        : $this->companyUuid;

    if ($companyUuid) {
        $query->where('company_uuid', $companyUuid);
    }

    if (! $this->authUser->hasRole('Superadmin')) {

        if ($this->authUser->hasRole('Admin')) {

            $query->withoutRoles(['Superadmin', 'Admin'])
                ->where('created_by_uuid', $this->authUser->uuid);

        } else {

            $query->where('created_by_uuid', $this->authUser->uuid);
        }

    } else {

        $query->withoutRoles(['Superadmin']);
    }

    if ($this->search) {
        $query->where(function ($q) {
            $q->where('name', 'like', "%{$this->search}%")
              ->orWhere('email', 'like', "%{$this->search}%");
        });
    }

    $query->where('status', '!=', 2);

    return $query->get()->map(function ($user) {

        return [
            'Name'  => $user->name,
            'Email' => $user->email,
            'Role'  => $user->role,
        ];
    });
}


    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Role',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // First row (headings) bold
            1 => [
                'font' => [
                    'bold' => true,
                ],
            ],
        ];
    }
}
