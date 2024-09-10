<?php

namespace App\Http\Requests;

use App\Constant\QuestionType;
use App\Constant\RouteName;
use App\Http\Requests\Rules\AnswerRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\HttpException;

class QuestionRequest extends FormRequest
{
    public function __construct(
        private AnswerRules $answerRules,
    ){
        parent::__construct();
    }

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
        if($this->route()->action['as'] === RouteName::QUESTIONS_CREATE) {
            return $this->createRules();
        }
        if($this->route()->action['as'] === RouteName::QUESTION_UPDATE) {
            return $this->updateRules();
        }

        throw new HttpException(422, 'Unprocessable entity');
    }

    private function createRules(): array
    {
        return [
            'questions' => 'required|array:question,points,type,answers',
            'questions.*.question' => $this->questionRule(),
            'questions.*.points' => $this->pointsRule(),
            'questions.*.type' => $this->typeRule(),
            'questions.*.answers' => 'array:answer,position',
            'questions.*.answers.*.answer' => $this->answerRules->answerRules(),
            'questions.*.answers.*.position' => $this->answerRules->positionRules(),
        ];
    }

    private function updateRules(): array
    {
        return [
            'question' => $this->questionRule(),
            'points' => $this->pointsRule(),
            'type' => $this->typeRule(),
            'exam_id' => 'required|int|exists:exams,id'
        ];
    }

    private function questionRule(): string
    {
        return 'required|string|max:500';
    }

    private function pointsRule(): string
    {
        return 'required|int';
    }

    private function typeRule(): array
    {
        return [
            'required',
            Rule::in(QuestionType::getValues()),
        ];
    }
}
