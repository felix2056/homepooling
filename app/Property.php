<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
	public function rooms(){
		return $this->hasMany('\App\Room');
	}
	public function images(){
		return $this->hasMany('\App\Image');
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
	public function getHighestOrder(){
		$types=[];
		if(count($this->orders)>0){
			foreach($this->orders as $order){
				array_push($types,$order->type);
			}
			if(in_array('premium',$types)) return 'premium';
			if(in_array('bump',$types)) return 'bump';
			if(in_array('basic',$types)) return 'basic';
		}else{
			return false;
		}
	}
	public function contract(){
		return $this->hasOne('\App\Contract');
	}
	public function epcert(){
		return $this->hasOne('\App\Epcert');
	}
	public function user(){
		return $this->belongsTo('\App\User');
	}
	public function amenities(){
		return $this->belongsToMany('\App\Amenity');
	}
	public function acceptings(){
		return $this->belongsToMany('\App\Accepting');
	}
	public function visits(){
		return $this->hasMany('\App\Visit');
	}
	public function favorites(){
		return $this->hasMany('\App\Favorite');
	}
	public function invites(){
		return $this->hasMany('\App\Invite');
	}
}
