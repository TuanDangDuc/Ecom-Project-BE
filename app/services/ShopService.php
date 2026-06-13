<?php

class ShopService {
    private ShopRepository $shopRepository;

    public function __construct(ShopRepository $shopRepository) {
        $this->shopRepository = $shopRepository;
    }

    public function createShop(CreateShopDtoRequest $request): array {
        $shop = new Shop();
        $shop->setName($request->name);
        $shop->setDescription($request->description);
        $shop->setAvatarUrl($request->avatarUrl);
        $shop->setUserId($request->userId);
        $shop->setStatus(ShopStatus::ACTIVE);
        $shop->setRatingAverage(0.0);

        $result = $this->shopRepository->save($shop);
        
        if ($result) {
            return ['success' => true, 'message' => 'Shop created successfully.'];
        } else {
            return ['success' => false, 'message' => 'Failed to create shop.'];
        }
    }

    public function updateShop(UpdateShopDtoRequest $request): array {
        if ($request->id === null) {
            return ['success' => false, 'message' => 'Shop ID is required.'];
        }

        $shop = $this->shopRepository->findById($request->id);
        if (!$shop) {
            return ['success' => false, 'message' => 'Shop not found.'];
        }

        if ($request->name !== null) $shop->setName($request->name);
        if ($request->description !== null) $shop->setDescription($request->description);
        if ($request->avatarUrl !== null) $shop->setAvatarUrl($request->avatarUrl);

        $result = $this->shopRepository->updateShop($shop);
        
        if ($result) {
            return ['success' => true, 'message' => 'Shop updated successfully.'];
        } else {
            return ['success' => false, 'message' => 'Failed to update shop.'];
        }
    }

    public function getShopsByUserId(int $userId): array {
        $shops = $this->shopRepository->findByUserId($userId);
        $data = [];
        foreach ($shops as $shop) {
            $data[] = [
                'id' => $shop->getId(),
                'name' => $shop->getName(),
                'description' => $shop->getDescription(),
                'status' => $shop->getStatus()->value,
                'avatarUrl' => $shop->getAvatarUrl(),
                'ratingAverage' => $shop->getRatingAverage(),
                'userId' => $shop->getUserId()
            ];
        }
        return ['success' => true, 'data' => $data];
    }

    public function getShopById(int $id): array {
        $shop = $this->shopRepository->findById($id);
        if (!$shop) {
            return ['success' => false, 'message' => 'Shop not found.'];
        }

        $data = [
            'id' => $shop->getId(),
            'name' => $shop->getName(),
            'description' => $shop->getDescription(),
            'status' => $shop->getStatus()->value,
            'avatarUrl' => $shop->getAvatarUrl(),
            'ratingAverage' => $shop->getRatingAverage(),
            'userId' => $shop->getUserId()
        ];
        return ['success' => true, 'data' => $data];
    }

    public function updateShopStatus(int $id, string $status): array {
        $shop = $this->shopRepository->findById($id);
        if (!$shop) {
            return ['success' => false, 'message' => 'Shop not found.'];
        }

        if (!in_array($status, ['ACTIVE', 'INACTIVE'])) {
            return ['success' => false, 'message' => 'Invalid status.'];
        }

        $result = $this->shopRepository->updateShopStatus($id, $status);
        if ($result) {
            return ['success' => true, 'message' => 'Shop status updated successfully.'];
        } else {
            return ['success' => false, 'message' => 'Failed to update shop status.'];
        }
    }

    public function deleteShop(int $id): array {
        $shop = $this->shopRepository->findById($id);
        if (!$shop) {
            return ['success' => false, 'message' => 'Shop not found.'];
        }

        $result = $this->shopRepository->deleteShop($id);
        if ($result) {
            return ['success' => true, 'message' => 'Shop deleted successfully.'];
        } else {
            return ['success' => false, 'message' => 'Failed to delete shop.'];
        }
    }

    public function updateShopRating(int $id, float $rating): array {
        $shop = $this->shopRepository->findById($id);
        if (!$shop) {
            return ['success' => false, 'message' => 'Shop not found.'];
        }

        $result = $this->shopRepository->updateShopRating($id, $rating);
        if ($result) {
            return ['success' => true, 'message' => 'Shop rating updated successfully.'];
        } else {
            return ['success' => false, 'message' => 'Failed to update shop rating.'];
        }
    }
}