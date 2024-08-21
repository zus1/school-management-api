<?php

namespace App\Http\Controllers\Me;

use App\Repository\GuardianRepository;
use Illuminate\Http\JsonResponse;

class Delete
{
    public function __construct(
        private GuardianRepository $repository
    ){
    }

    public function __invoke(): JsonResponse
    {
        $this->repository->delete();

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
