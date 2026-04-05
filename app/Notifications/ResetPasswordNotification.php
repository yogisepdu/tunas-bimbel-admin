<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = config('app.url') 
            . '/reset-password?token=' 
            . $this->token 
            . '&email=' 
            . urlencode($notifiable->email);

        return (new MailMessage)
            ->subject('Reset Password Akun Kamu 🔐')
            ->view('emails.reset-password', [
                'actionUrl' => $url, // 🔥 INI YANG BENAR
                'user' => $notifiable
            ]);
    }
}