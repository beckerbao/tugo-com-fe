<script>
    function redirectToDeviceBrowser(options = {}) {
        const extraPath = options.extraPath || '';
        const isServerEndPoint = options.isServerEndPoint || false;

        // Lấy endpoint từ môi trường hoặc sử dụng window.location.origin
        const endpoint = typeof import !== "undefined" ? import.meta.env.VITE_API_ENDPOINT : "";
        const url = `${isServerEndPoint ? endpoint : window.location.origin}${extraPath}`;

        // Lấy thông tin thiết bị
        const userAgent = navigator.userAgent || navigator.vendor || window.opera;

        // Kiểm tra thiết bị iOS
        if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
            setTimeout(() => {
                window.location.href = `x-safari-${url}`;
            }, 0);

            setTimeout(() => {
                window.location.href = `com-apple-mobilesafari-tab:${url}`;
            }, 1000);

            setTimeout(() => {
                window.location.href = `googlechrome://${url.replace(/^https?:\/\//, '')}`;
            }, 2000);

            setTimeout(() => {
                window.location.href = `firefox://open-url?url=${url}`;
            }, 3000);

            setTimeout(() => {
                window.location.href = `x-web-search://?cicd.aitracuuluat.vn`;
            }, 4000);

            return false;
        }

        // Kiểm tra thiết bị Android
        if (/android/i.test(userAgent)) {
            setTimeout(() => {
                window.location.href = `intent://${url.replace(/^https?:\/\//, '')}#Intent;scheme=https;package=com.android.chrome;end;`;
            }, 0);

            setTimeout(() => {
                window.location.href = `googlechrome://navigate?url=${url}`;
            }, 1000);

            setTimeout(() => {
                window.location.href = `firefox://open-url?url=${url}`;
            }, 2000);

            return false;
        }

        // Nếu không phải iOS hay Android, mở URL bình thường
        window.location.href = url;
        return true;
    }

</script>

<button onclick="redirectToDeviceBrowser()">Mở trình duyệt</button>
