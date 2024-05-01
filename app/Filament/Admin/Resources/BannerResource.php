<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Filament\Resources\BannerResource\RelationManagers;
use App\Models\Banner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    /**
     * @dev @victormsalatiel
     * @return bool
     */
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    /**
     * @param Form $form
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->description('')
                    ->schema([
                        Forms\Components\Group::make()->schema([
                            Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('link')
                                    ->label('Link')
                                    ->placeholder('Digite o link do banner')
                                    ->maxLength(191),
                                Forms\Components\Select::make('type')
                                    ->label('Selecione o tipo')
                                    ->options([
                                        'carousel' => 'Banner na Carousel',
                                        'home' => 'Banner na Home',
                                    ])
                                    ->required(),
                            ])->columns(2)->columnSpanFull(),

                            Forms\Components\Textarea::make('description')
                                ->placeholder('Digite uma descrição')
                                ->maxLength(65535)
                                ->columnSpanFull(),
                        ])->columns(2),
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->required(),
                    ])
            ]);
    }

    /**
     * @param Table $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Imagem'),
                Tables\Columns\TextColumn::make('link')
                    ->label('Link')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            'index' => \App\Filament\Admin\Resources\BannerResource\Pages\ListBanners::route('/'),
            'create' => \App\Filament\Admin\Resources\BannerResource\Pages\CreateBanner::route('/create'),
            'edit' => \App\Filament\Admin\Resources\BannerResource\Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}
