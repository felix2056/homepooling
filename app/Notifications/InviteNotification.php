<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class InviteNotification extends Notification implements ShouldQueue
{
    use Queueable;
	
	
	public $username;
	public $property_id;
	public $user_id;
	
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($username,$property_id,$user_id)
    {
        $this->username=$username;
        $this->property_id=$property_id;
        $this->user_id=$user_id;
        
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
			->subject('[Homepooling] You have been invited')
			->greeting('Hi '.$this->username.',')
			->line('You have received an invite to visit a Property on Homepooling.')
			->action('Visit the property', url('/properties/'.$this->property_id))
			->line('Get in touch with fellow poolers!');

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
            //
        ];
    }
}
