<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\Gemini\Client::class, function ($app) {
            $apiKey = config('gemini.api_key');
            
            return \Gemini::factory()
                ->withApiKey($apiKey)
                ->withHttpClient(new \GuzzleHttp\Client([
                    'verify' => false,
                    'timeout' => config('gemini.request_timeout', 30),
                ]))
                ->make();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        // OPTIMASI: Hanya jalankan query notifikasi untuk layout utama, bukan setiap komponen kecil (*)
        View::composer(['layouts.admin', 'layouts.app', 'layouts.guru', 'layouts.walikelas'], function ($view) {
            if (Auth::check()) {
                $unreadNotificationsCount = Notification::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->count();
                $latestNotifications = Notification::where('user_id', Auth::id())
                    ->latest()
                    ->take(5)
                    ->get();
                $view->with('unreadNotificationsCount', $unreadNotificationsCount);
                $view->with('latestNotifications', $latestNotifications);
            }
        });
    }
}
