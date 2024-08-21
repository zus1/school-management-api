<?php

namespace App\Http\Controllers\Guardian;

use App\Http\Requests\GuardianRequest;
use App\Models\Guardian;
use App\Repository\GuardianRepository;
use Illuminate\Http\JsonResponse;
use Zus1\Serializer\Facade\Serializer;

class Update
{
    public function __construct(
        private GuardianRepository $repository
    ){
    }

    public function __invoke(GuardianRequest $request, Guardian $guardian): JsonResponse
    {
        $guardian = $this->repository->update($request->input(), $guardian);

        return new JsonResponse(Serializer::normalize($guardian, 'guardian:update'));
    }
}
