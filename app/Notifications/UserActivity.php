<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserActivity extends Notification
{
    use Queueable;

    protected $msg_type = '';
    protected $msg_text = '';

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($msg_type, $msg_text)
    {
        $this->msg_type = $msg_type;
        $this->msg_text = $msg_text;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('[Homepooling] New message received')
            ->line($this->msg_text)
            ->action('Go To Admin Panel', url('/back-office'))
            ->line('Sensitive Info About Your Website Is Sent To All Admins');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'msg_type' => $this->msg_type,
            'msg_text' => $this->msg_text,
        ];
    }
}
