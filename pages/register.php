<?php
session_start();
$page_title = "Register";
include '../includes/header.php'; 
include '../helpers/apiCaller.php';

APICaller::init();

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    $repassword = $_POST['repassword'] ?? '';

    // Validate inputs
    if (empty($name) || empty($phone) || empty($password) || empty($repassword)) {
        $error_message = "Tất cả thông tin đều phải nhập.";
    } elseif (strlen($password) < 8) {
        $error_message = "Mật khẩu phải nhất 8 kí tự.";
    } elseif ($password !== $repassword) {
        $error_message = "Mật khẩu nhập lại không khớp";
    } else {
        // Call API to register
        $registerResponse = APICaller::post('/register', [
            'name' => $name,
            'phone' => $phone,
            'password' => $password
        ], [
            'Content-Type: application/json'
        ]);

        if (isset($registerResponse['status']) && $registerResponse['status'] === 'success') {            
            // Save token to local storage via JavaScript
            echo "<script>
            window.location.href='profile.php?notice=register_success';
        </script>";
            exit;
        } else {
            $error_message = $registerResponse['message'] ?? 'Đăng ký thất bại. Vui lòng thử lại.';
        }
    }
}
?>
<link rel="stylesheet" href="../assets/css/register.css">
<div class="register-container">
    <h1>Đăng ký tài khoản</h1>
    <?php if (!empty($error_message)): ?>
        <p style="color: red;"> <?php echo htmlspecialchars($error_message); ?> </p>
    <?php endif; ?>
    <form method="POST">
        <label for="name">Tên hiển thị</label>
        <input type="text" id="name" name="name" placeholder="Nhập tên hiển thị" required>

        <label for="phone">Số điện thoại</label>
        <input type="text" id="phone" name="phone" placeholder="Nhập số điện thoại. Vd: 0903974323" required>

        <label for="password">Mật khẩu</label>
        <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>

        <label for="repassword">Nhập lại mật khẩu</label>
        <input type="password" id="repassword" name="repassword" placeholder="Nhập lại mật khẩu" required>

        <button type="submit" name="register">Đăng ký</button>
    </form>
    <div class="back-to-login">
        <a href="login.php">← Đến trang đăng nhập</a>
    </div>
    <div class="back-to-home">
        <a href="home.php">← Quay lại trang chủ</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
