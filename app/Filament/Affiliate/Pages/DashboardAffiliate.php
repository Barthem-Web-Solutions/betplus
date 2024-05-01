<?php

namespace App\Filament\Affiliate\Pages;

use App\Filament\Affiliate\Widgets\AffiliateChart;
use App\Filament\Affiliate\Widgets\AffiliateWidgets;
use App\Filament\Affiliate\Widgets\LatestAdminComissions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Pages\Page;

class DashboardAffiliate extends \Filament\Pages\Dashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.affiliate.pages.dashboard-affiliate';

    use HasFiltersForm, HasFiltersAction;

    /**
     * @return string|\Illuminate\Contracts\Support\Htmlable|null
     */
    public function getSubheading(): string| null|\Illuminate\Contracts\Support\Htmlable
    {
        return "Olá, Afiliado! Seja muito bem-vindo ao seu painel.";
    }

    /**
     * @dev @victormsalatiel
     * @return bool
     */
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('afiliado');

    }


    /**
     * @param Form $form
     * @return Form
     */
    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    /**
     * @return array|\Filament\Actions\Action[]|\Filament\Actions\ActionGroup[]
     */
    protected function getHeaderActions(): array
    {
        return [
            FilterAction::make()
                ->label('Filtro')
                ->form([
                    DatePicker::make('startDate')->label('Data Incial'),
                    DatePicker::make('endDate')->label('Data Final'),
                ]),
        ];
    }


    /**
     * @return string[]
     */
    public function getWidgets(): array
    {
        return [
            AffiliateWidgets::class,
            AffiliateChart::class,
            LatestAdminComissions::class,
        ];
    }

}
