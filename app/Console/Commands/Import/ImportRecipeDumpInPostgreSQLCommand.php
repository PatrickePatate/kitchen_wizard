<?php

namespace App\Console\Commands\Import;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

class ImportRecipeDumpInPostgreSQLCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recipes:import-in-pgsql {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import a recipe dump in PostgreSQL';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if(!File::exists(database_path($this->argument('file'))))
        {
            $this->fail("Unable to find {$this->argument('file')} in ".database_path($this->argument('file')));
        }

        $this->getOutput()->info("Starting import of recipes from {$this->argument('file')} in PostgreSQL...");

        $process = Process::forever()
            ->env(['PGPASSWORD' => config('database.connections.pgsql.password')])
            ->run(sprintf('%s %s %s %s %s %s %s %s %s',
                'psql', '-h', config('database.connections.pgsql.host'), '-p 5433', '-U', config('database.connections.pgsql.username'), config('database.connections.mariadb.database'), '<', realpath(database_path($this->argument('file')))
            ));

        if($process->successful()) {
            $this->getOutput()->success("Recipes have been successfully imported in PostgreSQL");
        } else {
            $this->getOutput()->error("An error occurred while importing recipes in PostgreSQL");
            $this->getOutput()->newLine();
            $this->getOutput()->write($process->errorOutput());
        }
    }
}
