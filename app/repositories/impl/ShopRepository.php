<?php

class ShopRepository implements IShopRepository{
    public function __construct(
        private PDO $db
    ){}

    public function save(Shop $shop): bool {
        $sql = "insert into shops (name, description, shopStatus, avatarUrl, ratingAverage, userId) values (?, ?, ?, ?, ?, ?)";
        $statement = $this->db->prepare($sql);
        
        $statusValue = $shop->getStatus() !== null ? $shop->getStatus()->value : 'ACTIVE';

        return $statement->execute([
            $shop->getName(),
            $shop->getDescription(),
            $statusValue,
            $shop->getAvatarUrl(),
            $shop->getRatingAverage(),
            $shop->getUserId()
        ]);
    }

    public function findById(int $id): ?Shop {
        $sql = "select * from shops where id = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id]);
        $data = $statement->fetch();
        if (!$data) return null;
        
        $shop = new Shop();
        $shop->setId($data['id']);
        $shop->setName($data['name']);
        $shop->setDescription($data['description']);
        $shop->setStatus(ShopStatus::from($data['shopStatus']));
        $shop->setAvatarUrl($data['avatarUrl']);
        $shop->setRatingAverage((float)$data['ratingAverage']);
        $shop->setUserId($data['userId']);
        return $shop;
    }

    public function updateShop(Shop $shop): bool {
        $sql = "update shops set name = ?, description = ?, avatarUrl = ? where id = ?";
        $statement = $this->db->prepare($sql);
        return $statement->execute([
            $shop->getName(),
            $shop->getDescription(),
            $shop->getAvatarUrl(),
            $shop->getId()
        ]);
    }

    public function findByUserId(int $userId): array {
        $sql = "select * from shops where userId = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$userId]);
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        $shops = [];
        foreach ($rows as $data) {
            $shop = new Shop();
            $shop->setId($data['id']);
            $shop->setName($data['name']);
            $shop->setDescription($data['description']);
            $shop->setStatus(ShopStatus::from($data['shopStatus']));
            $shop->setAvatarUrl($data['avatarUrl']);
            $shop->setRatingAverage((float)$data['ratingAverage']);
            $shop->setUserId($data['userId']);
            $shops[] = $shop;
        }
        return $shops;
    }

    public function updateShopStatus(int $id, string $status): bool {
        $sql = "update shops set shopStatus = ? where id = ?";
        $statement = $this->db->prepare($sql);
        return $statement->execute([$status, $id]);
    }

    public function updateShopRating(int $id, float $rating): bool {
        $sql = "update shops set ratingAverage = ? where id = ?";
        $statement = $this->db->prepare($sql);
        return $statement->execute([$rating, $id]);
    }

    public function deleteShop(int $id): bool {
        $sql = "delete from shops where id = ?";
        $statement = $this->db->prepare($sql);
        return $statement->execute([$id]);
    }
}