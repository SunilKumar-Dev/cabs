<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role as SpatieRole;

#[Fillable(['id','name', 'guard_name','created_by', 'status'])]
class Role extends SpatieRole
{
    use HasFactory, HasRoles, SoftDeletes;
    

    public function scopeWithoutSuperadmin($query)
    {
        return $query->where('name', '!=', 'Superadmin');
    }

    
     



}