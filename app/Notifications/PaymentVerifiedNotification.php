<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentVerifiedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Transaction $transaction
    ) {}

    public function via(
        object $notifiable
    ): array {
        return [
            'mail',
        ];
    }

    public function toMail(
        object $notifiable
    ): MailMessage {
        $paymentUrl =
            url(
                '/payment/'
                    . $this->transaction->public_token
            );

        return (new MailMessage)
            ->subject(
                'Pembayaran Tunas Bimbel Berhasil Diverifikasi'
            )
            ->greeting(
                'Halo, '
                    . ($notifiable->name ?? 'Siswa')
                    . ' 👋'
            )
            ->line(
                'Pembayaran paket Tunas Bimbel Anda telah berhasil diverifikasi.'
            )
            ->line(
                'Invoice: '
                    . $this->transaction->invoice_no
            )
            ->line(
                'Paket: '
                    . $this->transaction->package_name
            )
            ->line(
                'Total pembayaran: Rp '
                    . number_format(
                        (float) $this->transaction->total,
                        0,
                        ',',
                        '.'
                    )
            )
            ->line(
                'Paket belajar sudah aktif pada akun student Anda.'
            )
            ->action(
                'Lihat Status Pembayaran',
                $paymentUrl
            )
            ->line(
                'Silakan masuk ke aplikasi Tunas Bimbel untuk mulai belajar.'
            );
    }
}
