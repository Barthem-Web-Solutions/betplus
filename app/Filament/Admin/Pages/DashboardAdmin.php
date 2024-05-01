<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Widgets\StatsOverview;
use App\Livewire\AdminWidgets;
use App\Livewire\WalletOverview;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class DashboardAdmin extends \Filament\Pages\Dashboard
{
    use HasFiltersForm, HasFiltersAction;

    /**
     * @dev @victormsalatiel
     * @return bool
     */
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('admin');
    }


    /**
     * @return string|\Illuminate\Contracts\Support\Htmlable|null
     */
    public function getSubheading(): string| null|\Illuminate\Contracts\Support\Htmlable
    {
        $roleName = 'Admin';
        if(auth()->user()->hasRole('afiliado')) {
            $roleName = 'Afiliado';
        }

        return "Olá, $roleName! Seja muito bem-vindo ao seu painel.";
    }

    /**
     * @param Form $form
     * @return Form
     */
    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
//                Section::make()
//                    ->schema([
//                        DatePicker::make('startDate'),
//                        DatePicker::make('endDate'),
//                    ])
//                    ->columns(2),
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
            AdminWidgets::class,
            WalletOverview::class,
            StatsOverview::class,
            //GGROverview::class,
            //GgrTableWidget::class,
        ];
    }
}
