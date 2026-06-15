<?php

class VariantMapper
{
    public static function CreateVariantDtoRequestToVariant(CreateVariantDtoRequest $request, ?int $productId = null): ProductVariants
    {
        $variant = new ProductVariants();

        $variant->setProductId($productId);
        $variant->setStock($request->stock);
        $variant->setOptions($request->options);
        $variant->setPrice($request->price);

        return $variant;
    }

    public static function UpdateVariantDtoRequestToVariant(UpdateVariantDtoRequest $request, ProductVariants $variant): ProductVariants
    {
        if ($request->productId !== null) {
            $variant->setProductId($request->productId);
        }

        if ($request->stock !== null) {
            $variant->setStock($request->stock);
        }

        if ($request->price !== null) {
            $variant->setPrice($request->price);
        }

        if ($request->options !== null) {
            $variant->setOptions(json_decode($request->options, true));
        }

        return $variant;
    }
}
