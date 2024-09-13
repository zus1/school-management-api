<?php

namespace App\Http\Requests;

use App\Constant\ExamSessionStatus;
use App\Constant\RouteName;
use App\Models\ExamSession;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExamSessionRequest extends FormRequest
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
        if($this->route()->action['as'] === RouteName::EXAM_SESSIONS) {
            return $this->collectionRules();
        }
        if($this->route()->action['as'] === RouteName::EXAM_SESSION) {
            return $this->retrieveRules();
        }
        if($this->route()->action['as'] === RouteName::EXAM_SESSION_GRADE) {
            return $this->gradeRules();
        }

        throw new HttpException(422, 'Unprocessable entity');
    }

    private function collectionRules(): array
    {
        $this->additionalCollectionRules();

        return [
            'filters' => 'required|array',
            'filters.status' => [
                'required',
                Rule::in(ExamSessionStatus::getValues())
            ]
        ];
    }

    private function retrieveRules(): array
    {
        $this->additionalRetrieveRules();

        return [];
    }

    private function gradeRules(): array
    {
        return [
            'comment' => 'string|max:500',
            'responses' => 'required|array:id,is_correct,comment',
            'responses.*.id' => 'required|integer|exists:exam_responses,id',
            'responses.*.is_correct' => ['required', 'boolean', Rule::in(true, false)],
            'responses.*.comment' => 'string|max:300',
        ];
    }

    private function additionalRetrieveRules(): void
    {
        /** @var ExamSession $examSession */
        $examSession = $this->route()->parameter('examSession');

        $this->statusRules($examSession->status);
    }

    private function additionalCollectionRules(): void
    {
        $filters = $this->query('filters', []);
        if($filters === []) {
            return;
        }

        $status = $filters['status'] ?? null;
        if($status === null) {
            return;
        }

        $this->statusRules($status);
    }

    private function statusRules(string $status)
    {
        $auth = Auth::user();

        if(
            $auth instanceof Teacher &&
            !in_array($status, [ExamSessionStatus::PENDING_GRADE, ExamSessionStatus::PENDING_GRADE])
        ) {
            throw new HttpException(400, 'Can not retrieve exam session in status '. $status);
        }

        if($auth instanceof Student && !in_array($status, [ExamSessionStatus::GRADED, ExamSessionStatus::PENDING_GRADE])) {
            throw new HttpException(400, 'Can not retrieve exam session in status '. $status);
        }

        if($auth instanceof Guardian && $status !== ExamSessionStatus::GRADED) {
            throw new HttpException(400, 'Can not retrieve exam session in status '. $status);
        }
    }
}
