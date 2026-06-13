<?php

class Reviews
{
    private ?int $id = null;
    private ?int $orderItemId = null;
    private ?int $productVariantId = null;
    private ?int $userId = null;
    private ?int $rating = null;
    private ?string $comment = null;
    private ?string $shopReply = null;
    private ?string $createdAt = null;
    private ?array $images = null;

    public function __construct(array $data = [])
    {
        if (array_key_exists('id', $data)) {
            $this->id = $data['id'];
        }
        if (array_key_exists('orderItemId', $data)) {
            $this->orderItemId = $data['orderItemId'];
        }
        if (array_key_exists('productVariantId', $data)) {
            $this->productVariantId = $data['productVariantId'];
        }
        if (array_key_exists('userId', $data)) {
            $this->userId = $data['userId'];
        }
        if (array_key_exists('rating', $data)) {
            $this->rating = $data['rating'];
        }
        if (array_key_exists('comment', $data)) {
            $this->comment = $data['comment'];
        }
        if (array_key_exists('shopReply', $data)) {
            $this->shopReply = $data['shopReply'];
        }
        if (array_key_exists('createdAt', $data)) {
            $this->createdAt = $data['createdAt'];
        }
        if (array_key_exists('images', $data)) {
            $this->images = $data['images'];
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getOrderItemId(): ?int
    {
        return $this->orderItemId;
    }

    public function setOrderItemId(?int $orderItemId): void
    {
        $this->orderItemId = $orderItemId;
    }

    public function getProductVariantId(): ?int
    {
        return $this->productVariantId;
    }

    public function setProductVariantId(?int $productVariantId): void
    {
        $this->productVariantId = $productVariantId;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): void
    {
        $this->userId = $userId;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): void
    {
        $this->rating = $rating;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    public function getShopReply(): ?string
    {
        return $this->shopReply;
    }

    public function setShopReply(?string $shopReply): void
    {
        $this->shopReply = $shopReply;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getImages(): ?array
    {
        return $this->images;
    }

    public function setImages(?array $images): void
    {
        $this->images = $images;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'orderItemId' => $this->orderItemId,
            'productVariantId' => $this->productVariantId,
            'userId' => $this->userId,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'shopReply' => $this->shopReply,
            'createdAt' => $this->createdAt,
            'images' => $this->images,
        ];
    }
}