<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Opening App...</title>
    <script>
      window.onload = function () {
        // 1. Parse query params
        const params = new URLSearchParams(window.location.search);
        const path = params.get("path") || "/"; // e.g. /voucher/detail
        const query = params.get("query") || ""; // e.g. id=15

        // 2. Tạo các đường dẫn
        const fullPath = query ? `${path}?${query}` : path;
        const universalLink = `https://review.tugo.com.vn${fullPath}`;
        const iosFallback = "https://apps.apple.com/app/id6743953061";
        const androidFallback = "https://play.google.com/store/apps/details?id=com.tugo.travel.vn";
        const androidIntent = `intent://${fullPath.replace(/^\//, '')}#Intent;scheme=tugo;package=com.tugo.travel.vn;end`;

        // 3. Detect thiết bị
        const ua = navigator.userAgent || navigator.vendor || window.opera;
        const isIOS = /iPad|iPhone|iPod/.test(ua) && !window.MSStream;
        const isAndroid = /android/i.test(ua);

        // 4. Xử lý chuyển hướng
        if (isIOS) {
          window.location.href = universalLink;
          setTimeout(() => {
            window.location.href = iosFallback;
          }, 2000);
        } else if (isAndroid) {
          window.location.href = androidIntent;
          setTimeout(() => {
            window.location.href = androidFallback;
          }, 2000);
        } else {
          // Desktop fallback (hoặc web preview)
          window.location.href = universalLink;
        }
      };
    </script>
  </head>
  <body>
    <p>Đang mở ứng dụng...</p>
  </body>
</html>
