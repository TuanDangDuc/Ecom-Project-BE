<?php

class ProductImageMapper
{
    public static function CreateProductImageDtoRequestToProductImage(CreateProductImageDtoRequest $request, int $productVariantId): ProductImages
    {
        $image = new ProductImages();
        $image->setUrl($request->url);
        $image->setImageOrder($request->imageOrder ?? 0);
        $image->setProductVariantId($productVariantId);

        return $image;
    }
}
