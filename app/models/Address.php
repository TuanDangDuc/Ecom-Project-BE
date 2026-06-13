<?php

class Address
{
    private int $id;
    private string $province;
    private string $district;
    private ?string $ward = null;
    private string $specificAddress;
    private bool $isDefault = false;
    private AddressType $addressType = AddressType::HOME;
    private string $phoneNumber;
    private ?string $city = null;
    private ?string $country = 'Vietnam';

    private int $userId;

    public function __construct() {}

    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getProvince(): string { return $this->province; }
    public function setProvince(string $province): void { $this->province = $province; }

    public function getDistrict(): string { return $this->district; }
    public function setDistrict(string $district): void { $this->district = $district; }

    public function getWard(): ?string { return $this->ward; }
    public function setWard(?string $ward): void { $this->ward = $ward; }

    public function getSpecificAddress(): string { return $this->specificAddress; }
    public function setSpecificAddress(string $specificAddress): void { $this->specificAddress = $specificAddress; }

    public function isDefault(): bool { return $this->isDefault; }
    public function setIsDefault(bool $isDefault): void { $this->isDefault = $isDefault; }

    public function getAddressType(): AddressType { return $this->addressType; }
    public function setAddressType(AddressType $addressType): void { $this->addressType = $addressType; }

    public function getPhoneNumber(): string { return $this->phoneNumber; }
    public function setPhoneNumber(string $phoneNumber): void { $this->phoneNumber = $phoneNumber; }

    public function getCity(): ?string { return $this->city; }
    public function setCity(?string $city): void { $this->city = $city; }

    public function getCountry(): ?string { return $this->country; }
    public function setCountry(?string $country): void { $this->country = $country; }

    public function getUserId(): int { return $this->userId; }
    public function setUserId(int $userId): void { $this->userId = $userId; }
}
