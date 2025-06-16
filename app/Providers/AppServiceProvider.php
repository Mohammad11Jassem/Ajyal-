<?php

namespace App\Providers;

use App\Interfaces\StudentInterface;
use App\Interfaces\SubjectRepositoryInterface;
use App\Repositories\StudentRepository;
use App\Repositories\SubjectRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
            $this->app->bind(StudentInterface::class, StudentRepository::class);
            $this->app->bind(SubjectRepositoryInterface::class, SubjectRepository::class);


    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
