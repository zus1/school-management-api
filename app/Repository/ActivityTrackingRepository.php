<?php

namespace App\Repository;

use App\Models\ActivityTracking;
use Illuminate\Support\Facades\Auth;
use Zus1\LaravelBaseRepository\Repository\LaravelBaseRepository;

class ActivityTrackingRepository extends LaravelBaseRepository
{
    protected const MODEL = ActivityTracking::class;

    public function create(string $route): ActivityTracking
    {
        $activityTracking = new ActivityTracking();
        $activityTracking->route = $route;

        $this->associateStudent($activityTracking);

        $activityTracking->save();

        return $activityTracking;
    }

    private function associateStudent(ActivityTracking $activityTracking): void
    {
        $student = Auth::user();

        $activityTracking->student()->associate($student);
    }
}
