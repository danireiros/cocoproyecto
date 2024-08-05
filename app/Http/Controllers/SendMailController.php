<?php

namespace App\Http\Controllers;

use App\Mail\MultipleRecipientsMail;
use Illuminate\Support\Facades\Mail;

class SendMailController extends Controller
{
    /**
     * Send an email to multiple recipients.
     *
     * @param  array  $emails  List of email addresses to send the email to.
     * @param  string  $content  The content of the email.
     * @param  string  $subject  The subject of the email.
     * @return void
     */
    public function sendEmails(array $emails = ['danireiros@gmail.com'], string $content = '', string $subject = 'Notificación CocoPruebas') : void
    {
        foreach ($emails as $email) {
            Mail::to($email)->send(new MultipleRecipientsMail($content, $subject));
        }
    }
}
