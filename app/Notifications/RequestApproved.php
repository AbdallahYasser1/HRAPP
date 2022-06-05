<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\PusherPushNotifications\PusherChannel;
use NotificationChannels\PusherPushNotifications\PusherMessage;
class RequestApproved extends Notification
{
    use Queueable;


    public function via($notifiable)
    {
        return [PusherChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return PusherMessage
     */
    public function toPushNotification($notifiable)
    {
        return PusherMessage::create()
            ->iOS()
            ->badge(1)
            ->title('Request Test')
            ->body('Request has arrived')
            ->sound('success')
            ->body("Your {$notifiable->service} account was approved!");
    }


    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
