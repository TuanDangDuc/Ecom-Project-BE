<?php
class AddressRepository implements IAddressRepository{
    public function __construct(
        private PDO $db
    ){}

    public function save(Address $address): bool {
        $sql = "insert into address (province, district, ward, specificAddress, isDefault, addressType, phoneNumber, city, country, userId) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $statement = $this->db->prepare($sql);
        
        $typeValue = $address->getAddressType() !== null ? $address->getAddressType()->value : 'HOME';

        return $statement->execute([
            $address->getProvince(),
            $address->getDistrict(),
            $address->getWard(),
            $address->getSpecificAddress(),
            $address->isDefault() ? 1 : 0,
            $typeValue,
            $address->getPhoneNumber(),
            $address->getCity(),
            $address->getCountry(),
            $address->getUserId()
        ]);
    }

    public function findByUserId(int $userId): array {
        $sql = "select * from address where userId = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$userId]);
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        $addresses = [];
        foreach ($rows as $data) {
            $address = new Address();
            $address->setId($data['id']);
            $address->setProvince($data['province']);
            $address->setDistrict($data['district']);
            $address->setWard($data['ward']);
            $address->setSpecificAddress($data['specificAddress']);
            $address->setIsDefault((bool)$data['isDefault']);
            $address->setAddressType(AddressType::from($data['addressType']));
            $address->setPhoneNumber($data['phoneNumber']);
            $address->setCity($data['city']);
            $address->setCountry($data['country']);
            $address->setUserId($data['userId']);
            $addresses[] = $address;
        }
        return $addresses;
    }

    public function findById(int $id): ?Address {
        $sql = "select * from address where id = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id]);
        $data = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$data) return null;

        $address = new Address();
        $address->setId($data['id']);
        $address->setProvince($data['province']);
        $address->setDistrict($data['district']);
        $address->setWard($data['ward']);
        $address->setSpecificAddress($data['specificAddress']);
        $address->setIsDefault((bool)$data['isDefault']);
        $address->setAddressType(AddressType::from($data['addressType']));
        $address->setPhoneNumber($data['phoneNumber']);
        $address->setCity($data['city']);
        $address->setCountry($data['country']);
        $address->setUserId($data['userId']);
        return $address;
    }

    public function updateAddress(Address $address): bool {
        $sql = "update address set province = ?, district = ?, ward = ?, specificAddress = ?, isDefault = ?, addressType = ?, phoneNumber = ?, city = ?, country = ? where id = ?";
        $statement = $this->db->prepare($sql);
        
        $typeValue = $address->getAddressType() !== null ? $address->getAddressType()->value : 'HOME';

        return $statement->execute([
            $address->getProvince(),
            $address->getDistrict(),
            $address->getWard(),
            $address->getSpecificAddress(),
            $address->isDefault() ? 1 : 0,
            $typeValue,
            $address->getPhoneNumber(),
            $address->getCity(),
            $address->getCountry(),
            $address->getId()
        ]);
    }

    public function deleteAddress(int $id): bool {
        $sql = "delete from address where id = ?";
        $statement = $this->db->prepare($sql);
        return $statement->execute([$id]);
    }
}