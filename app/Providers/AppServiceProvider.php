<?php

namespace App\Providers;

use App\Filters\AuthRelationFilters;
use App\Http\Controllers\CustomBaseCollectionController;
use App\Http\Requests\Rules\UserRules;
use App\Http\Requests\UserRequest;
use App\Repository\SchoolDirectoryBaseRepository;
use App\Repository\StudentRepository;
use App\Repository\SubjectRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->resolving(UserRequest::class, function (UserRequest $userRequest, Application $app) {
            $userRequest->setUserRules($app->make(UserRules::class));
        });

        $this->app->resolving(SchoolDirectoryBaseRepository::class, function (
            SchoolDirectoryBaseRepository $repository,
            Application $app)
        {
                $repository->setStudentRepository($app->make(StudentRepository::class));
                $repository->setSubjectRepository($app->make(SubjectRepository::class));
        });

        $this->app->resolving(CustomBaseCollectionController::class, function (CustomBaseCollectionController $controller, Application $app) {
            $controller->setAuthRelationshipFilters($app->make(AuthRelationFilters::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
