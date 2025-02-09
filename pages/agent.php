<?php
//detect user agent và hiển thị trên trang agent.php
$user_agent = $_SERVER['HTTP_USER_AGENT'];

// echo "User Agent: $user_agent";

//nếu user sử dụng iOS thì tạo url để mở trình duyệt safari, ng dung trên trang agent.php
if (strpos($user_agent, 'iPhone') || strpos($user_agent, 'iPad') || strpos($user_agent, 'iPod')) {
    echo "<script>
    window.location.href = 'x-web-search://www.example.com';
    </script>";
    exit;
}else if (strpos($user_agent, 'Android')) {
    echo "<script>
    window.location.href = 'googlechrome://www.example.com';
    </script>";
    exit;
}
?>