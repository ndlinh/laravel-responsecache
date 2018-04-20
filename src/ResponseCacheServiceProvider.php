<?php

namespace Spatie\ResponseCache;

use Illuminate\Cache\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Spatie\ResponseCache\Commands\Clear;
use Spatie\ResponseCache\Commands\Flush;
use Spatie\ResponseCache\CacheProfiles\CacheProfile;

class ResponseCacheServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $configPath = $this->app->basePath() . '/config/';
        $this->publishes([
            __DIR__.'/../config/responsecache.php' => $configPath . 'responsecache.php',
        ], 'config');

        $this->app->bind(CacheProfile::class, function (Application $app) {
            return $app->make(config('responsecache.cache_profile'));
        });

        $this->app->when(ResponseCacheRepository::class)
            ->needs(Repository::class)
            ->give(function () {
                $repository = $this->app['cache']->store(config('responsecache.cache_store'));
                if (! empty(config('responsecache.cache_tag'))) {
                    return $repository->tags(config('responsecache.cache_tag'));
                }

                return $repository;
            });

        $this->app->singleton('responsecache', ResponseCache::class);

        $this->app['command.responsecache:flush'] = $this->app->make(Flush::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                Flush::class,
                Clear::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/responsecache.php', 'responsecache');
    }
}
