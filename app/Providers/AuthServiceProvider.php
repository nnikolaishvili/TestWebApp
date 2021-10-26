<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('update-order', function (User $user) {
             return $user->isAdmin();
        });

        Gate::define('cancel-order', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('export-order', function (User $user) {
            return $user->isAdmin() || $user->isEditor();
        });

        Gate::define('view-products', function (User $user) {
            return $user->isAdmin() || $user->isEditor();
        });
    }
}
