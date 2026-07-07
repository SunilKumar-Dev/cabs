<?php

namespace App\Livewire\Users;

use App\Exports\UsersExport;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;


class UserIndex extends Component
{
    use WithPagination;
    public $search = '';

    public function exportUser()
    {
        return Excel::download(
            new UsersExport(
                auth()->user(),
                session('active_company_uuid'),
                $this->search
            ),
        'users.xlsx'
        );
    }

   
    public function deleteUser($id)
    {
        // Gate::authorize('delete', User::class);
        $user = User::findOrFail($id);
        $this->authorize('delete', $user);

        // Prevent self delete
        if ($user->id === auth()->id()) {
            session()->flash('error', 'You cannot delete yourself.');
            return;
        }

        // Prevent Superadmin delete
        if ($user->hasRole('Superadmin')) {
            session()->flash('error', 'Cannot delete Superadmin.');
            return;
        }

        $user->delete();

        session()->flash('success', 'User deleted successfully.');
    }

    
    public function editUser($id)
    {
         $user = User::findOrFail($id);
        $this->authorize('update', $user);

        $companies = Company::where('status', 1)->get(); // Assuming you have a Company model
        // return redirect()->route('users.edit', ['user' => $user, 'companies' => $companies]);     
        
        return redirect()->route('users.edit', $user);

    }

    public function createUser()
    {
        Gate::authorize('create', User::class);
        $companies = Company::where('status', 1)->get(); // Assuming you have a Company model
        if(!$companies->count()) {
            // session()->flash('error', 'First Create Company');
            $this->dispatch('company-required');
             return ;
        }
     
        return redirect()->route('users.create', ['companies' => $companies]);
    }

    public function toggleStatus($userId)
    {
        $user = User::findOrFail($userId);
        $this->authorize('update', $user);

        $authRole = auth()->user()->getRoleNames()->first();

        // 1. If Superadmin disabled user → Admin cannot change
        if (
            $user->disabled_by_role === "Superadmin"
            && $authRole === "Admin"
        ) {
            session()->flash('error', 'Only Superadmin can change status of this user.');
            return;
        }

        // 2. If Admin disabled user → only Superadmin OR Admin can change
        if (
            in_array($user->disabled_by_role, ["Admin", null, ""])
            && !in_array($authRole, ["Superadmin", "Admin"])
        ) {
            session()->flash('error', 'Only Superadmin and Admin can change status of this user.');
            return;
        }

        // Toggle status
        $user->status = !$user->status;

        // Store who changed status
        if(!$user->status) {
            $user->disabled_by_role = $authRole;
        } else {
            $user->disabled_by_role = null;
        }

        $user->save();
    }

     public function restoreUser($id)
    {
        $User = User::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $User);   
        $User->restore();

        session()->flash('success', 'User restored successfully');
    }

     public function forceDelUser($id)
    {
        $User = User::withTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $User);   
        $User->forceDelete();

        session()->flash('success', 'User permanently deleted successfully');
    }
    public function render()
    {
        Gate::authorize('viewAny', User::class);

        $authUser = auth()->user();
        $query = User::query();

        if ($authUser->hasRole('Superadmin')) {
            $query->withTrashed();
        }

        // Yahan fix karein: Agar value 'all' hai, toh ise null maan lein
        $sessionUuid = session('active_company_uuid');
        $activeCompanyUuid = ($sessionUuid === 'all') ? null : $sessionUuid;

        $users = $query
            ->with(['roles', 'companies'])
            ->where(function ($query) use ($authUser, $activeCompanyUuid) {
                
                // 1. Company Filter (Sirf tab chalega agar valid UUID hoga)
                if ($activeCompanyUuid) {
                    $query->whereHas('companies', function ($q) use ($activeCompanyUuid) {
                        $q->where('company_uuid', $activeCompanyUuid);
                    });
                }

                // 2. Role Based Logic
                if (!$authUser->hasRole('Superadmin')) {
                    if ($authUser->hasRole('Admin')) {
                        $query->withoutRoles(['Superadmin', 'Admin'])
                            ->where('created_by_uuid', $authUser->uuid);
                    } else {
                        $query->where('created_by_uuid', $authUser->uuid);
                    }
                } else {
                    $query->withoutRoles(['Superadmin']);
                }
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhereHas('companies', function ($q) {
                        $q->where('company_name', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->where('status', '!=', 2)
            ->latest()
            ->get();

        return view('livewire.users.user-index', compact('users'));
    }
}