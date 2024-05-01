<?php

namespace App\Enums;

enum PlayGamingEnum : string
{
    case GETBALANCE = 'getBalance';
    case WRITEBET = 'writeBet';
    case GAMELIST = 'gamesList';
    case OPENGAME = 'openGame';
    case GAMESESSION = 'gameSessionsLog';
}
