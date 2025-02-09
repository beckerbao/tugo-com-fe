<?php
//detect user agent và hiển thị trên trang agent.php
$user_agent = $_SERVER['HTTP_USER_AGENT'];

// echo "User Agent: $user_agent";

//nếu user sử dụng iOS thì mở bằng safari, nếu sử dụng Android thì mở bằng chrome
if (strpos($user_agent, 'iPhone') !== false || strpos($user_agent, 'iPad') !== false || strpos($user_agent, 'iPod') !== false) {
    header('Location: https://safari.com');
} elseif (strpos($user_agent, 'Android') !== false) {
    header('Location: https://chrome.com');
}
?>