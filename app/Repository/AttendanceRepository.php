<?php

namespace App\Repository;

use App\Constant\Pagination;
use App\Models\Attendance;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
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

    public function aggregate(array $data, array $collectionRelations): LengthAwarePaginator
    {
        $builder = $this->getBuilder();

        $sanitized = $this->sanitizeForAggregate($data);
        $fields = array_keys($sanitized);

        $builder->select(DB::raw('COUNT(*) as count, '.implode(', ', $fields)));

        $this->addCollectionRelations($builder, $collectionRelations);
        $this->aggregateAddWheres($builder, $data, $sanitized);
        $this->aggregateAddWithRelation($builder, $fields);
        $this->aggregateAddGroupBy($builder, $fields);

        return $builder->paginate($this->extract($data, 'per_page') ?? Pagination::DEFAULT_PER_PAGE);
    }

    private function aggregateAddWheres(Builder $builder, array $data, array $sanitizedData): void
    {
        $from = $this->extract($data, 'from');
        $to = $this->extract($data, 'to');
        $wheres = $this->extractWheres($sanitizedData);
        
        $builder->whereBetween('created_at', [$from, $to]);
        foreach ($wheres as $field => $value) {
            $builder->where($field, $value);
        }
    }

    private function aggregateAddWithRelation(Builder $builder, array $fields): void
    {
        foreach ($fields as $field) {
            $builder->with($this->covertForeignKeyToMethod($field));
        }
    }

    private function aggregateAddGroupBy(Builder $builder, array $fields): void
    {
        foreach ($fields as $key) {
            $builder->groupBy($key);
        }
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

    private function extractWheres(array $data): array
    {
        return array_filter($data, function (string $value) {
            return (int)$value !== 0;
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
