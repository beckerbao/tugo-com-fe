<?php
$path = $_GET['path'] ?? '/';
$query = $_GET['query'] ?? '';
$fullPath = $query ? $path . '?' . $query : $path;

// Thông tin app
$universalLink = "https://review.tugo.com.vn" . $fullPath;
$deepLink = "tugo://" . ltrim($fullPath, '/');
$androidIntent = "intent://" . ltrim($fullPath, '/') . "#Intent;scheme=tugo;package=com.tugo.travel.vn;end";
$iosStore = "https://apps.apple.com/app/id6743953061"; // 🔁 Đổi ID thật
$androidStore = "https://play.google.com/store/apps/details?id=com.tugo.travel.vn";

// Detect UA
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$isAndroid = stripos($userAgent, 'android') !== false;
$isIOS = stripos($userAgent, 'iphone') !== false || stripos($userAgent, 'ipad') !== false;
$isWebView = preg_match('/FBAN|FBAV|Instagram|Line|Zalo/i', $userAgent);

// Nếu không phải WebView → redirect trực tiếp
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

// Nếu là WebView → render trang fallback
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Mở ứng dụng Tugo</title>
  <style>
    body { font-family: sans-serif; text-align: center; padding: 40px; }
    a.button {
      display: inline-block;
      padding: 12px 24px;
      background: #6C5CE7;
      color: white;
      border-radius: 8px;
      text-decoration: none;
      font-size: 18px;
      margin-top: 20px;
    }
    a.button:hover {
      background: #5A4BD1;
    }
  </style>
</head>
<body>
  <h2>👉 Mở ứng dụng Tugo để tiếp tục</h2>
  <p>Bạn đang mở từ trình duyệt trong ứng dụng (Zalo, Facebook, v.v...).</p>
  <a class="button" href="<?= htmlspecialchars($deepLink) ?>">Mở ứng dụng Tugo</a>
</body>
</html>
