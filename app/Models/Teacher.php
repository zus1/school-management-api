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
 * @property int $school_class_id
 */
#[Attributes([
    ['id',
        'teacher:nestedSchoolClassCreate', 'teacher:nestedSchoolClassUpdate', 'teacher:retrieve',
        'teacher:collection', 'teacher:nestedSubjectEventCreate', 'teacher:nestedSubjectEventUpdate',
        'teacher:nestedSubjectEventRetrieve', 'user:nestedEventToggleNotify',
        'teacher:nestedSubjectEventCreate', 'teacher:nestedExamEventUpdate', 'teacher:nestedExamEventRetrieve',
        'teacher:nestedSubjectRetrieve', 'teacher:nestedSubjectToggleLecturer',
        'teacher:nestedTeacherSubjectRetrieve', 'teacher:nestedSubject', 'teacher:nestedGradeCreate',
        'teacher:nestedGradeCollection', 'teacher:nestedAttendanceCreate', 'teacher:nestedAttendanceCollection',
        'teacher:nestedAttendanceAggregate', 'teacher:nestedExamRetrieve', 'mediaOwner:nestedMediaUpload',
        'teacher:nestedTopAverageGrades'
    ],
    ['months_of_employment', 'user:register', 'user:me', 'teacher:update', 'teacher:retrieve'],
    ['employed_at', 'user:register', 'user:me', 'teacher:update', 'teacher:retrieve', 'teacher:collection'],
    ['employment_ends_at', 'user:register', 'user:me', 'teacher:update', 'teacher:retrieve', 'teacher:collection'],
    ['social_security_number', 'user:register', 'user:me', 'user:meUpdate', 'teacher:update', 'teacher:retrieve'],
    ['parent',
        'user:register', 'user:me', 'user:meUpdate', 'teacher:nestedSchoolClassCreate',
        'teacher:nestedSchoolClassUpdate', 'teacher:update', 'teacher:retrieve',
        'teacher:collection', 'teacher:nestedSubjectEventCreate', 'teacher:nestedSubjectEventUpdate',
        'teacher:nestedSubjectEventRetrieve', 'user:nestedEventToggleNotify',
        'teacher:nestedExamEventCreate', 'teacher:nestedExamEventUpdate', 'teacher:nestedExamEventRetrieve',
        'teacher:nestedSubjectRetrieve', 'teacher:nestedSubjectToggleLecturer',
        'teacher:nestedTeacherSubjectRetrieve', 'teacher:nestedTeacherSubjectCollection', 'teacher:nestedToggleLecturerClasses',
        'teacher:nestedSubject', 'teacher:nestedGradeCreate', 'teacher:nestedGradeCollection',
        'teacher:nestedAttendanceCreate', 'teacher:nestedAttendanceCollection', 'teacher:nestedAttendanceAggregate',
        'teacher:nestedExamRetrieve', 'mediaOwner:nestedMediaUpload', 'teacher:nestedTopAverageGrades'
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
        return $this->belongsToMany(Subject::class, 'teachers_subjects', 'teacher_id', 'subject_id');
            //->using(TeacherSubject::class);
    }

    public function hasStudent(Student $student): bool
    {
        return $this->students()->where('students.id', $student->id)->exists();
    }

    public function subjectEvents(): HasMany
    {
        return $this->hasMany(SubjectEvent::class, 'teacher_id', 'id');
    }

    public function lecturedSchoolClasses(): HasManyThrough
    {
        return $this->hasManyThrough(
            SchoolClass::class,
            SubjectEvent::class,
            'teacher_id',
            'id',
            'id',
            'school_class_id',
        );
    }

    public function lecturesSchoolClass(string $schoolClass): bool
    {
        return $this->lecturedSchoolClasses()->where('name', $schoolClass)->exists();
    }

    public function gradingRules(): HasMany
    {
        return $this->hasMany(GradingRule::class, 'teacher_id', 'id');
    }

    public function guardians(): HasManyThrough
    {
        return $this->hasManyThrough(
            Guardian::class,
            Student::class,
            'teacher_id',
            'id',
            'id',
            'guardian_id',
        );
    }
}
