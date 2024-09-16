<?php

namespace App\Repository;

use App\Models\GradeRange;
use App\Models\GradingRule;
use Zus1\LaravelBaseRepository\Repository\LaravelBaseRepository;

class GradeRangeRepository extends LaravelBaseRepository
{
    protected const MODEL = GradeRange::class;

    public function __construct(
        private GradingRuleRepository $gradingRuleRepository,
    ){
    }

    public function create(array $data, GradingRule $gradingRule): GradeRange
    {
        $gradeRange = new GradeRange();
        $this->modifySharedData($gradeRange, $data);

        $gradeRange->gradingRule()->associate($gradingRule);

        $gradeRange->save();

        return $gradeRange;
    }

    public function update(array $data, GradeRange $gradeRange): GradeRange
    {
        $this->modifySharedData($gradeRange, $data);

        if($data['grading_rule_id'] !== $gradeRange->grading_rule_id) {
            $this->associateGradingRule($gradeRange, $data['grading_rule_id']);
        }

        $gradeRange->save();

        return $gradeRange;
    }

    private function associateGradingRule(GradeRange $gradeRange, int $gradingRuleId): void
    {
        $gradingRule = $this->gradingRuleRepository->findOneByOr404(['id' => $gradingRuleId]);
        $gradeRange->gradingRule()->associate($gradingRule);
    }

    private function modifySharedData(GradeRange $gradeRange, array $data): void
    {
        $gradeRange->lower = $data['lower'];
        $gradeRange->upper = $data['upper'];
        $gradeRange->grade = $data['grade'];
    }
}
