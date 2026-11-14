<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentProofSubmittedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Transaction $transaction,
        public string $recipientType = 'customer',
    ) {
        $this->transaction->loadMissing([
            'package',
            'paymentMethod',
        ]);
    }

    /**
     * Notification untuk customer.
     */
    public static function forCustomer(
        Transaction $transaction
    ): self {
        return new self(
            $transaction,
            'customer'
        );
    }

    /**
     * Notification untuk admin.
     */
    public static function forAdmin(
        Transaction $transaction
    ): self {
        return new self(
            $transaction,
            'admin'
        );
    }

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
        if ($this->recipientType === 'admin') {
            return $this->toAdminMail();
        }

        return $this->toCustomerMail();
    }

    /**
     * =========================================================
     * EMAIL ADMIN
     * =========================================================
     */
    private function toAdminMail(): MailMessage
    {
        $transaction = $this->transaction;

        $total = 'Rp '
            . number_format(
                (float) $transaction->total,
                0,
                ',',
                '.'
            );

        $paymentMethod =
            $transaction->payment_method_name ?: '-';

        $paymentUrl = url(
            '/payment/'
                . $transaction->public_token
        );

        return (new MailMessage)
            ->subject(
                'Bukti Pembayaran Baru - '
                    . $transaction->invoice_no
            )
            ->greeting(
                'Halo Admin Tunas Bimbel 👋'
            )
            ->line(
                'Ada bukti pembayaran baru yang perlu diperiksa.'
            )
            ->line(
                'Invoice: '
                    . $transaction->invoice_no
            )
            ->line(
                'Nama customer: '
                    . $transaction->customer_name
            )
            ->line(
                'Email customer: '
                    . $transaction->customer_email
            )
            ->line(
                'WhatsApp customer: '
                    . $transaction->customer_phone
            )
            ->line(
                'Paket: '
                    . $transaction->package_name
            )
            ->line(
                'Metode pembayaran: '
                    . $paymentMethod
            )
            ->line(
                'Total pembayaran: '
                    . $total
            )
            ->line(
                'Status: '
                    . $transaction->status_label
            )
            ->line(
                'Bukti pembayaran telah dikirim dan menunggu verifikasi admin.'
            )
            ->action(
                'Buka Halaman Pembayaran',
                $paymentUrl
            )
            ->line(
                'Silakan buka panel transaksi untuk memeriksa bukti dan melakukan verifikasi.'
            )
            ->salutation(
                'Tunas Bimbel'
            );
    }

    /**
     * =========================================================
     * EMAIL CUSTOMER
     * =========================================================
     */
    private function toCustomerMail(): MailMessage
    {
        $transaction = $this->transaction;

        $total = 'Rp '
            . number_format(
                (float) $transaction->total,
                0,
                ',',
                '.'
            );

        $paymentMethod =
            $transaction->payment_method_name ?: '-';

        $paymentUrl = url(
            '/payment/'
                . $transaction->public_token
        );

        $adminName = config(
            'tunas_bimbel.admin_name',
            'Admin Tunas Bimbel'
        );

        $adminWhatsapp = config(
            'tunas_bimbel.admin_whatsapp'
        );

        $whatsappMessage = urlencode(
            'Halo '
                . $adminName
                . ', saya ingin konfirmasi pembayaran. '
                . 'Invoice: '
                . $transaction->invoice_no
                . ', Paket: '
                . $transaction->package_name
                . ', Total: '
                . $total
                . '.'
        );

        $whatsappUrl = $adminWhatsapp
            ? 'https://wa.me/'
            . preg_replace(
                '/\D+/',
                '',
                $adminWhatsapp
            )
            . '?text='
            . $whatsappMessage
            : null;

        $mail = (new MailMessage)
            ->subject(
                'Bukti Pembayaran Diterima - '
                    . $transaction->invoice_no
            )
            ->greeting(
                'Halo '
                    . ($transaction->customer_name ?: 'Student')
                    . ' 👋'
            )
            ->line(
                'Bukti pembayaran Anda telah berhasil diterima oleh Tunas Bimbel.'
            )
            ->line(
                'Invoice: '
                    . $transaction->invoice_no
            )
            ->line(
                'Paket: '
                    . $transaction->package_name
            )
            ->line(
                'Metode pembayaran: '
                    . $paymentMethod
            )
            ->line(
                'Total pembayaran: '
                    . $total
            )
            ->line(
                'Status: Menunggu Verifikasi'
            )
            ->line(
                'Admin akan memeriksa bukti pembayaran Anda sebelum paket diaktifkan.'
            )
            ->action(
                'Lihat Status Pembayaran',
                $paymentUrl
            )
            ->line(
                'Jika membutuhkan bantuan, Anda dapat menghubungi admin Tunas Bimbel.'
            );

        if ($whatsappUrl) {
            $mail->line(
                'Kontak WhatsApp Admin: '
                    . $whatsappUrl
            );
        }

        return $mail->salutation(
            'Terima kasih, Tunas Bimbel'
        );
    }
}
