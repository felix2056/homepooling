<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostTypeFeature extends Model
{
	public function type(){
    	return $this->belongsTo('App\PostType');
	}
}