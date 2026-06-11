<?php

class CreateCategoryRequest
{
    public string $name;
    
    public function __construct(array $data)
    {
        $this->name = trim((string)($data['name'] ?? ''));
    }
}