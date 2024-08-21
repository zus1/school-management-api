<?php

namespace App\Http\Controllers\Guardian;

use App\Models\Student;
use App\Models\Teacher;
use App\Repository\GuardianRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zus1\LaravelBaseRepository\Controllers\BaseCollectionController;
use Zus1\Serializer\Facade\Serializer;

class RetrieveCollection extends BaseCollectionController
{
    public function __construct(GuardianRepository $repository)
    {
        parent::__construct($repository);
    }

    public function __invoke(Request $request): JsonResponse
    {
        $auth = Auth::user();

        if($auth instanceof Teacher) {
            /** @var Student $student */
            foreach ($auth->students()->get() as $student) {
                $this->addCollectionRelation([
                    'relation' => 'students',
                    'field' => 'student.id',
                    'value' => $student->id,
                ]);
            }
        }

        $collection = $this->retrieveCollection($request);

        return new JsonResponse(Serializer::normalize($collection, 'guardian:collection'));
    }
}
