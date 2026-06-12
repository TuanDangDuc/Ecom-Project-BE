<?php

class ReviewImage
{
    private ?int $id = null;
    private ?int $reviewId = null;
    private ?string $imageUrl = null;
    private ?int $imageOrder = null;

    public function __construct(array $data = [])
    {
        if (array_key_exists('id', $data)) {
            $this->id = $data['id'];
        }
        if (array_key_exists('reviewId', $data)) {
            $this->reviewId = $data['reviewId'];
        }
        if (array_key_exists('imageUrl', $data)) {
            $this->imageUrl = $data['imageUrl'];
        }
        if (array_key_exists('imageOrder', $data)) {
            $this->imageOrder = $data['imageOrder'];
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

    public function getReviewId(): ?int
    {
        return $this->reviewId;
    }

    public function setReviewId(?int $reviewId): void
    {
        $this->reviewId = $reviewId;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }

    public function getImageOrder(): ?int
    {
        return $this->imageOrder;
    }

    public function setImageOrder(?int $imageOrder): void
    {
        $this->imageOrder = $imageOrder;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'reviewId' => $this->reviewId,
            'imageUrl' => $this->imageUrl,
            'imageOrder' => $this->imageOrder,
        ];
    }
}