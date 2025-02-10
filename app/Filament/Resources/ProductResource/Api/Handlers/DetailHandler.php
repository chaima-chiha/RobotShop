<?php

namespace App\Filament\Resources\ProductResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\ProductResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\ProductResource\Api\Transformers\ProductTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = ProductResource::class;


    /**
     * Show Product
     *
     * @param Request $request
     * @return ProductTransformer
     */
    public function handler(Request $request)
    {
        $id = $request->route('id');
        
        $query = static::getEloquentQuery();

        $query = QueryBuilder::for(
            $query->where(static::getKeyName(), $id)
        )
            ->first();

        if (!$query) return static::sendNotFoundResponse();

        return new ProductTransformer($query);
    }
}
