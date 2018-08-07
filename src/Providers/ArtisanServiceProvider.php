<?php

namespace Reva\RepositoryPattern\Providers;

use Illuminate\Foundation\Console\AppNameCommand;
use Illuminate\Support\ServiceProvider;

class ArtisanServiceProvider extends ServiceProvider {
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'RepositoryMake' => 'command.reva.repository.name',
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->registerCommands($this->commands);
    }

    /**
     * Register the given commands.
     *
     * @param  array $commands
     * @return void
     */
    protected function registerCommands(array $commands) {
        foreach (array_keys($commands) as $command) {
            call_user_func_array([$this, "register{$command}Command"], []);
        }

        $this->commands(array_values($commands));
    }

    /**
     * Register the command.
     *
     * @return void
     */
    protected function registerRepositoryMakeCommand() {
        $this->app->singleton('command.reva.repository.name', function ($app) {
            return new AppNameCommand($app['composer'], $app['files']);
        });
    }
}
