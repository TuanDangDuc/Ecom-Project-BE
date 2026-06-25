<?php

class CreateProductImageDtoRequest
{
    public ?string $url;
    public ?int $imageOrder;

    public function __construct(array $data)
    {
        $this->url = isset($data['url']) ? trim((string)$data['url']) : null;
        $this->imageOrder = isset($data['imageOrder']) ? (int)$data['imageOrder'] : null;
    }

    public function validate(): ?string
    {
        if ($this->url === null || $this->url === '') {
            return 'url is required.';
        }

        return null;
    }
}
