<?php

namespace App\Repository;

use App\Models\Exam;
use App\Models\GradingRule;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Zus1\LaravelBaseRepository\Repository\LaravelBaseRepository;

class ExamRepository extends LaravelBaseRepository
{
    protected const MODEL = Exam::class;

    public function __construct(
        private SubjectRepository $subjectRepository,
        private GradingRuleRepository $gradingRuleRepository,
    ){
    }
    public function create(array $data): Exam
    {
        $exam = new Exam();
        $this->modifySharedData($exam, $data);

        $this->associateTeacher($exam);
        $this->associateSubject($exam, $data['subject_id']);

        if(isset($data['grading_rule_id'])) {
            $this->associateGradingRule($exam, $data['grading_rule_id']);
        }

        $exam->save();

        return $exam;
    }

    public function update(array $data, Exam $exam): Exam
    {
        $this->modifySharedData($exam, $data);

        if($exam->subject_id !== $data['subject_id']) {
            $this->associateSubject($exam, $data['subject_id']);
        }

        $exam->save();

        return $exam;
    }

    public function addQuestions(Exam $exam, array $questions, int $totalPoints): Exam
    {
        $exam->total_points = $totalPoints;

        $this->checkIfTotalPointsMatchGradingRule($exam);

        $exam->questions()->saveMany($questions);

        $exam->save();

        return $exam;
    }

    public function increaseTotalPoints(Exam $exam, int $increaseValue): Exam
    {
        $exam->total_points += $increaseValue;
        $exam->grading_rule_id = null;

        $exam->save();

        return $exam;
    }

    public function decreaseTotalPoints(Exam $exam, int $decreaseValue): Exam
    {
        $exam->total_points -= $decreaseValue;
        $exam->grading_rule_id = null;

        $exam->save();

        return $exam;
    }

    public function toggleGradingRule(Exam $exam, string $action, ?GradingRule $gradingRule): Exam
    {
        if($action === 'add') {
            $this->checkIfTotalPointsMatchGradingRule($exam, $gradingRule);
            $exam->gradingRule()->associate($gradingRule);
        }
        if($action === 'remove') {
            $exam->gradingRule()->disassociate();
        }

        $exam->save();

        return $exam;
    }

    public function toggleAllowedSchoolClass(Exam $exam, string $schoolClass, string $action): Exam
    {
        $exam->school_classes_allowed_access = $exam->school_classes_allowed_access ?? [];

        if($action === 'add' && !in_array($schoolClass, $exam->school_classes_allowed_access)) {
            $exam->school_classes_allowed_access[] = $schoolClass;
        }
        if($action === 'remove' && in_array($schoolClass, $exam->school_classes_allowed_access)) {
            unset($exam->school_classes_allowed_access[array_search($schoolClass, $exam->school_classes_allowed_access)]);
        }

        $exam->save();

        return $exam;
    }

    private function checkIfTotalPointsMatchGradingRule(Exam $exam, ?GradingRule $gradingRule = null): void
    {
        if($exam->total_points === 0) {
            return;
        }

        $maxGradeRange = $this->determineMaxGradingRange($exam, $gradingRule);

        if($maxGradeRange === null) {
            return;
        }

        if($maxGradeRange !== $exam->total_points) {
            throw new HttpException(400, sprintf(
                'Grading rule with max range of %d added to exam, but trying to add total points of %d which '.
                'does not match that max value',
                $maxGradeRange,
                $exam->total_points
            ));
        }
    }

    private function determineMaxGradingRange(Exam $exam, ?GradingRule $gradingRule): ?int
    {
        if($gradingRule !== null) {
            return $gradingRule->maxRange();
        }

        return $exam->maxGradeRange();
    }

    private function modifySharedData(Exam $exam, array $data): void
    {
        $startsAt = new Carbon($data['starts_at']);
        $endsAt = new Carbon($data['ends_at']);

        $exam->title = $data['title'];
        $exam->description = $data['description'] ?? null;
        $exam->starts_at = $startsAt->format('Y-m-d H:i:s');
        $exam->ends_at = $endsAt->format('Y-m-d H:i:s');
        $exam->duration = $startsAt->diffInMinutes($endsAt);
    }

    private function associateTeacher(Exam $exam): void
    {
        /** @var Teacher $auth */
        $auth = Auth::user();

        $exam->teacher()->associate($auth);
    }

    private function associateSubject(Exam $exam, int $subjectId): void
    {
        $subject = $this->subjectRepository->findOneByOr404(['id' => $subjectId]);
        $exam->subject()->associate($subject);
    }

    private function associateGradingRule(Exam $exam, int $gradingRuleId): void
    {
        $gradingRule = $this->gradingRuleRepository->findOneByOr404(['id' => $gradingRuleId]);
        $exam->gradingRule()->associate($gradingRule);
    }
}
