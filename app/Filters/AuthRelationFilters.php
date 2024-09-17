<?php

namespace App\Filters;

use App\Constant\Roles;
use App\Http\Controllers\CustomBaseCollectionController;
use App\Models\Guardian;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class AuthRelationFilters
{
    private User $auth;

    public function __construct()
    {
        /** @var User $user */
        $user = Auth::user();

        $this->auth = $user;
    }

    public function setForController(CustomBaseCollectionController $controller): void
    {
        if($this->auth->hasRole(Roles::ADMIN)) {
            return;
        }

        if($this->auth->hasRole(Roles::STUDENT)) {
            $controller->customAddCollectionRelation([
                'relation' => 'student',
                'field' => 'id',
                'value' => $this->auth->id,
            ]);
        }

        if($this->auth instanceof Guardian && $this->auth->hasRole(Roles::GUARDIAN)) {
            $students = $this->auth->students()->get();
            /** @var Student $student */
            foreach ($students as $student) {
                $controller->customAddCollectionRelation([
                    'relation' => 'student',
                    'field' => 'id',
                    'value' => $student->id,
                ]);
            }
        }

        if($this->auth instanceof Teacher && $this->auth->hasRole(Roles::TEACHER)) {
            $controller->customAddCollectionRelation([
                'relation' => 'teacher',
                'field' => 'id',
                'value' => $this->auth->id,
            ]);

            $students = $this->auth->students()->get();
            /** @var Student $student */
            foreach ($students as $student) {
                $controller->customAddCollectionRelation([
                    'relation' => 'student',
                    'field' => 'id',
                    'value' => $student->id,
                ]);
            }
        }
    }

    public function setForRepository(Builder $builder): void
    {
        if($this->auth->hasRole(Roles::ADMIN)) {
            return;
        }

        if($this->auth instanceof Teacher && $this->auth->hasRole(Roles::TEACHER)) {
            $students = $this->auth->students()->get();

            $builder->where(function (Builder $builder) use ($students) {
                /** @var Student $student */
                foreach ($students as $student) {
                    $builder->orWhereRelation('student', 'id', $student->id);
                }
                $builder->orWhereRelation('teacher', 'id', $this->auth->id);
            });
        }

        if($this->auth->hasRole(Roles::STUDENT)) {
            $builder->whereRelation('student', 'id', $this->auth->id);
        }

        if($this->auth instanceof Guardian && $this->auth->hasRole(Roles::GUARDIAN)) {
            $students = $this->auth->students()->get();

            $builder->where(function (Builder $builder) use ($students) {
                /** @var Student $student */
                foreach ($students as $student) {
                    $builder->orWhereRelation('student', 'id', $student->id);
                }
            });
        }
    }

    public function setForActivityTrackingCollectionController(CustomBaseCollectionController $controller): void
    {
        if($this->auth instanceof Teacher && $this->auth->hasRole(Roles::TEACHER)) {
            $students = $this->auth->students()->get();
            $lecturedClasses = $this->auth->lecturedSchoolClasses()->with('students')->get();

            /** @var SchoolClass $lecturedClass */
            foreach ($lecturedClasses as $lecturedClass) {
                $students->merge($lecturedClass->students);
            }

            /** @var Student $student */
            foreach ($students as $student) {
                $controller->customAddCollectionRelation([
                    'relation' => 'student',
                    'field' => 'id',
                    'value' => $student->id,
                ]);
            }
        }
    }
}
