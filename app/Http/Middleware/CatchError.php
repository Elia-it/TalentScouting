<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class CatchError
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
    if($request->has('error') && $request->input('error') == TRUE){
        return response()->json([
           'message' => 'error found'
        ], 400);
    }
        return $next($request);
    }


}
