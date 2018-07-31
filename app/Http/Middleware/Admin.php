<?php
/*
  Defines the middleware namespace
*/
namespace App\Http\Middleware;

/*
  Defines the facades used by the controller.
*/
use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
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
          Any user with a permission less than 2 is not an admin and
          receives a 403 un authorized action response.
        */
        if (Auth::user()->permission < 2 ) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
