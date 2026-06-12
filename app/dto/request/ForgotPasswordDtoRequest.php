<?php

class ForgotPasswordDtoRequest {
    public string $email;

      
    public function __construct(array $data)
    {
        $this->email = trim((string)($data['email'] ?? ''));
    }

    public function validate(): ?string {
        if (empty($this->email)) return "email is required";
        
        return null;
    }
}