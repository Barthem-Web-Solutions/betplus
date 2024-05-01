<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Resources\DepositResource\Pages;
use App\Filament\Resources\DepositResource\RelationManagers;
use App\Models\Deposit;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DepositResource extends Resource
{
    protected static ?string $model = Deposit::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';

    protected static ?string $navigationLabel = 'Depositos';

    protected static ?string $modelLabel = 'Depositos';

    protected static ?string $navigationGroup = 'Administração';

    protected static ?string $slug = 'todos-depositos';

    protected static ?int $navigationSort = 2;

    /**
     * @dev @victormsalatiel
     * @return bool
     */
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    /**
     * @return string|null
     */
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 0)->count();
    }

    /**
     * @return string|array|null
     */
    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::where('status', 0)->count() > 5 ? 'success' : 'warning';
    }

    /**
     * @param Form $form
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Cadastro de Deposito')
                ->schema([
                    Forms\Components\Select::make('user_id')
                        ->label('Usuários')
                        ->placeholder('Selecione um usuário')
                        ->options(
                            fn($get) => User::query()
                                ->pluck('name', 'id')
                        )
                        ->searchable()
                        ->preload()
                        ->live()
                        ->required(),
                    Forms\Components\TextInput::make('amount')
                        ->label('Valor')
                        ->required()
                        ->default(0.00),
                    Forms\Components\TextInput::make('type')
                        ->label('Tipo')
                        ->required()
                        ->maxLength(191),
                    Forms\Components\FileUpload::make('proof')
                        ->label('Comprovante')
                        ->placeholder('Carregue a imagem do comprovante')
                        ->image()
                        ->columnSpanFull()
                        ->required(),
                    Forms\Components\Toggle::make('status')
                        ->required(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('payment_id')
                    ->label('ID Pag.')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Valor')
                    ->formatStateUsing(fn (Deposit $record): string => $record->symbol . ' ' . $record->amount)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->searchable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('proof')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->label('Criado a partir de'),
                        DatePicker::make('created_until')->label('Criado até'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Criado a partir de ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }

                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Criado até ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Admin\Resources\DepositResource\Pages\ListDeposits::route('/'),
            'create' => \App\Filament\Admin\Resources\DepositResource\Pages\CreateDeposit::route('/create'),
            'edit' => \App\Filament\Admin\Resources\DepositResource\Pages\EditDeposit::route('/{record}/edit'),
        ];
    }
}
