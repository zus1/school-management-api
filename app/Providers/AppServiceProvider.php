<?php

namespace App\Providers;

use App\Http\Requests\Rules\UserRules;
use App\Http\Requests\UserRequest;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
