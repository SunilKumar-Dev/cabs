<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;
use App\Models\Company;

class UserCreate extends Component
{
    public $name;
    public $email;
    public $password;
    public $company_uuid;
    public $password_confirmation;
    public $roles = [];          // selected role IDs
    public $rolesList = [];    // roles from DB
    public $companies = [];    // companies from DB

    public function mount()
    {
        $this->companies  = Company::where('status', 1)->get();
        $roles = Role::withoutSuperadmin()->get();
        if (!auth()->user()->hasRole('Superadmin')) {
            $roles = $roles->where('name', '!=', 'Admin');
            $this->roles = 'Admin';
        }

         if (auth()->user()->hasRole('Admin')) {
            $this->company_uuid = auth()->user()->companies?->company_uuid;
        }

        $this->rolesList = $roles;
       
    }

    public function createUser()
    {
        Gate::authorize('create', User::class);

        if ($this->name === 'Admin' || $this->name === 'User'  && auth()->user()->hasRole('Admin')) {

            $this->addError(
                'name',
                'Admin cannot be updated.'
            );

            return;
        }



        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|same:password',
            'company_uuid' => 'required|exists:companies,company_uuid',
        ]);

        $validated['status'] = 0;

            // ROLE LOGIC
        if (auth()->user()->hasRole('Superadmin')) {

            $role = 'Admin';

            $companyUuid = $this->company_uuid;

        } else {

            if ($this->roles === 'Admin') {
                abort(403, 'You are not allowed to create Admin users.');
            }

            $role = $this->roles;

            $companyUuid = auth()->user()->company_uuid;
        }

        // CHECK DUPLICATE ADMIN
        if ($role === 'Admin') {

            $adminExists = User::where('company_uuid', $companyUuid)
                ->where('role', 'Admin')
                ->exists();

            if ($adminExists) {

                $this->addError(
                    'company_uuid',
                    'This company already has an Admin.'
                );

                return;
            }
        }



        $user = User::create([
            'name' => $validated['name'],
            'uuid' => (string) Str::uuid(),
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'status' => $validated['status'],
            'role' => $role,
            'company_uuid' => $companyUuid,
        ]);

        $user->created_by_uuid = auth()->user()->uuid;
        $user->assignRole($role);
        $user->save();

        $this->dispatch('toast', [
            'message' => 'User created successfully!',
            'type' => 'success'
        ]);

        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.users.user-create');
    }
}
