<?php

namespace App\Filament\Admin\Resources\SettingResource\Pages;

use App\Filament\Admin\Resources\SettingResource;
use App\Models\Setting;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class GatewayPage extends Page implements HasForms
{
    use HasPageSidebar, InteractsWithForms;

    protected static string $resource = SettingResource::class;

    protected static string $view = 'filament.resources.setting-resource.pages.gateway-page';

    /**
     * @return string|Htmlable
     */
    public function getTitle(): string | Htmlable
    {
        return 'Gateways';
    }

    public Setting $record;
    public ?array $data = [];

    /**
     * @dev @victormsalatiel
     * @param Model $record
     * @return bool
     */
    public static function canView(Model $record): bool
    {
        return auth()->user()->hasRole('admin');
    }

    /**
     * @dev victormsalatiel - Meu instagram
     * @return void
     */
    public function mount(): void
    {
        $setting = Setting::first();
        $this->record = $setting;
        $this->form->fill($setting->toArray());
    }

    /**
     * @dev victormsalatiel - Meu instagram
     * @return void
     */
    public function save()
    {
        try {
            if(env('APP_DEMO')) {
                Notification::make()
                    ->title('Atenção')
                    ->body('Você não pode realizar está alteração na versão demo')
                    ->danger()
                    ->send();
                return;
            }

            $setting = Setting::find($this->record->id);

            if($setting->update($this->data)) {
                Cache::put('setting', $setting);

                Notification::make()
                    ->title('Dados alterados')
                    ->body('Dados alterados com sucesso!')
                    ->success()
                    ->send();

                redirect(route('filament.admin.resources.settings.payment', ['record' => $this->record->id]));

            }
        } catch (Halt $exception) {
            return;
        }
    }

    /**
     * @dev victormsalatiel - Meu instagram
     * @param Form $form
     * @return Form
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Gateways')
                    ->description('Ativa ou desative seus gateway de Pagamento')
                    ->schema([
                        Toggle::make('digitopay_is_enable')
                            ->label('DigitoPay'),
                        Toggle::make('sharkpay_is_enable')
                            ->label('SharkPay Ativo'),
                        Toggle::make('suitpay_is_enable')
                            ->label('SuitPay Ativo'),
                        Toggle::make('stripe_is_enable')
                            ->label('Stripe Ativo'),
                        Toggle::make('mercadopago_is_enable')
                            ->label('Mercado Pago Ativo'),
//                        Toggle::make('bspay_is_enable')
//                            ->label('BSPay Ativo'),
                    ])->columns(2)
            ])
            ->statePath('data') ;
    }
}
