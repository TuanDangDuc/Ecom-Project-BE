<?php

class CreateShopDtoRequest {

    public String $name;
    public String $description;
    public String $avatarUrl;
    public int $userId;

    public function __construct($data) {
        $this->name = $data['name'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->avatarUrl = $data['avatarUrl'] ?? null;
        $this->userId = $data['userId'] ?? null;
    }
    function validate(): bool {
        if ($this->name == null) {
            return false;
        }
        if ($this->description == null) {
            return false;
        }
        if ($this->avatarUrl == null) {
            return false;
        }
        if ($this->userId == null) {
            return false;
        }
        return true;
    }
}