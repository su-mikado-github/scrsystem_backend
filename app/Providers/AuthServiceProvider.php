<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Flags;

use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
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

        Gate::define('is_admin', function (User $user) {
            if ($user->is_admin == Flags::ON) {
                logger()->debug('is_admin => OK');
                if (!validator([ 'email'=>$user->email ], [ 'email'=>[ 'required', 'email:rfc' ] ])->fails()) {
                    logger()->debug('email => OK');
                    return true;
                }
            }
            logger()->debug('Gate:is_admin => NG');
            return false;
        });
    }
}
