<?php

class UpdateAddressDtoRequest {
    public ?int $id;
    public ?string $province;
    public ?string $district;
    public ?string $ward;
    public ?string $specificAddress;
    public ?bool $isDefault;
    public ?string $addressType;
    public ?string $phoneNumber;
    public ?string $city;
    public ?string $country;

    public function __construct($data) {
        $this->id = isset($data['id']) ? (int)$data['id'] : null;
        $this->province = $data['province'] ?? null;
        $this->district = $data['district'] ?? null;
        $this->ward = $data['ward'] ?? null;
        $this->specificAddress = $data['specificAddress'] ?? null;
        $this->isDefault = isset($data['isDefault']) ? (bool)$data['isDefault'] : null;
        $this->addressType = $data['addressType'] ?? null;
        $this->phoneNumber = $data['phoneNumber'] ?? null;
        $this->city = $data['city'] ?? null;
        $this->country = $data['country'] ?? null;
    }
}
