<?php

namespace App\Livewire\Users;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;


use Illuminate\Validation\Rule;
use Livewire\Component;

class UserEdit extends Component
{
    public User $user;

    public string $name = '';
    public string $email = '';
    public ?string $password = null;
    public ?string $password_confirmation = null;
    public $roles = [];          // selected role IDs
    public $rolesList = [];    // roles from DB

    public $selectedCompany;
    public $companyList = [];


    
public function mount(User $user = null)
{
    $authUser = auth()->user();

    // Roles
    if ($authUser->hasRole('Superadmin')) {

        $roles = Role::withoutSuperadmin()->get();
    }
    elseif ($authUser->hasRole('Admin')) {

        $roles = Role::whereNotIn('name', ['Superadmin', 'Admin'])->get();
    }
    else {

        $roles = Role::where('name', 'User')->get();
    }

     if (!auth()->user()->hasRole('Superadmin')) {
            $roles = $roles->where('name', '!=', 'Admin');
            $this->roles = 'Admin';
        }
        $this->rolesList = $roles;
       

    // Active Companies List
    $this->companyList = Company::where('status', 1)->get();

    // Edit Mode
    if ($user) {

        $this->user = $user;

        $this->name = $user->name;
        $this->email = $user->email;

        // selected company
        $this->selectedCompany = $this->user->company_uuid;

        // selected role
        $this->roles = $user->roles()
            ->pluck('name')
            ->first();
    }
}

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'password_confirmation' => 'nullable|same:password',
            'selectedCompany' => 'required|exists:companies,company_uuid',
            'roles' => 'required|min:1',
        ];
    }

     public function updateUser()
    {
        abort_unless(auth()->user()->can('edit_users'), 403);
        if (
            auth()->user()->hasRole('Admin') &&
            auth()->id() == $this->user->id
        ) {
            session()->flash('error', 'You cannot update your own account.');
            return;
        }
     
        $this->validate();


        $role = is_array($this->roles)
            ? $this->roles[0]
            : $this->roles;

            
        if ($this->roles === 'Admin' && User::companyHasAdmin($this->selectedCompany, $this->user->id)) {
            session()->flash('error', 'This company already has an Admin.');
            return;
        }


        $this->user->name = $this->name;
        $this->user->email = $this->email;
        $this->user->company_uuid = $this->selectedCompany;
        $this->user->role = $role;
        
         if ($this->password) {
            $this->user->password = bcrypt($this->password);
        }
         if ($this->roles) {
            $this->user->syncRoles($this->roles);
        } else {
            $this->user->syncRoles([]); // Remove all roles if none selected
        }


        $this->user->save();

        session()->flash('message', 'User updated successfully.');

        return redirect()->route('users.index');
    }   

        
    public function render()
    {
        return view('livewire.users.user-edit');
    }
}

