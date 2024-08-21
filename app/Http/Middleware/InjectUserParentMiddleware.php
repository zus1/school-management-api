<?php

namespace App\Http\Middleware;

use App\Constant\UserType;
use App\Models\User;
use App\Repository\UserRepository;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class InjectUserParentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userType = $request->query('user_type');
        $userId = $request->route()->parameter('user');

        /** @var UserRepository $repository */
        $repository = App::make(UserType::repositoryClass($userType));
        /** @var User $user */
        $user = $repository->findOneByOr404(['id' => $userId]);

        $request->route()->setParameter('user', $user->parent()->first());

        return $next($request);
    }
}
