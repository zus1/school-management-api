<?php

namespace App\Http\Middleware;

use App\Constant\Media\MediaOwner;
use App\Repository\UserRepository;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;
use Zus1\LaravelBaseRepository\Repository\LaravelBaseRepository;

class InjectMediaOwnerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ownerId = $request->route('owner');
        $ownerType = $request->query('owner_type');

        if($ownerId === null || $ownerType === MediaOwner::USER) {
            /** @var UserRepository $ownerRepository */
            $ownerRepository = App::make(UserRepository::class);
            $owner = $ownerRepository->findAuthParent();
        } else {
            /** @var LaravelBaseRepository $ownerRepository */
            $ownerRepository = App::make(MediaOwner::repository($request->query('owner_type')));
            $owner = $ownerRepository->findOneByOr404(['id' => $ownerId]);
        }

        $request->route()->setParameter('owner', $owner);

        return $next($request);
    }
}
