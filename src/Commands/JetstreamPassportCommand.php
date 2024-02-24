<?php

namespace HeaderX\JetstreamPassport\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class JetstreamPassportCommand extends Command
{
    public $signature = 'jetstream-passport:install';

    public $description = 'Install jetstream-passport';

    public function handle()
    {
        $this->call('vendor:publish', ['--tag' => 'passport-views']);
        $this->call('passport:keys');
        $this->call('passport:install', ['--uuids']);
        $this->call('passport:client', ['--personal' => true, '--name' => 'Laravel Personal Access Client']);

        $this->replaceInFile('Laravel\\Sanctum\\HasApiTokens' , 'Laravel\\Passport\\HasApiTokens', app_path('Models/User.php'));

        $this->copyFiles();
    }

    /**
     * Replace a given string within a given file.
     *
     * @param  string  $search
     * @param  string  $replace
     * @param  string  $path
     * @return void
     */
    protected function replaceInFile($search, $replace, $path)
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }

    protected function copyFiles()
    {
        $this->info('Copying files...');

        $sourceDir = realpath(__DIR__.'/../../stubs/');
        $destinationDir = base_path();

        $files = File::allFiles($sourceDir);

        foreach ($files as $file) {
            $destinationFilePath = $destinationDir.'/'.$file->getRelativePathname();
            File::ensureDirectoryExists(dirname($destinationFilePath));
            File::copy($sourceFile = $file->getPathname(), $destinationFilePath);
            // check verbosity
            if ($this->output->isVerbose()) {
                $this->line('<info>Copied</info> '.$sourceFile.' <info>to</info> '.$destinationFilePath);
            }
        }
    }
}
