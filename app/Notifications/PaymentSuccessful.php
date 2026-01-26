<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentSuccessful extends Notification
{
    use Queueable;

    protected $transaction;

    public function __construct($transaction)
    {
        // Kita kirim data transaksi ke dalam notifikasi
        $this->transaction = $transaction;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Mengambil nama user dari objek $notifiable
        $name = ucfirst($notifiable->name);

        return (new MailMessage)
            ->subject('Pembayaran Berhasil - w3site.id')
            ->greeting("Halo $name!") // Menggunakan nama user
            ->line('Terima kasih! Pembayaran untuk Order ID: ' . $this->transaction->order_id . ' telah kami terima.')
            ->line('Sekarang paket Anda sudah aktif. Silakan mulai buat website AI Kakak!')
            ->action('Mulai Buat Website', url('/dashboard'))
            ->line('Jika ada kendala, hubungi tim support kami ya.');
    }
}