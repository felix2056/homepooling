<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function property(){
		return $this->belongsTo('\App\Property');
    }
    public function wanted(){
		return $this->belongsTo('\App\Wanted');
    }
    public function user(){
		return $this->belongsTo('\App\User');
    }
}
