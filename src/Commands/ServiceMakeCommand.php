<?php

namespace Reva\RepositoryPattern\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputOption;

class ServiceMakeCommand extends GeneratorCommand {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'reva:service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Service';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() {
        $this->createRepository();
        parent::handle();
    }

    /**
     * Create a controller for the model.
     *
     * @return void
     */
    protected function createRepository() {

        // get model class
        $modelClass = $this->option('model');

        // call repository command
        $this->call('reva:repository', [
            'name' => class_basename($modelClass) . "Repository",
            '--model' => $modelClass,
            '--folder' => $this->option('folder')
        ]);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub() {
        $stub = null;

        if ($this->option('model')) {
            $stub = '/stubs/service.model.stub';
        }

        $stub = $stub ?? '/stubs/service.model.stub';

        return __DIR__ . $stub;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace) {
        return $rootNamespace . '\Services\\' . $this->option('folder');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     * @return string
     */
    protected function getRepositoryNamespace($rootNamespace) {
        return $rootNamespace . 'Repositories';
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in base namespace.
     *
     * @param  string $name
     * @return string
     */
    protected function buildClass($name) {

        // get use replacements
        $replace = [];

        if ($this->option('model')) {
            $replace = $this->buildModelReplacements($replace);
            $replace = $this->buildRepositoryReplacements($replace);
        }

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    /**
     * Build the model replacement values.
     *
     * @param  array $replace
     * @return array
     */
    protected function buildRepositoryReplacements(array $replace) {
        $repositoryClass = $this->parseRepository($this->option('model'));

        return array_merge($replace, [
            'Folder' => $this->option('folder'),
            'DummyFullRepositoryClass' => $repositoryClass,
            'DummyRepositoryClass' => class_basename($repositoryClass)
        ]);
    }

    /**
     * Build the model replacement values.
     *
     * @param  array $replace
     * @return array
     */
    protected function buildModelReplacements(array $replace) {
        $modelClass = $this->parseModel($this->option('model'));

        if (!class_exists($modelClass)) {
            $this->error("A {$modelClass} model does not exist. Please create one.", true);
        }

        return array_merge($replace, [
            'DummyFullModelClass' => $modelClass,
            'DummyModelClass' => class_basename($modelClass)
        ]);
    }

    /**
     * Get the fully-qualified model class name.
     *
     * @param  string $model
     * @return string
     */
    protected function parseModel($model) {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        $model = "Models/{$this->option('folder')}/". $model;
        $model = trim(str_replace('/', '\\', $model), '\\');

        if (!Str::startsWith($model, $rootNamespace = $this->laravel->getNamespace())) {
            $model = $rootNamespace . $model;
        }

        return $model;
    }

    /**
     * Get the fully-qualified repository class name.
     *
     * @param  string $repository
     * @return string
     */
    protected function parseRepository($repository) {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $repository)) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        $repository = "{$this->option('folder')}/". $repository;
        $repository = trim(str_replace('/', '\\', $repository), '\\');

        if (!Str::startsWith($repository, $rootNamespace = $this->getRepositoryNamespace($this->laravel->getNamespace()))) {
            $repository = $rootNamespace . '\\' . $repository . 'Repository';
        }

        return $repository;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions() {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Generate a repository class for the given model.'],
            ['folder', 'f', InputOption::VALUE_OPTIONAL, 'Generate a folder for the given name.']
        ];
    }
}
