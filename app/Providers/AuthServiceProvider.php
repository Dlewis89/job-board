<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use Illuminate\Validation\Rules\Password;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
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

        if (! $this->app->routesAreCached()) {
            Passport::routes();
        }

        Passport::tokensExpireIn(now()->addDay());
        Passport::refreshTokensExpireIn(now()->addDay());
        Passport::personalAccessTokensExpireIn(now()->addDay());

        Password::defaults(function () {
            $rule = Password::min(8);

            return $this->app->isProduction()
                        ? $rule->mixedCase()->uncompromised()
                        : $rule;
        });
    }
}
