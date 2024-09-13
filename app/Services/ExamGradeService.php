<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\ExamResponse;
use App\Models\ExamSession;
use App\Models\GradeRange;
use App\Models\Question;
use App\Repository\ExamResponseRepository;
use App\Repository\ExamSessionRepository;
use App\Repository\GradeRepository;
use App\Repository\QuestionRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExamGradeService
{
    private array $questionMap;
    private array $responsesMap;

    public function __construct(
        private ExamSessionRepository $examSessionRepository,
        private QuestionRepository $questionRepository,
        private ExamResponseRepository $examResponseRepository,
        private GradeRepository $gradeRepository,
    ){
    }

    public function gradeBulk(array $examSessionResponses, ExamSession $examSession): ExamSession
    {
        $this->loadResponses($examSessionResponses['responses']);
        $this->loadQuestions();

        $achievedPoints = $this->handleResponses($examSessionResponses['responses']);

        /** @var Exam $exam */
        $exam = $examSession->exam()->first();

        $gradedExamSession = $this->handelExamSessionGrade(
            examSession: $examSession,
            exam: $exam,
            achievedPoints: $achievedPoints,
            comment: $examSessionResponses['comment'] ?? null
        );

        $this->gradeRepository->create([
            'grade' => $examSession->grade,
            'comment' => $examSessionResponses['comment'],
            'student_id' => $examSession->student_id,
            'subject_id' => $exam->subject_id,
        ]);

        return $gradedExamSession;
    }

    private function handleResponses(array $examSessionResponses): int
    {
        $achievedPoints = 0;
        $processedResponses = [];
        foreach ($examSessionResponses as $examSessionResponse) {
            $processedResponses[] = $this->processResponse($examSessionResponse, $achievedPoints);
        }
        ExamResponse::massUpdate($processedResponses);

        return $achievedPoints;
    }

    private function handelExamSessionGrade(
        ExamSession $examSession,
        Exam $exam,
        int $achievedPoints,
        ?string $comment
    ): ExamSession {
        $grade = $this->calculateGrade($exam, $achievedPoints);

        return $this->examSessionRepository->grade(
            examSession: $examSession,
            exam: $exam,
            grade: $grade,
            achievedPoints: $achievedPoints,
            comment: $comment,
        );
    }

    private function calculateGrade(Exam $exam, int $achievedPointsPoints): int
    {
        $gradeRanges = $exam->gradeRanges()->get();

        $grade = 0;
        /** @var GradeRange $gradeRange */
        foreach ($gradeRanges as $gradeRange) {
            if($gradeRange->lower <= $achievedPointsPoints && $gradeRange->upper >= $achievedPointsPoints) {
                $grade = $gradeRange->grade;

                break;
            }
        }

        if($grade === 0) {
            throw new HttpException(500, 'Could not calculate the grade');
        }

        return $grade;
    }

    private function processResponse(array $examSessionResponse, int &$achievedPoints): ExamResponse
    {
        /** @var ExamResponse $responseObj */
        $responseObj = $this->responsesMap[$examSessionResponse['id']];
        /** @var Question $questionObj */
        $questionObj = $this->questionMap[$responseObj->question_id];

        if($examSessionResponse['is_correct'] === true) {
            $achievedPoints += $questionObj->points;
            $responseObj->is_correct = true;
        } else {
            $responseObj->is_correct = false;
        }

        $responseObj->comment = $examSessionResponse['comment'] ?? null;

        return $responseObj;
    }

    private function loadResponses(array $examSessionResponsesArr): void
    {
        $ids = collect($examSessionResponsesArr)->pluck('id')->all();
        $responses = $this->examResponseRepository->findByIds($ids);

        $responses->each(function (ExamResponse $response) {
            $this->responsesMap[$response->id] = $response;
        });
    }

    private function loadQuestions(): void
    {
        $responseIds = array_keys($this->responsesMap);

        $questions = $this->questionRepository->findByExamResponseIds($responseIds);

        $questions->each(function (Question $question) {
           $this->questionMap[$question->id] = $question;
        });
    }
}
