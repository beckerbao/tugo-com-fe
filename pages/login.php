<?php 
// Ensure session is started
session_start();
$page_title = "Login";
include '../helpers/common.php';
include '../includes/header.php'; 
include '../helpers/apiCaller.php';

APICaller::init();

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';

    $loginResponse = APICaller::post('/login', [
        'phone' => $phone,
        'password' => $password
    ], []);

    if (isset($loginResponse['status']) && $loginResponse['status'] === 'success') {
        // Save token to session
        $_SESSION['jwt_token'] = $loginResponse['data']['token'];
        // var_dump(get_access_token());


        // Save token to local storage via JavaScript
        echo "<script>
            window.location.href='profile.php?notice=login_success';
        </script>";
        exit;
    } else {
        $error_message = $loginResponse['message'] ?? 'Đăng nhập thất bại, vui lý kiểm tra lại thông tin';
    }
}
?>
<link rel="stylesheet" href="../assets/css/login.css">
<div class="login-container">
    <h1>Login</h1>
    <?php if (!empty($error_message)): ?>
        <p style="color: red;"> <?php echo htmlspecialchars($error_message); ?> </p>
    <?php endif; ?>
    <form method="POST">
        <label for="phone">Số điện thoại</label>
        <input type="text" id="phone" name="phone"  placeholder="Nhập số điện thoại. Vd: 0903974323" required>

        <label for="password">Mật khẩu</label>
        <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>

        <button type="submit" name="login">Đăng nhập</button>
    </form>
    <div class="forgot-password">
        <a href="#">Quên mật khẩu?</a>
    </div>
    <div class="back-to-home">
        <a href="home.php">← Quay lại trang chủ</a>
    </div>
    <div class="go-to-register">
        <a href="login2.php">Chưa có tài khoản? Đăng ký ngay</a>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
