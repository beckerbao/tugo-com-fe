<?php
// Lấy thông tin từ URL
$path = $_GET['path'] ?? '/';
$query = $_GET['query'] ?? '';
$fullPath = $query ? $path . '?' . $query : $path;

// Link deep link / fallback
$universalLink = "https://review.tugo.com.vn" . $fullPath;
$iosFallback = "https://apps.apple.com/app/id6743953061"; // 🔁 Thay ID thật
$androidFallback = "https://play.google.com/store/apps/details?id=com.tugo.travel.vn";
$androidIntent = "intent://" . ltrim($fullPath, '/') . "#Intent;scheme=tugo;package=com.tugo.travel.vn;end";

// Phát hiện thiết bị
$userAgent = $_SERVER['HTTP_USER_AGENT'];

if (stripos($userAgent, 'iPhone') !== false || stripos($userAgent, 'iPad') !== false) {
    // iOS → Universal Link
    header("Location: $universalLink");
    exit;
} elseif (stripos($userAgent, 'Android') !== false) {
    // Android → Intent link
    header("Location: $androidIntent");
    exit;
} else {
    // Default (desktop hoặc unknown) → Mở trang web
    header("Location: $universalLink");
    exit;
}
?>
