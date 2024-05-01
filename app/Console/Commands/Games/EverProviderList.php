<?php

namespace App\Console\Commands\Games;

use App\Traits\Commands\Games\FiversGamesCommandTrait;
use App\Traits\Providers\EvergameTrait;
use Illuminate\Console\Command;

class EverProviderList extends Command
{
    use EvergameTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ever:providers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        return self::getProviderEvergame();
    }
}
