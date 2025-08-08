<?php

namespace App\Providers;

use App\Models\User; // T2ekdi men had l'import
use Illuminate\Support\Facades\Gate; // T2ekdi men had l'import
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
{
    // Version finale et sécurisée du Gate
    Gate::define('manage-users', function (User $user) {
        return trim($user->role) === 'Admin';
    });
}
}