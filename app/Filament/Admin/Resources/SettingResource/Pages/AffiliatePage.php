<?php

namespace App\Filament\Admin\Resources\SettingResource\Pages;

use App\Filament\Admin\Resources\SettingResource;
use App\Models\Setting;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AffiliatePage extends Page  implements HasForms
{
    use HasPageSidebar, InteractsWithForms;

    protected static string $resource = SettingResource::class;

    protected static string $view = 'filament.resources.setting-resource.pages.affiliate-page';

    /**
     * @return string|Htmlable
     */
    public function getTitle(): string | Htmlable
    {
        return __('Pagamentos');
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
                Section::make('Ajuste de Afiliados')
                    ->description('Porcentagem de Sub-Afiliados')
                    ->schema([
                        TextInput::make('perc_sub_lv1')
                            ->label('% Sub afiliado LV1')
                            ->numeric()
                            ->maxLength(191),
                        TextInput::make('perc_sub_lv2')
                            ->label('% Sub afiliado LV2')
                            ->numeric()
                            ->maxLength(191),
                        TextInput::make('perc_sub_lv3')
                            ->label('% Sub afiliado LV3')
                            ->numeric()
                            ->maxLength(191),
                    ])->columns(3)
            ])
            ->statePath('data');
    }
}
