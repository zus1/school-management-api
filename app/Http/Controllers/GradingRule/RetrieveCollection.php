<?php

namespace App\Http\Controllers\GradingRule;

use App\Models\Teacher;
use App\Repository\GradingRuleRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zus1\LaravelBaseRepository\Controllers\BaseCollectionController;
use Zus1\Serializer\Facade\Serializer;

class RetrieveCollection extends BaseCollectionController
{
    public function __construct(GradingRuleRepository $repository)
    {
        parent::__construct($repository);
    }

    public function __invoke(Request $request): JsonResponse
    {
        /** @var Teacher $auth */
        $auth = Auth::user();

        $this->addCollectionRelation([
            'relation' => 'teacher',
            'field' => 'id',
            'value' => $auth->id,
        ]);

        $collection = $this->retrieveCollection($request);

        return new JsonResponse(Serializer::normalize($collection, 'gradingRule:collection'));
    }
}
