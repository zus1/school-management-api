<?php

namespace App\Http\Controllers\Student;

use App\Models\Guardian;
use App\Models\Teacher;
use App\Repository\StudentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zus1\LaravelBaseRepository\Controllers\BaseCollectionController;
use Zus1\Serializer\Facade\Serializer;

class RetrieveCollection extends BaseCollectionController
{
    public function __construct(StudentRepository $repository)
    {
        parent::__construct($repository);
    }

    public function __invoke(Request $request): JsonResponse
    {
        $auth = Auth::user();

        if($auth instanceof Teacher) {
            $this->addCollectionRelation([
                'relation' => 'teacher',
                'field' => 'id',
                'value' => $auth->id
            ]);
        }
        if($auth instanceof Guardian) {
            $this->addCollectionRelation([
                'relation' => 'guardian',
                'field' => 'id',
                'value' => $auth->id
            ]);
        }

        $collection = $this->retrieveCollection($request);

        return new JsonResponse(Serializer::normalize($collection, 'student:collection'));
    }
}
