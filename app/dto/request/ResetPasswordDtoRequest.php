<?php
class ResetPasswordDtoRequest {
    public string $requestId;
    public string $newPassword;


      public function __construct(array $data)
    {
        $this->requestId = trim((string)($data['requestId'] ?? ''));
        $this->newPassword = trim((string)($data['newPassword'] ?? ''));
    }

    public function validate(): ?string {
        if (empty($this->requestId)) return "requestId is required";
        if (empty($this->newPassword)) return "newPassword is required";
        
        return null;
    }
}