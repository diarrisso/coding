<?php

namespace App\Notifications;

use App\Models\Teaser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TeaserNotification extends Notification implements ShouldQueue
{
    use Queueable;


    /**
     * Create a new notification instance.
     *
     * @param Teaser $teaser
     */
    public function __construct( private readonly Teaser $teaser)
    {
    }
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */

    public function toMail(mixed $notifiable): MailMessage
    {
        $recipientName = $notifiable->name ?? 'Administrator';
        return (new MailMessage)
            ->subject('Neue Teaser erstellt')
            ->greeting('Hallo ' . $recipientName . ',')
            ->line('Eine Teaser wurde erstellt .')
            ->line('Überschrift: ' . $this->teaser->title)
            ->action('Greifen Sie auf diese ', route('teasers.show',  $this->teaser->id))
            ->line('Danke, dass du unsere App benutzt hast!');
    }


}
