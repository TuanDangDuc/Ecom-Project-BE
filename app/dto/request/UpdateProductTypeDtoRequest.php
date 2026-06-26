<?php

class UpdateProductTypeDtoRequest {
    public string $name;
    public ?string $description;

    public function __construct(array $data) {
        $this->name = trim((string)($data['name'] ?? ''));
        $description = trim((string)($data['description'] ?? ''));

        $this->description = $description === '' 
            ? null 
            : $description;
    }

    public function validate(): ?string {
        if (empty($this->name)) return "Name is required.";

        return null;
    }
}
