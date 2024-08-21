<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Zus1\Discriminator\Observers\DiscriminatorObserver;
use Zus1\Serializer\Attributes\Attributes;

/**
 * @property int $id
 * @property string $months_of_employment
 * @property string $employed_at
 * @property string $employment_ends_at
 * @property string $social_security_number
 */
#[Attributes([
    ['id', 'teacher:nestedSchoolClassCreate', 'teacher:nestedSchoolClassUpdate', 'teacher:retrieve', 'teacher:collection'],
    ['months_of_employment', 'user:register', 'user:me', 'teacher:update', 'teacher:retrieve'],
    ['employed_at', 'user:register', 'user:me', 'teacher:update', 'teacher:retrieve', 'teacher:collection'],
    ['employment_ends_at', 'user:register', 'user:me', 'teacher:update', 'teacher:retrieve', 'teacher:collection'],
    ['social_security_number', 'user:register', 'user:me', 'user:meUpdate', 'teacher:update', 'teacher:retrieve'],
    ['parent',
        'user:register', 'user:me', 'user:meUpdate', 'teacher:nestedSchoolClassCreate',
        'teacher:nestedSchoolClassUpdate', 'teacher:update', 'teacher:retrieve', 'teacher:collection'
    ],
])]
#[ObservedBy(DiscriminatorObserver::class)]
class Teacher extends User
{
    use HasFactory;

    const CONTRACT_DURATION = 4; //years

    public $timestamps = false;

    public function students(): HasManyThrough
    {
        return $this->hasManyThrough(
            Student::class,
            SchoolClass::class,
            'teacher_id',
            'school_class_id',
            'id',
            'id',
        );
    }

    public function schoolClass(): HasOne
    {
        return $this->hasOne(SchoolClass::class, 'teacher_id', 'id');
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'teachers_subjects', 'teacher_id', 'subject_id')
            ->using(TeacherSubject::class);
    }

    public function hasStudent(Student $student): bool
    {
        return $this->students()->where('students.id', $student->id)->exists();
    }
}
