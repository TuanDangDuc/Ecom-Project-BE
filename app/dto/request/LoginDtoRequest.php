<?php

class LoginDtoRequest
{
    public string $username;
    public string $password;

    public function __construct(array $data)
    {
        $this->username = trim((string)($data['username'] ?? ''));
        $this->password = trim((string)($data['password'] ?? ''));
    }

    public function validate(): ?string
    {
        if (empty($this->username)) return "Username is required.";

        if (empty($this->password)) return "Password is required.";

        return null;
    }
}