<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Đang mở ứng dụng Tugo...</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 40px;
      text-align: center;
      color: #333;
    }
    .button {
      display: inline-block;
      margin-top: 20px;
      padding: 12px 24px;
      background-color: #1e88e5;
      color: white;
      text-decoration: none;
      border-radius: 6px;
    }
  </style>
</head>
<body>
  <h2>🔄 Đang mở ứng dụng Tugo...</h2>
  <p>Nếu bạn thấy thông báo này quá lâu, hãy nhấn nút bên dưới để mở thủ công:</p>
  <a id="fallbackLink" class="button" href="#">Mở ứng dụng Tugo</a>

  <script>
    function getTokenFromURL() {
      const params = new URLSearchParams(window.location.search);
      return params.get("token");
    }

    const token = getTokenFromURL();
    const deepLink = `tugo://reset-password?token=${token}`;
    const fallbackLink = document.getElementById("fallbackLink");

    fallbackLink.href = deepLink;

    // Thử redirect ngay
    window.location.href = deepLink;

    // Nếu không có app → user vẫn thấy fallback nút
  </script>
</body>
</html>
