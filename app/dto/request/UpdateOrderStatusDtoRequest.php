<?php

class UpdateOrderStatusDtoRequest
{
    public string $status;

    public function __construct(array $data)
    {
        $this->status = trim((string)($data['status'] ?? ''));
    }

    public function validate(): ?string
    {
        if (empty($this->status)) {
            return "status is required.";
        }

        $allowedStatuses = ['PENDING', 'CONFIRMED', 'SHIPPING', 'COMPLETED', 'CANCELED'];
        if (!in_array(strtoupper($this->status), $allowedStatuses)) {
            return "Invalid status value.";
        }
        return null;
    }
}
