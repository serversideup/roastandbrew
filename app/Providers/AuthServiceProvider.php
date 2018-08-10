<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Models\Cafe;
use App\Policies\CafePolicy;

use App\Models\Action;
use App\Policies\ActionPolicy;

use App\Models\Company;
use App\Policies\CompanyPolicy;

use App\Models\User;
use App\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Cafe::class => CafePolicy::class,
        Action::class => ActionPolicy::class,
        Company::class => CompanyPolicy::class,
        User::class => UserPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
    }
}
