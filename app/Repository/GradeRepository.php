<?php

namespace App\Repository;

use App\Constant\Analytics\DatePeriod;
use App\Constant\Analytics\DateUnit;
use App\Constant\Analytics\GradeType;
use App\Filters\AuthRelationFilters;
use App\Models\Grade;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class GradeRepository extends SchoolDirectoryBaseRepository
{
    protected const MODEL = Grade::class;

    public function __construct(
        private AuthRelationFilters $authRelationFilters,
    ){
    }

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

    public function findTopAverageGrades(array $data): Collection
    {
        $builder = $this->getBuilder();

        $sanitizedColumns = $this->sanitizeGradeAnalyticsWheres($data);

        $builder->select(DB::raw(sprintf('AVG(grade) as avg, %s', implode(', ', array_keys($sanitizedColumns)))));
        $this->addWheresForTopGrades($builder, $data, $sanitizedColumns);

        return $builder->groupBy(array_keys($sanitizedColumns))
            ->orderBy('avg', 'DESC')
            ->limit($data['limit'])
            ->get();
    }

    private function addWheresForTopGrades(Builder $builder, array $data, array $sanitizedColumns): void
    {
        $dateBoundaries = $this->getDateBoundariesForGradeAnalytics($data);
        $timeUnit = $this->getTimeUnitFroGradeAnalytics($data);

        $builder->whereBetween(
            sprintf("DATE_FORMAT(created_at, '%s')", DateUnit::getFormat($timeUnit)['db']),
            [$dateBoundaries['from'], $dateBoundaries['to']]
        )->where('is_final', false);

        foreach ($sanitizedColumns as $column => $value) {
            $relation = substr($column, 0, -strlen('_id'));
            $builder->with($relation);

            if($value === null) {
                continue;
            }

            $builder->where($column, $value);
        }
    }

    public function findForGradeAnalytics(array $data): Collection
    {
        $builder = $this->getBuilder();

        $this->addSelectForGradeAnalytics($builder, $data);
        $this->authRelationFilters->setForRepository($builder);
        $this->addWheresForGradeAnalytics($builder, $data);

        $collection = $builder->groupBy('date')->get();

        return $this->addMissingDates($collection, $data['unit']);
    }

    private function addMissingDates(Collection $dates, string $unit): Collection
    {
        if($dates->isEmpty()) {
            return $dates;
        }

        $firstEntryKeys = array_keys($datesArr = $dates->toArray()[0]);
        $dateAndValue = $dates->pluck($firstEntryKeys[0], 'date')->all();

        $all = DateUnit::getFullPeriod(new Carbon(array_key_first($dateAndValue)), $unit);


        foreach ($all as $date) {
            if(!array_key_exists($date, $dateAndValue)) {
                $dates->add([
                    $firstEntryKeys[0] => 0, //min, max or avg
                    'date' => $date,
                ]);
            }
        }

        usort($datesArr, function (array $dateArr1, array $dateArr2) {
            return $dateArr1['date'] > $dateArr2['date'];
        });

        return new Collection($datesArr);
    }

    private function addSelectForGradeAnalytics(Builder $builder, array $data): void
    {
        $unit = $this->getTimeUnitFroGradeAnalytics($data);
        $gradeType = $data['type'];

        $select = '';

        if($gradeType === GradeType::AVG) {
            $select.= 'AVG(grade) as average,';
        }
        if($gradeType === GradeType::MAX) {
            $select.= 'MAX(grade) as max,';
        }
        if($gradeType === GradeType::MIN) {
            $select.= 'MIN(grade) as min,';
        }

        $select.= sprintf(" DATE_FORMAT('created_at', '%s') as date", DateUnit::getFormat($unit)['db']);

        $builder->select(DB::raw($select));
    }

    private function addWheresForGradeAnalytics(Builder $builder, array $data): void
    {
        $unit = $this->getTimeUnitFroGradeAnalytics($data);

        $dateBoundaries = $this->getDateBoundariesForGradeAnalytics($data);
        $column  = sprintf("DATE_FORMAT(created_at, '%s')", DateUnit::getFormat($unit)['db']);

        $wheres = $this->sanitizeGradeAnalyticsWheres($data);

        $builder->whereBetween($column, [$dateBoundaries['from'], $dateBoundaries['to']])
            ->where('is_final', false);

        foreach ($wheres as $field => $value) {
            $builder->where($field, $value);
        }
    }

    private function sanitizeGradeAnalyticsWheres(array $data): array
    {
        return array_filter($data, function (string $key) {
            return in_array($key, ['student_id', 'teacher_id', 'subject_id', 'class_id']);
        }, ARRAY_FILTER_USE_KEY);

    }

    private function getDateBoundariesForGradeAnalytics(array $data): array
    {
        $unit = $this->getTimeUnitFroGradeAnalytics($data);

        if(isset($data['period'])) {
            $boundaries = DatePeriod::boundaries($data['period']);
        } else {
            $boundaries = [
                'from' => new Carbon($data['from']),
                'to' => new Carbon($data['to']),
            ];
        }

        return [
            'from' => $boundaries['from']->format(DateUnit::getFormat($unit)['format']),
            'to' => $boundaries['to']->format(DateUnit::getFormat($unit)['format']),
        ];
    }

    private function getTimeUnitFroGradeAnalytics(array $data)
    {
        return isset($data['period']) ? DatePeriod::unit($data['period']) : $data['unit'];
    }

    private function modifyShardData(Grade $grade, array $data): void
    {
        $grade->grade = $data['grade'];
        $grade->comment = $data['comment'] ?? null;
        $grade->is_final = $data['is_final'] ?? false;
    }
}
