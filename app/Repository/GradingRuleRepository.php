<?php

namespace App\Repository;

use App\Models\GradingRule;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Zus1\LaravelBaseRepository\Repository\LaravelBaseRepository;

class GradingRuleRepository extends LaravelBaseRepository
{
    protected const MODEL = GradingRule::class;

    public function create(array $data): GradingRule
    {
        $gradingRule = new GradingRule();
        $this->modifySharedData($gradingRule, $data);

        $this->associateTeacher($gradingRule);

        $gradingRule->save();

        return $gradingRule;
    }

    public function update(array $data,  GradingRule $gradingRule): GradingRule
    {
        $this->modifySharedData($gradingRule, $data);

        $gradingRule->save();

        return $gradingRule;
    }

    private function modifySharedData(GradingRule $gradingRule, array $data): void
    {
        $gradingRule->name = $data['name'];
        $gradingRule->description = $data['description'] ?? null;
    }

    private function associateTeacher(GradingRule $gradingRule): void
    {
        /** @var Teacher $teacher */
        $teacher = Auth::user();

        $gradingRule->teacher()->associate($teacher);
    }
}
