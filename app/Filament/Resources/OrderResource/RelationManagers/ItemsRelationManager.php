<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Product;
use App\Models\Video;
use Filament\Forms\Components\Select;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';



    public function form(Form $form): Form
    {
        // Fetch the lists of products and videos
    $products = Product::all()->mapWithKeys(function ($product) {
        return [$product->id => 'Product: ' . $product->name];
    })->toArray();

    $videos = Video::all()->mapWithKeys(function ($video) {
        return [$video->id => 'Video: ' . $video->title];
    })->toArray();


    return $form
        ->schema([
            Forms\Components\Select::make('product_id')
                ->options($products),
                Forms\Components\Select::make('video_id')
                ->options($videos),
            Forms\Components\TextInput::make('quantity')
                ->numeric()
                ->required(),

            Forms\Components\TextInput::make('price')
                ->numeric()
                ->required(),

        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([


                Tables\Columns\TextColumn::make('video.title')->label('video'),

                Tables\Columns\TextColumn::make('product.name')->label('Produit'),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantité'),
                Tables\Columns\TextColumn::make('price')
                    ->label('Prix')
                    ->money('TND'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                 // 💡 Bouton "Ajouter code"
             Tables\Actions\Action::make('ajouter_code')
            ->label('Ajouter code')
            ->icon('heroicon-o-key')
            ->url(fn ($record): string => route('filament.admin.resources.video-activation-codes.create', [
                'user_id' => $this->ownerRecord->user_id,
                'video_id' => $record->video_id,
            ]))
            ->visible(fn ($record) => $record->video_id !== null), // afficher seulement si c'est une vidéo
    ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
