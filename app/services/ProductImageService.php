<?php

class ProductImageService
{
    private IProductImageRepository $productImageRepository;

    public function __construct(
        IProductImageRepository $productImageRepository
    ) {
        $this->productImageRepository = $productImageRepository;
    }

    public function uploadImages(int $variantId, array $files): array
    {
        $uploadDir = __DIR__ . '/../../public/uploads/products/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $uploadedUrls = [];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

        $normalizedFiles = [];
        if (isset($files['images'])) {
            $fileData = $files['images'];
            if (is_array($fileData['name'])) {
                for ($i = 0; $i < count($fileData['name']); $i++) {
                    if ($fileData['error'][$i] === UPLOAD_ERR_OK) {
                        $normalizedFiles[] = [
                            'name' => $fileData['name'][$i],
                            'type' => $fileData['type'][$i],
                            'tmp_name' => $fileData['tmp_name'][$i],
                            'size' => $fileData['size'][$i]
                        ];
                    }
                }
            } else {
                if ($fileData['error'] === UPLOAD_ERR_OK) {
                    $normalizedFiles[] = $fileData;
                }
            }
        }

        if (empty($normalizedFiles)) {
            return ['success' => false, 'message' => 'No files uploaded or upload error occurred.'];
        }

        $imageOrder = 0;

        foreach ($normalizedFiles as $file) {
            if (!in_array($file['type'], $allowedTypes)) {
                continue;
            }

            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            if (empty($ext)) {
                $ext = $file['type'] === 'image/png' ? 'png' : ($file['type'] === 'image/webp' ? 'webp' : 'jpg');
            }
            $filename = md5(uniqid() . microtime()) . '.' . $ext;
            $destPath = $uploadDir . $filename;

            if (move_uploaded_file($file['tmp_name'], $destPath)) {
                $url = '/uploads/products/' . $filename;
                
                $image = new ProductImages(null, $url, $imageOrder++, $variantId);
                
                $this->productImageRepository->create($image);
                $uploadedUrls[] = $url;
            }
        }

        return [
            'success' => true,
            'message' => 'Images uploaded successfully.',
            'urls' => $uploadedUrls
        ];
    }

    public function deleteImage(int $id): array
    {
        $deleted = $this->productImageRepository->delete($id);

        if ($deleted) {
            return ['success' => true, 'message' => 'Image deleted successfully.'];
        }

        return ['success' => false, 'message' => 'Failed to delete image.'];
    }
}
