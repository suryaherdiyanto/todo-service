<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Str;

class KeyGenerateCommand extends Command
{
    protected $signature = 'key:generate';

    protected $description = 'Get random string';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info(str_random(32));
    }
}