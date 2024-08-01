<?php

namespace App\Providers;

use Illuminate\Auth\Access\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate as FacadesGate;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VerifyEmail as VerifyEmailNotification;
use App\Models\User;
use App\Models\Project;
use App\Policies\ProjectPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Project::class => ProjectPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Notification::routes([
            'mail' => env('MAIL_FROM_ADDRESS'),
        ]);

        FacadesGate::define('verify-email', function (User $user) {
            return $user->hasVerifiedEmail();
        });
    }
}
