<?php

namespace App\Console\Commands\Games;

use App\Traits\Commands\Games\FiversGamesCommandTrait;
use App\Traits\Providers\VeniXTrait;
use Illuminate\Console\Command;

class VenixProviderList extends Command
{
    use VeniXTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'venix:providers';

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
        return self::getVenixProvider();
    }
}
