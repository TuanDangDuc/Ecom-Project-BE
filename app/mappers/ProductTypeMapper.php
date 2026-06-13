<?php

class ProductTypeMapper
{
    public static function CreateProductTypeDtoRequestToProductType(CreateProductTypeDtoRequest $request): ProductType
    {
        $productType = new ProductType();
        $productType->setName($request->name);
        $productType->setDescription($request->description);
        return $productType;
    }
}