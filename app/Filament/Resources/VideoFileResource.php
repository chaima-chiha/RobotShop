<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\VideoFilee;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\VideoFileResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\VideoFileResource\RelationManagers;

class VideoFileResource extends Resource
{
    protected static ?string $model = VideoFilee::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static ?string $navigationLabel = 'Video File';
protected static ?string $pluralModelLabel = 'Video Files';
protected static ?string $modelLabel = 'Video File';
protected static ?string $navigationGroup = 'Contenu e-learning';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('video_id')
                    ->relationship('video', 'title')
                    ->required(),

                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                FileUpload::make('file_path')
                    ->label('Fichier')
                    ->directory('video-files')
                   ->downloadable()
                    ->required()
                    ->acceptedFileTypes([
                        'application/zip',
                        'application/x-rar-compressed',
                        'application/x-zip-compressed',
                        'application/pdf',
                        'text/plain',         // pour .ino
                        'text/x-c',           // au cas où
                        'application/octet-stream', // générique pour fichiers inconnus
                        'image/*',
                        '.py', '.php', '.js', '.ino'
                    ])
                    ,
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('video.title')->label("Vidéo"),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('file_path')->label("Lien")
                    ->url(fn ($record) => Storage::url($record->file_path), true),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListVideoFiles::route('/'),
            'create' => Pages\CreateVideoFile::route('/create'),
            'edit' => Pages\EditVideoFile::route('/{record}/edit'),
        ];
    }
}
