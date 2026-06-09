<?php

class RegisterDtoRequest {
    public string $username;
    public string $password;
    public string $email;

    public function __construct(array $data) {
        $this->username = trim((string)($data['username'] ?? ''));
        $this->password = trim((string)($data['password'] ?? ''));
        $this->email = trim((string)($data['email'] ?? ''));
    }


    public function validate(): ?string {
        if (empty($this->username))  return "Username is required.";

        if (empty($this->password)) return "Password is required.";

        if (empty($this->email)) return "Email is required.";

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) return "Invalid email format.";

        return null;
    }
}