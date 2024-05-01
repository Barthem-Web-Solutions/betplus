<?php

namespace App\Console\Commands\Games;

use App\Traits\Commands\Games\FiversGamesCommandTrait;
use App\Traits\Providers\PlayGamingTrait;
use App\Traits\Providers\PlayIGamingTrait;
use App\Traits\Providers\VeniXTrait;
use Illuminate\Console\Command;

class PlayIGamingGamesList extends Command
{
    use PlayIGamingTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'playigaming:games';

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
        self::getPIGGames();
    }
}
