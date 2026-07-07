<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Superadmin sees all, everyone else only sees their own
        return $user->hasRole('Superadmin')|| $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
         // Superadmin sees all, everyone else only sees their own
        // return $user->hasRole('superadmin') || $user->id === $model->id;
          return $user->hasRole('Superadmin')|| $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('Superadmin')|| $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can update the model.
     */
     public function update(User $user, User $model): bool
    {
        return $user->hasRole('Superadmin')|| $user->hasRole('Admin');
    }
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
       return $user->hasRole('Superadmin')|| $user->hasRole('Admin');
    }



    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
         return $user->role === 'Superadmin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->role === 'Superadmin';
    }
}
