<?php

namespace HeaderX\JetstreamPassport\Commands;

use Illuminate\Console\Command;

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
    }
}
