<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoResource\Pages;
use App\Models\Video;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\VideoColumn;
use Filament\Tables\Columns\ImageColumn;

class VideoResource extends Resource
{
    protected static ?string $model = Video::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->nullable()
                    ->maxLength(65535)
                    ->columnSpanFull(),

                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),

                FileUpload::make('video_path')
                    ->label('Vidéo')
                    ->directory('videos')
                    ->acceptedFileTypes(['video/mp4', 'video/quicktime'])
                    ->maxSize(50240) // 50MB
                    ->downloadable()
                    ->columnSpanFull(),

                FileUpload::make('thumbnail')
                    ->label('Miniature')
                    ->image()
                    ->directory('video-thumbnails')
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('duration')
                    ->label('Durée (en secondes)')
                    ->numeric()
                    ->nullable(),
                    
                Forms\Components\Select::make('products')
                    ->multiple()
                    ->relationship('products', 'name')
                    ->preload()
                    ->searchable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),

                ImageColumn::make('thumbnail')
                    ->label('Miniature')
                    ->disk('public')
                    ->width(100)
                    ->height(80),

                Tables\Columns\TextColumn::make('category.name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('duration')
                    ->formatStateUsing(fn (string $state): string => gmdate('H:i:s', $state))
                    ->label('Durée'),

                Tables\Columns\TextColumn::make('products.name')
                    ->label('Produits associés')
                    ->listWithLineBreaks()
                    ->limitList(3)
                    ->expandableLimitedList(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),


            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVideos::route('/'),
            'create' => Pages\CreateVideo::route('/create'),
            'edit' => Pages\EditVideo::route('/{record}/edit'),
        ];
    }
}
