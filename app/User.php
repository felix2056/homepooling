<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Cache;

use Laravel\Cashier\Billable;

class User extends Authenticatable
{
	use Notifiable;
	use Billable;

	protected $fillable = ['name', 'email', 'password', 'gender', 'photo','family_name','msg_in_remain','early_bird'];
	protected $hidden 	= ['password', 'remember_token', 'is_admin'];

	public function isOnline()
	{
	    return Cache::has('user-is-online-' .$this->id);
	}

	public function preferences(){
		return $this->hasOne('\App\UserPreference');
	}
	public function wanted(){
		return $this->hasOne('\App\Wanted');
	}
	public function orders(){
		return $this->hasMany('\App\Order');
	}
	public function reviews(){
		return $this->hasMany('\App\Review');
	}
	public function reporteds(){
		return $this->hasMany('\App\Report');
	}
	public function reports(){
		return $this->hasMany('\App\Report','author_id');
	}
	public function properties(){
		return $this->hasMany('\App\Property');
	}
	public function visits(){
		return $this->hasMany('\App\Visit');
	}
	public function favorites(){
		return $this->hasMany('\App\Favorite');
	}
	public function verifies(){
		return $this->hasMany('\App\Verify');
	}
	public function invites(){
		return $this->hasMany('\App\Invite','user_id_from');
	}
	public function inviteds(){
		return $this->hasMany('\App\Invite','user_id_to');
	}
	public function msgs_from(){
		return $this->hasMany('\App\Thread','user_id_1');
	}
	public function msgs_to(){
		return $this->hasMany('\App\Thread','user_id_2');
	}
	public function msgs_all(){
		return $this->hasMany('\App\Message');
	}
}
