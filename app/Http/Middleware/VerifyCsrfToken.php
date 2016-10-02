<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Contracts\Auth\Guard;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except  = [
      // '/uber/v1/*',
    ];

    private $openRoutes = 
    [
        '/uber/v1/savetoken'
    ];

    $routes = [
        route('uber/v1/savetoken', [], false)
    ];

   public function handle($request, Closure $next)
    {
        foreach($this->openRoutes as $route)
        {
           if(in_array($request->path(), $this->openRoutes))
            return $next($request);
        }

        return parent::handle($request, $next);
    }
}
