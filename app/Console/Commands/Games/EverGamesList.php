<?php

namespace App\Console\Commands\Games;

use App\Traits\Commands\Games\FiversGamesCommandTrait;
use App\Traits\Providers\EvergameTrait;
use App\Traits\Providers\VeniXTrait;
use Illuminate\Console\Command;

class EverGamesList extends Command
{
    use EvergameTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ever:games';

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
        self::getGamesEvergame();
    }
}
