<?php

namespace App\Filament\Affiliate\Widgets;

use App\Models\AffiliateHistory;
use App\Models\User;
use App\Models\Wallet;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AffiliateWidgets extends BaseWidget
{
    protected static ?int $navigationSort = -2;

    /**
     * @return array|Stat[]
     */
    protected function getCards(): array
    {
        $inviterId      = auth()->user()->id;
        $usersIds       = User::where('inviter', $inviterId)->get()->pluck('id');
        $usersTotal     = User::where('inviter', $inviterId)->count();
        $comissaoTotal  = Wallet::whereIn('user_id', $usersIds)->sum('balance');
        $mycomission    = Wallet::where('user_id', $inviterId)->sum('refer_rewards');

        // \Log::info('inviterId: '.$inviterId);
        // \Log::info('usersIds: '.$usersIds);
        // \Log::info('usersTotal: '.$usersTotal);
        // \Log::info('comissaoTotal: '.$comissaoTotal);

        return [
            Stat::make('Saldo Disponível', \Helper::amountFormatDecimal($mycomission))
                ->description('Saldo Disponível para saque')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Saldo dos indicados', \Helper::amountFormatDecimal($comissaoTotal))
                ->description('Saldo geral dos indicados.')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Cadastros', $usersTotal)
                ->description('Usuários cadastrados com meu link')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }

    /**
     * @return bool
     */
    public static function canView(): bool
    {
        return auth()->user()->hasRole('afiliado');
    }
}
