<?php 
session_start();
$page_title = "Login with OTP";
include '../helpers/common.php';
include '../includes/header.php'; 
include '../helpers/apiCaller.php';

APICaller::init();

// Xử lý form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = $_POST['phone'];

    if (empty($phone)) {
        $error_message = "Vui lòng nhập số điện thoại.";
    } else {
        $data = ['phone' => $phone];
        $response = APICaller::post('/login-with-otp', $data);

        if (isset($response['status']) && $response['status'] === 'success') {
            $_SESSION['otp_phone'] = $phone; // Lưu số điện thoại để xác thực OTP
            echo "<script>window.location.href='login-verify-otp.php';</script>";
            exit;
        } else {
            $error_message = $response['message'] ?? 'Không thể gửi OTP. Vui lòng thử lại.';
        }
    }
}
?>
<link rel="stylesheet" href="../assets/css/login.css">
<div class="login-container">
    <h1>Đăng nhập bằng OTP</h1>

    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <form action="" method="post">
        <label for="phone">Số điện thoại</label>
        <input type="text" id="phone" name="phone" placeholder="Nhập số điện thoại" required>

        <button type="submit">Gửi OTP</button>
    </form>

    <div class="back-to-home">
        <a href="home.php">← Quay lại trang chủ</a>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
