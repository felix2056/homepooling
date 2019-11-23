<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wanted extends Model
{
	public function acceptings(){
		return $this->belongsToMany('\App\Accepting');
	}
	public function amenities(){
		return $this->belongsToMany('\App\Amenity');
	}
	public function user(){
		return $this->belongsTo('\App\User');
	}
	public function orders(){
		return $this->hasMany('\App\Order');
	}
	public function getHighestOrder(){
		$types=[];
		if(count($this->orders)>0){
			foreach($this->orders as $order){
				array_push($types,$order->type);
			}
			if(in_array('premium',$types)) {return 'premium';}
			elseif(in_array('bump',$types)) {return 'bump';}
			elseif(in_array('basic',$types)) {return 'basic';}
		}else{
			return false;
		}
	}
}
