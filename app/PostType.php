<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostType extends Model
{
    public function features(){
    	return $this->hasMany('App\PostTypeFeature');
    }
}