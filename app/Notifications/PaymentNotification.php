<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;

class PaymentNotification extends Notification implements ShouldQueue
{
    use Queueable;
    
    public $id;
    public $user_id;
    public $amount;
    public $name;
    public $family_name;
    public $product;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order)
    {
		$this->id=$order->id;
		$this->amount=$order->amount;
		$this->user_id=$order->user->id;
		$this->name=$order->user->name;
		$this->family_name=$order->user->family_name;

		if(isset($order->property_id)){
			$type='Property';
			$this->product=ucfirst($order->type).' '.$type.' on Homepooling';
		}elseif(isset($order->wanted_id)){
			$type='Wanted';
			$this->product=ucfirst($order->type).' '.$type.' on Homepooling';
		}else{
			$type='Early Bird';
			$this->product=$type.' on Homepooling';
		}
		
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
   		$pdf=new Dompdf();
		$html=view('invoice',['amount'=>$this->amount,'product_name'=>$this->product,'name'=>$this->name.' '.$this->family_name])->render();
		$pdf->loadHtml($html);
		// (Optional) Setup the paper size and orientation
		$pdf->setPaper('A4', 'portrait');
		// Render the HTML as PDF
		$pdf->render();
		$filename='invoice_'.$this->id.'.pdf';
		// Output the generated PDF to storage
		Storage::put('/public/invoices/'.$filename,$pdf->output());
		$invoice=new \App\Invoice;
		$invoice->user_id=$this->user_id;
		$invoice->path='/storage/invoices/'.$filename;
		$invoice->save();
		
		return (new MailMessage)
			->subject('[Homepooling] New message received')
			->greeting('Hi, '.$this->name.' '.$this->family_name.',')
			->line('Attached, you can find the invoice for your payment on Homepooling')
			->line('Thank you for using our application!')
			->attach(asset('storage/invoices/'.$filename), [
				'as' => 'invoice.pdf',
				'mime' => 'application/pdf',
			]);
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
