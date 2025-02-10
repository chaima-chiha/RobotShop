<?php
namespace App\Filament\Resources\ProductResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\ProductResource;
use App\Filament\Resources\ProductResource\Api\Requests\CreateProductRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = ProductResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create Product
     *
     * @param CreateProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateProductRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}