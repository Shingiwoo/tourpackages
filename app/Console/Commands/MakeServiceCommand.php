<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;

class MakeServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class with nested namespace support';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $segments = explode('/', $name);
        $classNameWithoutNamespace = Str::studly(end($segments)) . 'Service';
        $namespace = 'App\\Services';
        $directoryPath = app_path('Services');

        if (count($segments) > 1) {
            // Build namespace and directory path for nested structure
            $namespace .= '\\' . implode('\\', array_map('ucfirst', array_slice($segments, 0, -1)));
            $directoryPath .= '/' . implode('/', array_slice($segments, 0, -1));
        }

        $className = $classNameWithoutNamespace;
        $fullPath = $directoryPath . '/' . $className . '.php';

        if (File::exists($fullPath)) {
            $this->error("Service class [{$namespace}\\{$className}] already exists!");
            return;
        }

        File::makeDirectory($directoryPath, 0755, true, true);

        $stub = $this->getStub();

        $this->files->put(
            $fullPath,
            str_replace(['{{ Namespace }}', '{{ ClassName }}'], [$namespace, $className], $stub)
        );

        $this->info("Service class [{$namespace}\\{$className}] created successfully.");
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return <<<'STUB'
        <?php

        namespace {{ Namespace }};

        class {{ ClassName }}
        {
            // Add your service logic here
        }
        STUB;
    }
}