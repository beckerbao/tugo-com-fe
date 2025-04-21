<?php
$path = $_GET['path'] ?? '/';
$query = $_GET['query'] ?? '';
$fullPath = $query ? $path . '?' . $query : $path;

$universalLink = "https://review.tugo.com.vn" . $fullPath;
$deepLink = "tugo://" . ltrim($fullPath, '/');
$androidIntent = "intent://" . ltrim($fullPath, '/') . "#Intent;scheme=tugo;package=com.tugo.travel.vn;end";
$iosStore = "https://apps.apple.com/app/idYOUR_APP_ID"; // 🔁 Thay bằng App Store ID thật
$androidStore = "https://play.google.com/store/apps/details?id=com.tugo.travel.vn";

$userAgent = $_SERVER['HTTP_USER_AGENT'];
$isAndroid = stripos($userAgent, 'android') !== false;
$isIOS = stripos($userAgent, 'iphone') !== false || stripos($userAgent, 'ipad') !== false;
$isWebView = preg_match('/FBAN|FBAV|Instagram|Line|Zalo|TikTok/i', $userAgent);

// Nếu là Safari hoặc Chrome → redirect
if (!$isWebView) {
    if ($isIOS) {
        header("Location: $universalLink");
        exit;
    } elseif ($isAndroid) {
        header("Location: $androidIntent");
        exit;
    } else {
        header("Location: $universalLink");
        exit;
    }
}

// Nếu là WebView, hiển thị trang fallback
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Mở ứng dụng Tugo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body { font-family: sans-serif; text-align: center; padding: 40px; background: #f5f5f5; }
    h2 { color: #6C5CE7; }
    a.button {
      display: inline-block;
      padding: 12px 24px;
      background: #6C5CE7;
      color: white;
      border-radius: 8px;
      text-decoration: none;
      font-size: 18px;
      margin-top: 24px;
    }
    a.button:hover {
      background: #5A4BD1;
    }
    .qr {
      margin-top: 24px;
    }
    .note {
      font-size: 15px;
      margin-top: 16px;
      color: #555;
    }
  </style>
</head>
<body>
  <h2>👉 Mở ứng dụng Tugo để tiếp tục</h2>
  <p>Bạn đang mở từ trình duyệt trong ứng dụng (Zalo, Facebook...).</p>

  <a class="button" href="<?= htmlspecialchars($deepLink) ?>">Mở ứng dụng Tugo</a>

  <p class="note">Nếu bạn chưa cài ứng dụng, vui lòng tải tại:</p>
  <a class="button" href="<?= $isIOS ? $iosStore : $androidStore ?>">Tải Tugo App</a>

  <div class="qr">
    <p class="note">Hoặc quét mã QR trên máy khác:</p>
    <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=<?= urlencode("https://review.tugo.com.vn" . $_SERVER['REQUEST_URI']) ?>" alt="QR Code">
  </div>
</body>
</html>
