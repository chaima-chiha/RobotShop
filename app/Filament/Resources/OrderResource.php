<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;



class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Client')
                    ->relationship('user', 'name')
                    ->required(),

                Forms\Components\TextInput::make('nom')
                    ->label('Nom complet')
                    ->required(),

                Forms\Components\TextInput::make('adresse')
                    ->label('Adresse de livraison')
                    ->required(),

                Forms\Components\TextInput::make('telephone')
                    ->label('Téléphone')
                    ->tel()
                    ->required(),

                Forms\Components\Select::make('livraison')
                    ->label('Méthode de livraison')
                    ->options([
                        'point_retrait' => 'Point de retrait',
                        'domicile' => 'Livraison à domicile',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('total')
                    ->label('Total')
                    ->numeric()
                    ->required(),

                Forms\Components\Select::make('status')
                    ->label('Statut de la commande')
                    ->options([
                        'en_attente' => 'En_attente',
                        'confirmée' => 'Confirmée',
                        'expédiée' => 'Expédiée',
                        'livrée' => 'Livrée',
                        'annulée' => 'Annulée',
                    ])
                    ->required(),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('Client'),
                Tables\Columns\TextColumn::make('nom')->label('Nom'),
                Tables\Columns\TextColumn::make('adresse')->label('Adresse'),
                Tables\Columns\TextColumn::make('telephone')->label('Téléphone'),
                Tables\Columns\TextColumn::make('livraison')->label('Livraison'),
                Tables\Columns\TextColumn::make('total')->label('Montant total')->money('TND'),
                Tables\Columns\TextColumn::make('status')->label('Statut')
                    ->colors([
                        'warning' => 'en_attente',
                        'success' => 'confirmée',
                        'info'    => 'en cours',
                        'primary' => 'livrée',
                        'danger'  => 'annulée',
                    ]),
                Tables\Columns\TextColumn::make('created_at')->label('Date')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document')
                    ->color('secondary')
                    ->url(fn ($record) => route('invoice.download', $record->id))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('print')
                    ->label('Imprimer')
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->url(fn ($record) => route('invoice.print', ['order_id' => $record->id]))
                    ->openUrlInNewTab(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ItemsRelationManager::class,
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),

        ];
    }
}
