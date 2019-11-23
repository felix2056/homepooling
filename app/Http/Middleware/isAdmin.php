<?php

namespace App\Http\Middleware;

use Closure;

class isAdmin
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
		if (\Auth::user() && \Auth::user()->is_admin != 1 ){
			return redirect('/')->with('error','Sorry, you are not an Administrator');
		}
		return $next($request);
    }
}
