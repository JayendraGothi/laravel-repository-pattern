<?php

namespace Reva\RepositoryPattern\Providers;

use Illuminate\Support\ServiceProvider;
use Reva\RepositoryPattern\Commands\RepositoryMakeCommand;

class RepositoryPatternServiceProvider extends ServiceProvider {

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        if ($this->app->runningInConsole()) {
            $this->commands([
                RepositoryMakeCommand::class
            ]);
        }
    }
}
