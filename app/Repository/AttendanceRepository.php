<?php

namespace App\Repository;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AttendanceRepository extends SchoolDirectoryBaseRepository
{
    protected const MODEL = Attendance::class;

    private array $aggregateFields = [
        'teacher_id',
        'student_id',
        'school_class_id',
        'subject_id',
    ];

    public function create(array $data): Attendance
    {
        $attendance = new Attendance();
        $attendance->comment = $data['comment'];

        $this->setBaseProperties($attendance, $data);

        $attendance->save();

        return $attendance;
    }

    public function update(array $data, Attendance $attendance): Attendance
    {
        $attendance->comment = $data['comment'];

        $attendance->save();

        return $attendance;
    }

    public function aggregate(array $data, array $collectionRelations): Attendance
    {
        $builder = $this->getBuilder();

        $from = $this->extract($data, 'from');
        $to = $this->extract($data, 'to');

        $sanitized = $this->sanitizeForAggregate($data);
        $fields = array_keys($sanitized);

        $builder->select(DB::raw('COUNT(*) as count, '.implode(', ', $fields)));

        $this->addCollectionRelations($builder, $collectionRelations);

        $builder->whereBetween('created_at', [$from, $to]);

        foreach ($sanitized as $field => $value) {
            $relationshipMethod = $this->covertForeignKeyToMethod($field);

            $builder->where($field, $value)
                ->with($relationshipMethod);
        }

        foreach (array_keys($sanitized) as $key) {
            $builder->groupBy($key);
        }

        return $builder->first();
    }

    private function addCollectionRelations(Builder $builder, array $collectionRelations): void
    {
        $builder->where(function (Builder $builder) use ($collectionRelations) {
            foreach ($collectionRelations as $collectionRelation) {
                $builder->orWhereRelation(
                    $collectionRelation['relation'],
                    $collectionRelation['field'],
                    $collectionRelation['value'],
                );
            }
        });
    }

    private function extract(array &$data, string $field): mixed
    {
        $value = $data[$field] ?? null;

        if($value !== null) {
            unset($data['field']);
        }

        return $value;
    }

    private function sanitizeForAggregate(array $data): array
    {
        return array_intersect_key($data, array_flip($this->aggregateFields));
    }

    private function covertForeignKeyToMethod(string $foreignKeyField): string
    {
        return Str::camel(substr($foreignKeyField, 0, -(strlen('_id'))));
    }
}
