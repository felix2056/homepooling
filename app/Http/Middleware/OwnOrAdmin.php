<?php

namespace App\Http\Middleware;

use Closure;

class OwnOrAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
	if (\Auth::check() && \Auth::user()->is_admin != 1){
		$params=$request->route()->parameters();
		if(array_key_exists('property',$params)){
			if($params['property']->user_id==\Auth::user()->id){
				return $next($request);
			}else{
				return redirect('/')->with('error','Ehi, you can\'t edit other user\'s properties!');
			}
		}
		if(array_key_exists('user',$params)){
			if($params['user']->id==\Auth::user()->id){
				return $next($request);
			}else{
				return redirect('/')->with('error','Ehi, you can\'t edit other user\'s profiles!');
			}
		}
		if(array_key_exists('wanted',$params)){
			if($params['wanted']->user_id==\Auth::user()->id){
				return $next($request);
			}else{
				return redirect('/')->with('error','Ehi, you can\'t edit other user\'s ads!');
			}
		}
		return redirect('/')->with('error','Ehi, you\'re not an Administrator !');
	}
	
	return $next($request);
    }
}
