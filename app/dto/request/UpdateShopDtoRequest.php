<?php

class UpdateShopDtoRequest {
    public ?int $id;
    public ?string $name;
    public ?string $description;
    public ?string $avatarUrl;

    public function __construct($data) {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->avatarUrl = $data['avatarUrl'] ?? null;
    }
}
