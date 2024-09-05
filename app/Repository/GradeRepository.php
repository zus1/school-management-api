<?php

namespace App\Repository;

use App\Models\Grade;

class GradeRepository extends SchoolDirectoryBaseRepository
{
    protected const MODEL = Grade::class;

    public function create(array $data): Grade
    {
        $grade = new Grade();
        $this->modifyShardData($grade, $data);

        $this->setBaseProperties($grade, $data);

        $grade->save();

        return $grade;
    }

    public function update(array $data, Grade $grade): Grade
    {
        $this->modifyShardData($grade, $data);

        $grade->save();

        return $grade;
    }

    private function modifyShardData(Grade $grade, array $data): void
    {
        $grade->grade = $data['grade'];
        $grade->comment = $data['comment'] ?? null;
        $grade->is_final = $data['is_final'] ?? false;
    }
}
