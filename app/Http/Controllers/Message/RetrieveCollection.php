<?php

namespace App\Http\Controllers\Message;

use App\Http\Requests\MessageRequest;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Illuminate\Http\JsonResponse;
use Zus1\LaravelBaseRepository\Controllers\BaseCollectionController;
use Zus1\Serializer\Facade\Serializer;

class RetrieveCollection extends BaseCollectionController
{
    public function __construct(
        private UserRepository $userRepository,
        MessageRepository $repository
    ) {
        parent::__construct($repository);
    }

    public function __invoke(MessageRequest $request): JsonResponse
    {
        $auth = $this->userRepository->findAuthParent();

        $this->addCollectionRelation([
            'relation' => $request->query('requester_type'),
            'field' => 'id',
            'value' => $auth->id,
        ]);

        $collection = $this->retrieveCollection($request);

        return new JsonResponse(Serializer::normalize($collection, ['message:collection', 'user:nestedMessageCollection']));
    }
}
