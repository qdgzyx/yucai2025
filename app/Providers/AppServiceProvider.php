<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
      if (app()->isLocal()) {
            $this->app->register(\VIACreative\SudoSu\ServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
	{
		\App\Models\User::observe(\App\Observers\UserObserver::class);
		\App\Models\QuantifyRecord::observe(\App\Observers\QuantifyRecordObserver::class);
		\App\Models\QuantifyItem::observe(\App\Observers\QuantifyItemObserver::class);
		\App\Models\QuantifyType::observe(\App\Observers\QuantifyTypeObserver::class);
		\App\Models\Semester::observe(\App\Observers\SemesterObserver::class);
		\App\Models\Academic::observe(\App\Observers\AcademicObserver::class);
		\App\Models\Assignment::observe(\App\Observers\AssignmentObserver::class);
		\App\Models\Subject::observe(\App\Observers\SubjectObserver::class);
		\App\Models\Report::observe(\App\Observers\ReportObserver::class);
		\App\Models\Banji::observe(\App\Observers\BanjiObserver::class);
		\App\Models\Grade::observe(\App\Observers\GradeObserver::class);
		\App\Models\Reply::observe(\App\Observers\ReplyObserver::class);
		\App\Models\Topic::observe(\App\Observers\TopicObserver::class);
        \App\Models\Link::observe(\App\Observers\LinkObserver::class);
        \Illuminate\Pagination\Paginator::useBootstrap();
        //
    }
}
