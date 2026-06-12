<?php

class VerifyOtpDtoRequest {
    public string $requestId;
    public string $otp;

      public function __construct(array $data)
    {
        $this->requestId = trim((string)($data['requestId'] ?? ''));
        $this->otp = trim((string)($data['otp'] ?? ''));
    }

    public function validate(): ?string {
        if (empty($this->requestId)) return "requestId is required";
        if (empty($this->otp)) return "otp is required";
        
        return null;
    }
}