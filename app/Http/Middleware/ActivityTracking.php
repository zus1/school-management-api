<?php

namespace App\Http\Middleware;

use App\Models\Student;
use App\Repository\ActivityTrackingRepository;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ActivityTracking
{
    public function __construct(
        private ActivityTrackingRepository $repository,
    ){
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!Auth::user() instanceof Student) {
            return $next($request);
        }

        $this->repository->create(route: $this->getUriWithBindings($request));

        return $next($request);
    }

    private function getUriWithBindings(Request $request): string
    {
        $uri = $request->route()->uri();

        foreach ($request->route()->parameters() as $binding => $parameter) {
            if($parameter instanceof Model) {
                $replacement = $parameter->getAttribute('id');
            } else {
                $replacement = $parameter;
            }

            $uri = str_replace($binding, $replacement, $uri);
        }

        return $uri;
    }
}
