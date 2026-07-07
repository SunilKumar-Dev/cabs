<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;


class RoleIndex extends Component
{

    public $readablePermissions = '';
    public function render()
    {
        $roles = Role::query()
            ->withoutSuperadmin()
            ->with('users')
            ->with('permissions')
            ->where('status', 1)
            ->get();  
        return view('livewire.roles.role-index', compact('roles'));
    }
}
