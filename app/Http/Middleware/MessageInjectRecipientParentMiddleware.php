<?php

namespace App\Http\Middleware;

use App\Constant\MessageUserType;
use App\Constant\UserType;
use App\Models\User;
use App\Repository\UserRepository;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class MessageInjectRecipientParentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $childType = $request->query(MessageUserType::withSuffix(MessageUserType::RECIPIENT));

        if($childType === null) {
            return $next($request);
        }

        $senderId = $request->route()->parameter(MessageUserType::RECIPIENT);
        /** @var UserRepository $childRepository */
        $childRepository = App::make(UserType::repositoryClass($childType));
        /** @var User $child */
        $child = $childRepository->findOneByOr404(['id' => $senderId]);
        /** @var User $parent */
        $parent = $child->parent()->first();

        $request->route()->setParameter(MessageUserType::RECIPIENT, $parent);

        return $next($request);
    }
}
