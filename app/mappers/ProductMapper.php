<?php

class ProductMapper
{
    public static function CreateProductDtoRequestToProduct(CreateProductDtoRequest $request): Product
    {
        $product = new Product();

        $product->setName($request->name);
        $product->setDescription($request->description);
        $product->setThumbnailUrl($request->thumbnailUrl);
        $product->setImageUrl($request->imagesUrl);
        $product->setBasePrice($request->basePrice);
        $product->setRatingAverage($request->ratingAverage);
        $product->setCategoryId($request->categoryId);
        $product->setShopId($request->shopId);
        $product->setProductTypeId($request->typeId);

        return $product;
    }

    public static function UpdateProductDtoRequestToProduct(UpdateProductDtoRequest $request): Product
    {
        $product = new Product();

        $product->setName($request->name);
        $product->setDescription($request->description);
        $product->setThumbnailUrl($request->thumbnailUrl);
        $product->setImageUrl($request->imagesUrl);
        $product->setBasePrice($request->basePrice);
        $product->setRatingAverage($request->ratingAverage);
        $product->setCategoryId($request->categoryId);
        $product->setShopId($request->shopId);
        $product->setProductTypeId($request->typeId);

        return $product;
    }
}