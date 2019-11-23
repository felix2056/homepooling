<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class MessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

	public $user;
	public $message;
	public $username;
	public $user_id;
	public $text;
	public $from;
	public $photo;
	public $updated;
	public $fromname;
	public $thread_id;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user,$message)
    {
		$this->thread_id=$message->thread_id;
		$this->username=$user->name;
		$this->user_id=$user->id;
		$this->text=$message->text;
		$this->from=$message->user_id;
		$this->fromname=$message->user->name;
		$this->photo=$message->user->photo;
		$this->updated=date('j M',($message->updated_at)->getTimeStamp());
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
	if(!isset(\App\User::find($this->user_id)->preferences)||isset(\App\User::find($this->user_id)->preferences->notify_by_mail)&&\App\User::find($this->user_id)->preferences->notify_by_mail==1||!isset(\App\User::find($this->user_id)->preferences)){
		return ['mail','broadcast'];
	}else{
		return ['broadcast'];
	}
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
			->greeting('Hi '.$this->username.',')
			->line('You have received a new message on Homepooling.')
			->action('Read the message', url('/messages'))
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
			'user_id'=>$this->from,
			'thread_id'=>$this->thread_id,
			'name'=>$this->fromname,
			'text'=>$this->text,
			'photo'=>$this->photo,
			'updated_at'=>$this->updated
		];
	}
	public function toBroadcast($notifiable)
	{
		return new BroadcastMessage([
			'user_id'=>$this->from,
			'thread_id'=>$this->thread_id,
			'name'=>$this->fromname,
			'text'=>$this->text,
			'photo'=>$this->photo,
			'updated_at'=>$this->updated
		]);
	}
}
