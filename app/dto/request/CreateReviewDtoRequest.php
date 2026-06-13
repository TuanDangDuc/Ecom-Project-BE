<?php

class CreateReviewDtoRequest
{
    public ?int $orderItemId;
    public ?int $rating;
    public string $comment;

    public function __construct(array $data)
    {
        $this->orderItemId = isset($data['orderItemId']) ? (int)$data['orderItemId'] : null;
        $this->rating = isset($data['rating']) ? (int)$data['rating'] : null;
        $this->comment = trim((string)($data['comment'] ?? ''));
    }

    public function validate(): ?string
    {
        if ($this->orderItemId === null || $this->rating === null) {
            return 'orderItemId and rating are required.';
        }
        if ($this->rating < 1 || $this->rating > 5) {
            return 'Rating must be between 1 and 5.';
        }
        return null;
    }
}
