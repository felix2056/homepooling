<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReportNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $author;
    public $user_id;
    public $property_id;
    public $text;
    
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($report)
    {
        $this->author=\App\User::find($report->author_id);
        $this->user_id=$report->user_id;
        $this->property_id=$report->property_id;
        $this->text=$report->text;
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
		$message=new MailMessage;
		if(isset($this->property_id)){
			$message->subject('[Homepooling] A Property has been reported');
			$message->greeting('Hi Admin,');
			$message->line('A Property has been reported for abuse or inappropriate content by '.$this->author->name.' '.$this->author->family_name.'.');
			if(isset($this->text)){
				$message->line('The text of the report:'.$this->text);
			}
			$message->action('Visit the admin panel', url('/back-office/reports'));
		}
		if(isset($this->user_id)){
			$message->subject('[Homepooling] A User has been reported');
			$message->greeting('Hi Admin,');
			$message->line('A User has been reported for abuse or inappropriate content by '.$this->author->name.' '.$this->author->family_name.'.');
			if(isset($this->text)){
				$message->line('The text of the report:'.$this->text);
			}
			$message->action('Visit the admin panel', url('/back-office/reports'));
		}
		return $message;
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
