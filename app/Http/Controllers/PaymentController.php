<?php

namespace App\Http\Controllers;

use Auth;
use App\Order;
use App\Http\Requests;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

	/*protected function sanitize($request){
		$inputs = [];
		foreach($request->all() as $name => $input)
			if (gettype($input) == 'string')
				$inputs[$name] = filter_var($input, FILTER_SANITIZE_STRING);

		error_log(json_encode($inputs),3,'err.txt');
		$request->replace($inputs);
		return true;
	}*/

	protected function distanced($lat1, $lon1, $lat2, $lon2, $unit) {
		$theta 	= $lon1 - $lon2;
		$dist 	= sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist 	= acos($dist);
		$dist 	= rad2deg($dist);
		$miles 	= $dist * 60 * 1.1515;
		$unit 	= strtoupper($unit);

		return $unit == "K" ? ($miles * 1.609344) : ($unit == "N" ? ($miles * 0.8684) : $miles);
	}

	/**
	 * Make a Stripe payment.
	 *
	 * @param Illuminate\Http\Request $request
	 * @param App\Order $order
	 * @return chargeCustomer()
	*/
	protected function postPayWithStripe($request, $order)
	{
		$type 	= isset($order->property_id) ? 'Property' : (isset($order->wanted_id) ? 'Wanted' : 'Early Bird');
		$id 	= $order->id;
		$amount = $order->amount * 100;
		$name 	= $order->type .' '. $type .' on Homepooling';
		return $this->chargeCustomer($amount, $name, $request->input('stripeToken'));
	}

	/**
	 * Charge a Stripe customer.
	 *
	 * @var Stripe\Customer $customer
	 * @param integer $order_amount
	 * @param string $product_name
	 * @param string $token
	 * @return createStripeCharge()
	*/
	protected function chargeCustomer($order_amount, $product_name, $token)
	{
		\Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

		$customer = !$this->isStripeCustomer() ? $this->createStripeCustomer($token) : \Stripe\Customer::retrieve(Auth::user()->stripe_id);

		return $this->createStripeCharge($order_amount, $product_name, $customer);
	}

	/**
	 * Create a Stripe charge.
	 *
	 * @var Stripe\Charge $charge
	 * @var Stripe\Error\Card $e
	 * @param integer $order_amount
	 * @param string $product_name
	 * @param Stripe\Customer $customer
	 * @return postStoreOrder()
	*/
	protected function createStripeCharge($order_amount, $product_name, $customer)
	{
		try {
			$charge = \Stripe\Charge::create([
				"amount" 		=> $order_amount,
				"currency" 		=> "eur",
				"customer" 		=> $customer->id,
				"description" 	=> $product_name
			]);

			return $charge;
		} catch(\Stripe\Error\Card $e) {
			return false;
			// return redirect()->route('index')->with('error', 'Your credit card was been declined. Please try again or contact us.');
		}

		// return true;
		// return $this->postStoreOrder($product_name);
	}

	/**
	 * Create a new Stripe customer for a given user.
	 *
	 * @var Stripe\Customer $customer
	 * @param string $token
	 * @return Stripe\Customer $customer
	*/
	protected function createStripeCustomer($token)
	{
		\Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

		$customer = \Stripe\Customer::create(array(
			"description" 	=> Auth::user()->email,
			"source" 		=> $token
		));

		Auth::user()->stripe_id = $customer->id;
		Auth::user()->save();

		return $customer;
	}

	/**
	 * Check if the Stripe customer exists.
	 *
	 * @return boolean
	*/
	protected function isStripeCustomer()
	{
		return Auth::check() && \App\User::where('id', Auth::user()->id)->whereNotNull('stripe_id')->first();
	}

	/**
	 * Store a order.
	 *
	 * @param string $product_name
	 * @return redirect()
	*/
	/*protected function postStoreOrder($product_name)
	{
		TODO: notifica via mail del pagamento avvenuto
		Order::create([
			'email' => Auth::user()->email,
			'product' => $product_name
		]);

		return true;

		return redirect()
			->route('index')
			->with('msg', 'Thanks for your purchase!');
	}*/
}
