<?php
//detect user agent và hiển thị trên trang agent.php
$user_agent = $_SERVER['HTTP_USER_AGENT'];

echo "User Agent: $user_agent";
?>
<script>
    window.open(url, '_system');
</script>