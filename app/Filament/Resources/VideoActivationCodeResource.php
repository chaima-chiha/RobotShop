<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoActivationCodeResource\Pages;
use App\Filament\Resources\VideoActivationCodeResource\RelationManagers;
use App\Models\VideoActivationCode;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VideoActivationCodeResource extends Resource
{
    protected static ?string $model = VideoActivationCode::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?string $navigationLabel = 'Codes d\'activation';
    protected static ?string $pluralModelLabel = 'Codes d\'activation';
protected static ?string $navigationGroup = 'Contenu e-learning';
    public static function form(Form $form): Form
    {
        $userId = request()->get('user_id');
        $videoId = request()->get('video_id');

        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->relationship('user', 'name')
                ->searchable()
                ->required()
                ->default($userId),

            Forms\Components\Select::make('video_id')
                ->relationship('video', 'title')
                ->searchable()
                ->required()
                ->default($videoId),

            Forms\Components\TextInput::make('code')
                ->required()
                ->unique()
                ->maxLength(20),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Utilisateur'),
                Tables\Columns\TextColumn::make('video.title')->label('Vidéo'),
                Tables\Columns\TextColumn::make('code')->label('Code d\'activation'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Créé le'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVideoActivationCodes::route('/'),
            'create' => Pages\CreateVideoActivationCode::route('/create'),
            'edit' => Pages\EditVideoActivationCode::route('/{record}/edit'),
        ];
    }
}
