<?php

namespace App\Http\Controllers;

use App\Models\Guardian;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Zus1\LaravelBaseRepository\Controllers\BaseCollectionController;

class BaseSchoolDirectoryCollectionController extends BaseCollectionController
{
    protected function setCollectionRelations(): void
    {
        $auth = Auth::user();

        if($auth instanceof Student) {
            $this->addCollectionRelation([
                'relation' => 'student',
                'field' => 'id',
                'value' => $auth->id,
            ]);
        }

        if($auth instanceof Guardian) {
            $students = $auth->students()->get();
            /** @var Student $student */
            foreach ($students as $student) {
                $this->addCollectionRelation([
                    'relation' => 'student',
                    'field' => 'id',
                    'value' => $student->id,
                ]);
            }
        }

        if($auth instanceof Teacher) {
            $this->addCollectionRelation([
                'relation' => 'teacher',
                'field' => 'id',
                'value' => $auth->id,
            ]);

            $students = $auth->students()->get();
            /** @var Student $student */
            foreach ($students as $student) {
                $this->addCollectionRelation([
                    'relation' => 'student',
                    'field' => 'id',
                    'value' => $student->id,
                ]);
            }
        }
    }
}
