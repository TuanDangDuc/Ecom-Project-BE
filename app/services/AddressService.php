<?php

class AddressService {
    private AddressRepository $addressRepository;

    public function __construct(AddressRepository $addressRepository) {
        $this->addressRepository = $addressRepository;
    }

    public function createAddress(CreateAddressDtoRequest $request): array {
        $address = new Address();
        $address->setProvince($request->province);
        $address->setDistrict($request->district);
        $address->setSpecificAddress($request->specificAddress);
        $address->setPhoneNumber($request->phoneNumber);
        $address->setUserId($request->userId);

        if ($request->ward !== null) $address->setWard($request->ward);
        if ($request->isDefault !== null) $address->setIsDefault($request->isDefault);
        if ($request->addressType !== null) {
            $address->setAddressType(AddressType::from($request->addressType));
        } else {
            $address->setAddressType(AddressType::HOME);
        }
        if ($request->city !== null) $address->setCity($request->city);
        if ($request->country !== null) $address->setCountry($request->country);

        $result = $this->addressRepository->save($address);
        
        if ($result) {
            return ['success' => true, 'message' => 'Address created successfully.'];
        } else {
            return ['success' => false, 'message' => 'Failed to create address.'];
        }
    }

    public function getAddressesByUserId(int $userId): array {
        $addresses = $this->addressRepository->findByUserId($userId);
        $data = [];
        foreach ($addresses as $address) {
            $data[] = [
                'id' => $address->getId(),
                'province' => $address->getProvince(),
                'district' => $address->getDistrict(),
                'ward' => $address->getWard(),
                'specificAddress' => $address->getSpecificAddress(),
                'isDefault' => $address->isDefault(),
                'addressType' => $address->getAddressType()->value,
                'phoneNumber' => $address->getPhoneNumber(),
                'city' => $address->getCity(),
                'country' => $address->getCountry(),
                'userId' => $address->getUserId()
            ];
        }
        return ['success' => true, 'data' => $data];
    }

    public function updateAddress(UpdateAddressDtoRequest $request): array {
        if ($request->id === null) {
            return ['success' => false, 'message' => 'Address ID is required.'];
        }

        $address = $this->addressRepository->findById($request->id);
        if (!$address) {
            return ['success' => false, 'message' => 'Address not found.'];
        }

        if ($request->province !== null) $address->setProvince($request->province);
        if ($request->district !== null) $address->setDistrict($request->district);
        if ($request->ward !== null) $address->setWard($request->ward);
        if ($request->specificAddress !== null) $address->setSpecificAddress($request->specificAddress);
        if ($request->isDefault !== null) $address->setIsDefault($request->isDefault);
        if ($request->addressType !== null) $address->setAddressType(AddressType::from($request->addressType));
        if ($request->phoneNumber !== null) $address->setPhoneNumber($request->phoneNumber);
        if ($request->city !== null) $address->setCity($request->city);
        if ($request->country !== null) $address->setCountry($request->country);

        $result = $this->addressRepository->updateAddress($address);
        
        if ($result) {
            return ['success' => true, 'message' => 'Address updated successfully.'];
        } else {
            return ['success' => false, 'message' => 'Failed to update address.'];
        }
    }

    public function deleteAddress(int $id): array {
        $address = $this->addressRepository->findById($id);
        if (!$address) {
            return ['success' => false, 'message' => 'Address not found.'];
        }

        $result = $this->addressRepository->deleteAddress($id);
        if ($result) {
            return ['success' => true, 'message' => 'Address deleted successfully.'];
        } else {
            return ['success' => false, 'message' => 'Failed to delete address.'];
        }
    }

    public function getAddressById(int $id): array {
        $address = $this->addressRepository->findById($id);
        if (!$address) {
            return ['success' => false, 'message' => 'Address not found.'];
        }

        $data = [
            'id' => $address->getId(),
            'province' => $address->getProvince(),
            'district' => $address->getDistrict(),
            'ward' => $address->getWard(),
            'specificAddress' => $address->getSpecificAddress(),
            'isDefault' => $address->isDefault(),
            'addressType' => $address->getAddressType()->value,
            'phoneNumber' => $address->getPhoneNumber(),
            'city' => $address->getCity(),
            'country' => $address->getCountry(),
            'userId' => $address->getUserId()
        ];
        return ['success' => true, 'data' => $data];
    }
}
