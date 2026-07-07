<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

use App\Policies\UserPolicy;
use App\Policies\RolePolicy;
use App\Policies\CompanyPolicy;
use App\Models\User;
use App\Models\Role;
use App\Models\Company;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;

use Spatie\Permission\Models\Permission;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Company::class, CompanyPolicy::class);
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Superadmin') ? true : null;
        });


        View::composer('*', function ($view) {

                if (!session()->has('active_company_uuid') && auth()->check()) {

                    $user = auth()->user();

                    $companyUuid = $user->hasRole('Superadmin')  ? 'all' : ($user->companies?->company_uuid ?? $user->company_uuid);

                    session(['active_company_uuid' => $companyUuid]);
                }

                $companyUuid = session('active_company_uuid');

                $companyName = match (true) {
                    !$companyUuid => 'No Company Selected',
                    $companyUuid === 'all' => 'All Companies',
                    default => Company::where('company_uuid', $companyUuid)
                        ->value('company_name') ?? 'Unknown Company',
                };

                $view->with('activeCompanyName', $companyName);
            });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
