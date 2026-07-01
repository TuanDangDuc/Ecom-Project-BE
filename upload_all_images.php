<?php
/**
 * upload_all_images.php
 * 
 * Script upload ảnh lên Cloudinary cho tất cả sản phẩm và biến thể.
 * 
 * CÁCH DÙNG:
 * 1. Đặt URL ảnh vào $productImages và $variantImages bên dưới
 * 2. Chạy: php upload_all_images.php
 */

require_once __DIR__ . '/app/core/Database.php';

// ============================================================
//  CẤU HÌNH CLOUDINARY
// ============================================================
$cloudName    = 'dbgsk6byt';
$uploadPreset = 'ecommerce_upload';
$apiUrl       = "https://api.cloudinary.com/v1_1/{$cloudName}/image/upload";

// ============================================================
//  ẢNH CHO TỪNG SẢN PHẨM (thumbnailUrl & imagesUrl)
//  Key = tên sản phẩm trong DB, Value = URL ảnh nguồn
// ============================================================
$productImages = [
    'iPhone 15 Pro Max'           => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=800',
    'Samsung Galaxy S24 Ultra'    => 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=800',
    'MacBook Pro M3 14 inch'      => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=800',
    'Dell XPS 15'                 => 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=800',
    'Tai nghe AirPods Pro 2'      => 'https://images.unsplash.com/photo-1600294037681-c80b4cb5b434?w=800',
    'Apple Watch Series 9'        => 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=800',
    'iPad Pro 11 inch M2'         => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=800',
    'Áo Thun Nam Cổ Tròn Basic'   => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=800',
    'Áo Khoác Bomber Kaki Dày Dặn' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=800',
    // Thêm sản phẩm khác ở đây...
];

$variantImages = [
    'Titan Tự Nhiên' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=600',
    'Titan Đen'      => 'https://images.unsplash.com/photo-1510557880182-3d4d3cba35a5?w=600',
    'Titan Xanh'     => 'https://images.unsplash.com/photo-1591337676887-a217a6970a8a?w=600',
    'Xám Titan'      => 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=600',
    'Đen Titan'      => 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=600',
    'Vàng Titan'     => 'https://images.unsplash.com/photo-1605236453806-6ff36851218e?w=600',
    'Màu Bạc'        => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=600',
    'Xám Không Gian' => 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=600',
    'Màu Trắng'      => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=600',
    'Màu Đen - Size' => 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=600',
    'Màu Xanh Rêu'   => 'https://images.unsplash.com/photo-1544441893-675973e31985?w=600',
    // Thêm biến thể khác ở đây...
];

// ============================================================
//  HÀM UPLOAD ẢNH LÊN CLOUDINARY
// ============================================================
function uploadToCloudinary(string $sourceUrl, string $apiUrl, string $uploadPreset): ?array {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => $apiUrl,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query([
            'file'          => $sourceUrl,
            'upload_preset' => $uploadPreset,
        ]),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $data = json_decode($response, true);

    if ($httpCode === 200 && isset($data['secure_url'])) {
        $base = $data['secure_url'];
        return [
            'thumbnail' => str_replace('/upload/', '/upload/w_150,q_auto,f_auto/', $base),
            'full'      => str_replace('/upload/', '/upload/w_600,q_auto,f_auto/', $base),
        ];
    }

    echo "  ⚠ Upload thất bại (HTTP $httpCode): " . ($data['error']['message'] ?? $response) . "\n";
    return null;
}

// ============================================================
//  BẮT ĐẦU XỬ LÝ
// ============================================================
try {
    $db = Database::connection();
    echo "✅ Kết nối database thành công.\n\n";

    // --- Upload ảnh cho sản phẩm ---
    echo "=== CẬP NHẬT ẢNH SẢN PHẨM ===\n";
    $updateProduct = $db->prepare("UPDATE product SET thumbnailUrl = ?, imagesUrl = ? WHERE name = ?");

    foreach ($productImages as $productName => $sourceUrl) {
        echo "📦 Đang xử lý: $productName\n";
        $urls = uploadToCloudinary($sourceUrl, $apiUrl, $uploadPreset);
        if ($urls) {
            $updateProduct->execute([$urls['thumbnail'], $urls['full'], $productName]);
            echo "  ✅ Đã cập nhật: {$urls['thumbnail']}\n";
        }
        sleep(1); // Tránh rate limit
    }

    // --- Upload ảnh cho biến thể ---
    echo "\n=== CẬP NHẬT ẢNH BIẾN THỂ ===\n";
    $variants = $db->query("SELECT id, options FROM productVariants")->fetchAll(PDO::FETCH_ASSOC);
    $updateVariantImg = $db->prepare("UPDATE productImages SET url = ? WHERE productVariantId = ?");

    foreach ($variants as $v) {
        $matched = false;
        foreach ($variantImages as $keyword => $sourceUrl) {
            if (mb_strpos($v['options'], $keyword) !== false) {
                echo "🎨 Biến thể #{$v['id']} ({$v['options']})\n";
                $urls = uploadToCloudinary($sourceUrl, $apiUrl, $uploadPreset);
                if ($urls) {
                    $updateVariantImg->execute([$urls['full'], $v['id']]);
                    echo "  ✅ Đã cập nhật: {$urls['full']}\n";
                }
                $matched = true;
                sleep(1);
                break;
            }
        }
    }

    echo "\n🎉 Hoàn thành!\n";

} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
