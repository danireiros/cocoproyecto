<?php

namespace App\Providers;

use Illuminate\Auth\Access\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate as FacadesGate;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VerifyEmail as VerifyEmailNotification;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // Register your policies here
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Configurar el correo electrónico de verificación para la notificación
        Notification::routes([
            'mail' => env('MAIL_FROM_ADDRESS'),
        ]);

        // Opcional: Puedes personalizar la verificación de correo electrónico
        FacadesGate::define('verify-email', function (User $user) {
            return $user->hasVerifiedEmail();
        });
    }
}
