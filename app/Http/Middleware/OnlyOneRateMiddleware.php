<?php

namespace App\Http\Middleware;

use App\Models\Rate;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class OnlyOneRateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $request->validate([
            'rating' => ['required', 'integer', 'between:0,5'],
            'apartment_id' => ['required', 'integer'],
        ]);

        if(!Rate::where('user_id', Auth::user()->id)->where('apartment_id', $request->apartment_id)->exists()){
            return $next($request);
        }
        return response()->json("You can rate this apartment only once", Response::HTTP_FORBIDDEN);
    }
}
