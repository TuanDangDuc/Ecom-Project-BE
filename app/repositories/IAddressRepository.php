<?php
interface IAddressRepository {
    public function save(Address $address): bool;
    public function findByUserId(int $userId): array;
    public function findById(int $id): ?Address;
    public function updateAddress(Address $address): bool;
    public function deleteAddress(int $id): bool;
}