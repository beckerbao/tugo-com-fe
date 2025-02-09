<script>
    function openBrowser(url, backupUrls = []) {
        let opened = false;

        // Mở URL trong trình duyệt mặc định
        window.location.href = url;

        // Nếu sau 2 giây mà người dùng vẫn ở đây, tức là trang không mở được
        setTimeout(() => {
            if (!opened && backupUrls.length > 0) {
                // Thử URL tiếp theo
                window.location.href = backupUrls.shift();
                openBrowser(window.location.href, backupUrls);
            }
        }, 2000);
    }

    // Gọi hàm
    function redirectToDeviceBrowserV2() {
        const url = "https://www.example.com";

        // Danh sách thử mở trình duyệt theo thứ tự
        const backupUrls = [
            `x-safari-${url}`, // Safari trên iOS
            `com-apple-mobilesafari-tab:${url}`, // Tab mới trên Safari
            `googlechrome://${url.replace(/^https?:\/\//, '')}`, // Chrome
            `firefox://open-url?url=${url}`, // Firefox
            `x-web-search://?${url}` // Safari Search
        ];

        openBrowser(url, backupUrls);
    }

    function redirectToDeviceBrowser(options = {}) {
        const extraPath = options.extraPath || '';
        const isServerEndPoint = options.isServerEndPoint || false;

        // Lấy API Endpoint từ meta hoặc window.location
        const endpoint = document.querySelector('meta[name="api-endpoint"]')?.getAttribute("content") || window.location.origin;
        
        // Xây dựng URL
        const url = `${isServerEndPoint ? endpoint : window.location.origin}${extraPath}`;

        // Kiểm tra thiết bị
        const userAgent = navigator.userAgent || navigator.vendor || window.opera;

        // iOS
        if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
            setTimeout(() => { window.location.href = `x-safari-${url}`; }, 0);
            setTimeout(() => { window.location.href = `com-apple-mobilesafari-tab:${url}`; }, 1000);
            setTimeout(() => { window.location.href = `googlechrome://${url.replace(/^https?:\/\//, '')}`; }, 2000);
            setTimeout(() => { window.location.href = `firefox://open-url?url=${url}`; }, 3000);
            setTimeout(() => { window.location.href = `x-web-search://?cicd.aitracuuluat.vn`; }, 4000);
            return false;
        }

        // Android
        if (/android/i.test(userAgent)) {
            setTimeout(() => { window.location.href = `intent://${url.replace(/^https?:\/\//, '')}#Intent;scheme=https;package=com.android.chrome;end;`; }, 0);
            setTimeout(() => { window.location.href = `googlechrome://navigate?url=${url}`; }, 1000);
            setTimeout(() => { window.location.href = `firefox://open-url?url=${url}`; }, 2000);
            return false;
        }

        // Mở trang web bình thường nếu không phải iOS/Android
        window.location.href = url;
        return true;
    }

</script>

<button onclick="redirectToDeviceBrowserV2()">Mở trình duyệt</button>
