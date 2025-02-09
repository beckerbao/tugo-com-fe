<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mở trình duyệt hệ thống</title>
    <script>
        // (function() {
        //     const userAgent = navigator.userAgent || navigator.vendor || window.opera;

        //     // Kiểm tra nếu đang chạy trong WebView (ứng dụng thứ 3)
        //     const isWebView = /(iPhone|iPod|iPad).*AppleWebKit(?!.*Safari)/i.test(userAgent) ||
        //                       (userAgent.includes("Android") && userAgent.includes("Version/"));

        //     if (isWebView) {
        //         let url = "https://www.example.com"; // Thay thế bằng URL thực của bạn
                
        //         if (/iPhone|iPad|iPod/i.test(userAgent)) {
        //             // Mở trong Safari trên iOS
        //             window.location.href = url;
        //         } else if (/Android/i.test(userAgent)) {
        //             // Mở trong Chrome trên Android
        //             window.location.href = "intent://www.example.com#Intent;scheme=https;package=com.android.chrome;end;";
        //         } else {
        //             // Nếu không xác định được nền tảng, mở link trực tiếp
        //             window.location.href = url;
        //         }
        //     }
        // })();

        function openInBrowser() {
            let url = "https://www.example.com"; // Thay thế bằng URL của bạn
            
            try {
                // Mở trong trình duyệt hệ thống (nếu WebView hỗ trợ)
                window.open(url, "_system");
            } catch (e) {
                // Nếu không mở được, mở trực tiếp
                window.location.href = url;
            }
        }
    </script>
</head>
<body>

    <h1>Trang đang chạy trong WebView</h1>
    <p>Nếu trang này mở trong WebView, bạn nên mở bằng trình duyệt mặc định.</p>

    <!-- Nút mở trong Safari hoặc Chrome -->
    <button onclick="openInBrowser()">Mở trên Safari/Chrome</button>

</body>
</html>
