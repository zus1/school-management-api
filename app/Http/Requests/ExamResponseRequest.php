<?php

namespace App\Http\Requests;

use App\Constant\ExamSessionStatus;
use App\Constant\QuestionType;
use App\Constant\RouteName;
use App\Models\ExamSession;
use App\Models\Guardian;
use App\Models\Question;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExamResponseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if($this->route()->action['as'] === RouteName::EXAM_RESPONSE_CREATE) {
            return $this->createRules();
        }
        if($this->route()->action['as'] === RouteName::EXAM_RESPONSE_UPDATE) {
            return $this->updateRules();
        }
        if($this->route()->action['as'] === RouteName::EXAM_RESPONSES) {
            return $this->collectionRules();
        }
        if($this->route()->action['as'] === RouteName::EXAM_RESPONSE) {
            $this->additionalRetrieveRules();
        }

        throw new HttpException(422, 'Unprocessable entity');
    }

    private function createRules(): array
    {
        $rules = [
            'question_id' => 'required|integer|exists:questions,id',
            'answer_id' => $this->answerRules(),
        ];


        return $this->addResponseRules($rules);
    }

    private function updateRules(): array
    {
        $rules = [
            'answer_id' => $this->answerRules(),
        ];

        return $this->addResponseRules($rules);
    }

    private function collectionRules(): array
    {
        $this->additionalCollectionRules();

        return [
            'filters' => 'required|array:examSession.status',
        ];
    }

    private function additionalCollectionRules(): void
    {
        $auth = Auth::user();

        $filters = $this->query('filters', []);
        if($filters === []) {
            return; //let it fail later
        }

        $this->sessionStatusRules(
            status: $filters['examSession.status'],
            for: 'collection',
            exceptionMessageParameter: $auth::class
        );
    }

    private function additionalRetrieveRules(): void
    {
        $examResponse = $this->route('examResponse');
        /** @var ExamSession $session */
        $session = $examResponse->examSession()->first();

        $this->sessionStatusRules(
            status: $session->status,
            for: 'retrieve',
            exceptionMessageParameter: $session->status
        );
    }

    private function sessionStatusRules(string $status, string $for, string $exceptionMessageParameter): void
    {
        $auth = Auth::user();

        if(
            $auth instanceof Teacher &&
            in_array($status, [ExamSessionStatus::GRADED, ExamSessionStatus::PENDING_GRADE]) === false
        ) {
            $this->throwSessionStatusException(for: $for, messageParameter: $exceptionMessageParameter);
        }

        if(
            $auth instanceof Student &&
            in_array($status, [ExamSessionStatus::GRADED, ExamSessionStatus::IN_PROGRESS]) === false
        ) {
            $this->throwSessionStatusException(for: $for, messageParameter: $exceptionMessageParameter);
        }

        if($auth instanceof Guardian && $status !== ExamSessionStatus::GRADED) {
            $this->throwSessionStatusException(for: $for, messageParameter: $exceptionMessageParameter);
        }
    }

    private function throwSessionStatusException(string $for, string $messageParameter): void
    {
        if($for === 'collection') {
            throw new HttpException(422, 'Can not retrieve response for session in status'. $messageParameter);
        }
        if($for === 'retrieve') {
            throw new HttpException(422, 'invalid filter for '.$messageParameter);
        }
    }


    private function addResponseRules(array $rules): array
    {
        /** @var ?Question $question */
        $question = $this->route()->parameter('question_id');
        if($question === null) {
            return $rules;
        }

        if($question->type === QuestionType::TEXT) {
            $rules['response'] = 'required|string|max:2000';
        }
        if($question->type === QuestionType::ESSAY) {
            $rules['response'] = 'required|string|max:20000';
        }

        return $rules;
    }

    private function answerRules(): string
    {
        return 'integer|exists:answers,id';
    }
}
