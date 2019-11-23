<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
	public function from_user(){
		return $this->belongsTo('\App\User','user_id_1');
	}
	public function to_user(){
		return $this->belongsTo('\App\User','user_id_2');
	}
	public function messages(){
		return $this->hasMany('\App\Message');
	}
	public function getLastMessage(){
		return $this->messages()->orderBy('created_at','DESC')->take(1)->get();
	}
	public function hasUnread(){
		foreach($this->messages as $message){
			if($message->seen===0 && ($message->user_id !== \Auth::user()->id)) return true;
		}
		return false;
	}

}
