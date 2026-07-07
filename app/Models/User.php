<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Company;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['id','uuid','name','role', 'email', 'password', 'status','company_uuid','created_by_uuid'])]
#[Hidden(['id', 'password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements PasskeyUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, PasskeyAuthenticatable, TwoFactorAuthenticatable, HasRoles, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        $initials = Str::initials($this->name, true);

        return Str::length($initials) > 1
            ? Str::substr($initials, 0, 1).Str::substr($initials, -1)
            : $initials;
    }


      public function scopeWithoutSuperadmin($query)
    {
        return $query->whereHas('roles', function($q) {
            $q->where('name', '!=', 'Superadmin');
        });
    }

    // Hierarchy
    public function creator():BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_uuid', 'uuid');
    }

    public function createdUsers():HasMany
    {
        return $this->hasMany(User::class, 'created_by_uuid', 'uuid');
    }

    public function company()
{
    return $this->belongsTo(
        Company::class,
        'company_uuid',
        'company_uuid'
    );
}

    public function Companies():BelongsTo
    {
        return $this->belongsTo(Company::class,'company_uuid','company_uuid');
    }

    
    // User Model
    public function scopeWithoutRoles($query, array $roles)
    {
        return $query->whereDoesntHave('roles', function ($q) use ($roles) {
            $q->whereIn('name', $roles);
        });
    }


    //check this method , admin and super admin is this correct or not?
    public static function companyHasAdmin(string $companyUuid, ?int $ignoreId = null): bool
    {
        return static::where('company_uuid', $companyUuid)
            ->where('role', 'Admin')
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists();
    }



}
