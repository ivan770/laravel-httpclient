<?php


namespace Ivan770\HttpClient\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class HttpRequestMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:http';

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['method', 'm', InputOption::VALUE_OPTIONAL, 'Request method'],
            ['url', 'u', InputOption::VALUE_OPTIONAL, 'Request URL'],
        ];
    }

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new HTTP request class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'HTTP request';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/Request.stub';
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);

        $stub = str_replace(
            'DummyMethod',
            "\"{$this->option('method')}\"" ?? "\"GET\"",
            $stub
        );

        return str_replace(
            'DummyURL',
            "\"{$this->option('url')}\"" ?? "\"\"",
            $stub
        );
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\HttpRequests';
    }
}