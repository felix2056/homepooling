<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
	protected $fillable = ['user_id', 'receiver_id', 'seen', 'text', 'deleted'];
	protected $touches=['thread'];
	
	public function thread(){
		return $this->belongsTo('\App\Thread');
	}
	public function user(){
		return $this->belongsTo('\App\User');
	}
	public function receiver(){
		return $this->hasOne('\App\User', 'id', 'receiver_id');
	}
}