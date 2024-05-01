<?php

namespace App\Filament\Admin\Pages;

use App\Models\Gateway;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;

class GatewayPage extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.gateway-page';

    public ?array $data = [];
    public Gateway $setting;

    /**
     * @dev @victormsalatiel
     * @return bool
     */
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    /**
     * @return void
     */
    public function mount(): void
    {
        $gateway = Gateway::first();
        if(!empty($gateway)) {
            $this->setting = $gateway;
            $this->form->fill($this->setting->toArray());
        }else{
            $this->form->fill();
        }
    }

    /**
     * @param Form $form
     * @return Form
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('DigitoPay')
                    ->description('Ajustes de credenciais para a DigitoPay')
                    ->schema([
                        TextInput::make('digitopay_uri')
                            ->label('Client URI')
                            ->placeholder('Digite a url da api')
                            ->default('https://si5n56mrnjzvt5gr2f536ildr40sqzke.lambda-url.sa-east-1.on.aws/')
                            ->maxLength(191),
                        TextInput::make('digitopay_cliente_id')
                            ->label('Client ID')
                            ->placeholder('Digite o client ID')
                            ->maxLength(191),
                        TextInput::make('digitopay_cliente_secret')
                            ->label('Client Secret')
                            ->placeholder('Digite o client secret')
                            ->maxLength(191),
                    ])->columns(3),
                Section::make('Mercado Pago')
                    ->description('Chaves do Mercado Pago API')
                    ->schema([
                        TextInput::make('mp_client_id')
                            ->label('Mercado Pago Client ID')
                            ->placeholder('Digite Mercado Pago Client ID')
                            ->maxLength(191),
                        TextInput::make('mp_client_secret')
                            ->label('Mercado Pago Cliene Secret')
                            ->placeholder('Digite Mercado Pago Cliene Secret')
                            ->maxLength(191),
                        TextInput::make('mp_public_key')
                            ->label('Mercado Pago Public Key')
                            ->placeholder('Digite Mercado Pago Public Key')
                            ->maxLength(191),
                        TextInput::make('mp_access_token')
                            ->label('Mercado Pago Access Token')
                            ->placeholder('Digite Mercado Pago Access Token')
                            ->maxLength(191),
                    ])->columns(2),
                Section::make('SharkPay')
                    ->description('Chaves da SharkPay')
                    ->schema([
                        TextInput::make('public_key')
                            ->label('Chave Publica')
                            ->placeholder('Digite a chave publica')
                            ->maxLength(191),
                        TextInput::make('private_key')
                            ->label('Chave Privada')
                            ->placeholder('Digite a chave privada')
                            ->maxLength(191),
                    ])->columns(2),
                Section::make('Stripe')
                    ->description('Ajustes de credenciais para a Stripe')
                    ->schema([
                        TextInput::make('stripe_public_key')
                            ->label('Chave Publica')
                            ->placeholder('Digite a chave publica')
                            ->maxLength(191)
                            ->columnSpanFull(),
                        TextInput::make('stripe_secret_key')
                            ->label('Chave Privada')
                            ->placeholder('Digite a chave privada')
                            ->maxLength(191)
                            ->columnSpanFull(),
                        TextInput::make('stripe_webhook_key')
                            ->label('Chave Webhook')
                            ->placeholder('Digite a chave do webhook')
                            ->maxLength(191)
                            ->columnSpanFull(),
                    ]),

                Section::make('Suitpay')
                    ->description('Ajustes de credenciais para a Suitpay')
                    ->schema([
                        TextInput::make('suitpay_uri')
                            ->label('Client URI')
                            ->placeholder('Digite a url da api')
                            ->maxLength(191)
                            ->columnSpanFull(),
                        TextInput::make('suitpay_cliente_id')
                            ->label('Client ID')
                            ->placeholder('Digite o client ID')
                            ->maxLength(191)
                            ->columnSpanFull(),
                        TextInput::make('suitpay_cliente_secret')
                            ->label('Client Secret')
                            ->placeholder('Digite o client secret')
                            ->maxLength(191)
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }


    /**
     * @return void
     */
    public function submit(): void
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

            $setting = Gateway::first();
            if(!empty($setting)) {
                if($setting->update($this->data)) {
                    if(!empty($this->data['stripe_public_key'])) {
                        $envs = DotenvEditor::load(base_path('.env'));

                        $envs->setKeys([
                            'STRIPE_KEY' => $this->data['stripe_public_key'],
                            'STRIPE_SECRET' => $this->data['stripe_secret_key'],
                            'STRIPE_WEBHOOK_SECRET' => $this->data['stripe_webhook_key'],
                        ]);

                        $envs->save();
                    }

                    Notification::make()
                        ->title('Chaves Alteradas')
                        ->body('Suas chaves foram alteradas com sucesso!')
                        ->success()
                        ->send();
                }
            }else{
                if(Gateway::create($this->data)) {
                    Notification::make()
                        ->title('Chaves Criadas')
                        ->body('Suas chaves foram criadas com sucesso!')
                        ->success()
                        ->send();
                }
            }


        } catch (Halt $exception) {
            Notification::make()
                ->title('Erro ao alterar dados!')
                ->body('Erro ao alterar dados!')
                ->danger()
                ->send();
        }
    }
}
