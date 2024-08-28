<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Zus1\Serializer\Attributes\Attributes;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $max_capacity
 * @property int $floor
 * @property string $number
 * @property string $size
 * @property int $number_of_seats
 * @property string $purpose
 */
#[Attributes([
    ['id', 'classroom:nestedSubjectEventCreate', 'classroom:nestedSubjectEventUpdate', 'classroom:nestedSubjectEventRetrieve'],
    ['name', 'classroom:nestedSubjectEventCreate', 'classroom:nestedSubjectEventUpdate', 'classroom:nestedSubjectEventRetrieve'],
    ['floor', 'classroom:nestedSubjectEventCreate', 'classroom:nestedSubjectEventUpdate', 'classroom:nestedSubjectEventRetrieve'],
    ['number', 'classroom:nestedSubjectEventCreate', 'classroom:nestedSubjectEventUpdate','classroom:nestedSubjectEventRetrieve'],
])]
class Classroom extends Model
{
    use HasFactory;

    public function subjectEvents(): HasMany
    {
        return $this->hasMany(SubjectEvent::class, 'classroom_id', 'id');
    }
}
