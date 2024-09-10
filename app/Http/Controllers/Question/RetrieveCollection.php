<?php

namespace App\Http\Controllers\Question;

use App\Models\Exam;
use App\Models\Student;
use App\Models\User;
use App\Repository\QuestionRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zus1\LaravelBaseRepository\Controllers\BaseCollectionController;
use Zus1\Serializer\Facade\Serializer;

class RetrieveCollection extends BaseCollectionController
{
    public function __construct(QuestionRepository $repository)
    {
        parent::__construct($repository);
    }

    public function __invoke(Request $request, Exam $exam): JsonResponse
    {
        /** @var User $auth */
        $auth = Auth::user();

        $this->addCollectionRelation([
            'relation' => 'exam',
            'field' => 'id',
            'value' => $exam->id,
        ]);

        if($auth instanceof Student) {
            $filters = $request->query('filters', []);
            $request->query->set('filters', [
                ...$filters,
                'active' => true,
            ]);
        }


        $collection = $this->retrieveCollection($request);

        return new JsonResponse(Serializer::normalize($collection, 'question:collection'));
    }
}
