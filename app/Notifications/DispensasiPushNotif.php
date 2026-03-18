<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class DispensasiPushNotif extends Notification
{
    use Queueable;

    protected $title;
    protected $body;
    protected $url;

    // 🔥 Constructor sekarang dinamis
    public function __construct($title, $body, $url)
    {
        $this->title = $title;
        $this->body = $body;
        $this->url = $url;
    }

    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
{
    return (new WebPushMessage)
        ->title('Dispensasi Baru')
        ->icon('/logo.png')
        ->badge('/logo.png') // kecil di status bar
        ->body('Ada dispensasi baru dari siswa, cek sekarang!')
        ->tag('dispen-notif') // supaya tidak numpuk
        ->renotify(true)
        ->vibrate([100, 50, 100])
        ->data([
            'url' => url('/dispen')
        ]);
}
}