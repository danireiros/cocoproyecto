<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class VerificationController extends Controller
{
    /**
     * Verify the user's email address.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id  The ID of the user to verify.
     * @param  string  $hash  The email verification hash.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request, int $id, string $hash)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect('/?status=Enlace de verificación invalido.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect('/?status=Tu email ya fue verificado anteriormente.');
        }

        $user->markEmailAsVerified();

        return redirect('/?status=Tu email ha sido verificado.');
    }

    /**
     * Resend the email verification link to the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect('/');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'Verification link sent!');
    }
}
