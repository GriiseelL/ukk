<?php

namespace App\Providers;

use App\Models\Notification;
use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        app('router')->aliasMiddleware('checksuspend', \App\Http\Middleware\CheckSuspended::class);
        User::observe(UserObserver::class);
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $unreadNotificationsCount = Notification::where('receiver_id', auth()->id())
                    ->where('is_read', 0)
                    ->count();

                $view->with('unreadNotificationsCount', $unreadNotificationsCount);
            }
        });
    }
}
