<?php
class ProductVariants {
    private int $id;
    private int $stock;
    /** @var array<string,string> */
    private array $options;
    private float $price;

    private int $productId;
}