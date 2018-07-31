<?php
/*
  Defines the middleware namespace.
*/
namespace App\Http\Middleware;

/*
  Defines the facades used by the middleware.
*/
use Closure;
use Illuminate\Support\Facades\Auth;

class Owner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        /*
          Any user that has a permission less than 1 cannot
          access and recieves a 403 Un authorized action.
        */
        if (Auth::user()->permission < 1 ) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
