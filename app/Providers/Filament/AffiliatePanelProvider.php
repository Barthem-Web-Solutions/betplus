<?php

namespace App\Providers\Filament;

use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use App\Filament\Affiliate\Pages\DashboardAffiliate;
use App\Filament\Affiliate\Resources\AffiliateWithdrawResource;
use App\Filament\Affiliate\Resources\SubAffiliateResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AffiliatePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('affiliate')
            ->path('affiliate')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->font('Roboto Condensed')
            ->brandLogo(fn () => view('filament.components.logo'))
            ->discoverResources(in: app_path('Filament/Affiliate/Resources'), for: 'App\\Filament\\Affiliate\\Resources')
            ->discoverPages(in: app_path('Filament/Affiliate/Pages'), for: 'App\\Filament\\Affiliate\\Pages')
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->sidebarCollapsibleOnDesktop()
            ->collapsibleNavigationGroups(true)
            ->pages([
                DashboardAffiliate::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Affiliate/Widgets'), for: 'App\\Filament\\Affiliate\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->groups([
                    NavigationGroup::make()
                        ->items([
                            NavigationItem::make('dashboard')
                                ->icon('heroicon-o-home')
                                ->label(fn (): string => __('filament-panels::pages/dashboard.title'))
                                ->url(fn (): string => DashboardAffiliate::getUrl())
                                ->isActiveWhen(fn () => request()->routeIs('filament.affiliate.pages.dashboard-affiliate')),

                            NavigationItem::make('sub_affiliates')
                                ->icon('heroicon-o-user-group')
                                ->label(fn (): string => 'Sub Afiliados')
                                ->url(fn (): string => SubAffiliateResource::getUrl())
                                ->isActiveWhen(fn () => request()->routeIs('filament.admin.resources.sub-affiliates.index '))
                                ->visible(fn(): bool => auth()->user()->hasRole('afiliado')),

                            NavigationItem::make('withdraw_affiliates')
                                ->icon('heroicon-o-banknotes')
                                ->label(fn (): string => auth()->user()->hasRole('afiliado') && auth()->user()->hasRole('admin') ? 'Meus Saques' : 'Saques de Afiliados')
                                ->url(fn (): string => AffiliateWithdrawResource::getUrl())
                                ->isActiveWhen(fn () => request()->routeIs('filament.admin.resources.sub-affiliates.index'))
                                ->visible(fn(): bool => auth()->user()->hasRole('afiliado') || auth()->user()->hasRole('admin')),

                        ])
                    ,
                    auth()->user()->hasRole('afiliado') ?
                        NavigationGroup::make('affiliate_link')
                            ->label('Marketing')
                            ->items([
                                NavigationItem::make('Link de Convite')
                                    ->url(url('/register?code='.auth()->user()->inviter_code), shouldOpenInNewTab: true)
                                    ->icon('heroicon-o-link')
                            ])
                        : NavigationGroup::make(),
                ]);
            })
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
