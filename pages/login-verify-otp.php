<?php 
session_start();
$page_title = "Verify OTP";
include '../helpers/common.php';
include '../includes/header.php'; 
include '../helpers/apiCaller.php';

APICaller::init();

// Kiểm tra nếu không có số điện thoại từ login2.php, redirect về login2.php
if (!isset($_SESSION['otp_phone'])) {
    echo "<script>window.location.href='login2.php';</script>";
    exit;
}

$phone = $_SESSION['otp_phone'];

// Xử lý xác thực OTP
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = implode('', $_POST['otp']); // Ghép 4 input thành 1 chuỗi mã OTP
    
    if (empty($otp) || strlen($otp) !== 4) {
        $error_message = "Vui lòng nhập OTP hợp lệ (4 số).";
    } else {
        $data = [
            'phone' => $phone,
            'otp' => $otp
        ];

        $response = APICaller::post('/confirm-otp', $data);

        if (isset($response['status']) && $response['status'] === 'success') {
            // Lưu JWT token vào session
            $_SESSION['jwt_token'] = $response['data']['token'] ?? '';

            // Redirect về home
            echo "<script>window.location.href='home.php?notice=login_success';</script>";
            exit;
        } else {
            $error_message = $response['message'] ?? 'OTP không hợp lệ. Vui lòng thử lại.';
        }
    }
}
?>
<script src="../assets/js/verifyotp.js"></script>
<link rel="stylesheet" href="../assets/css/loginverifyotp.css">
<div class="otp-container">
    <h1>Xác thực OTP</h1>
    <p>Vui lòng nhập mã OTP được gửi đến số điện thoại (Zalo) <strong><?php echo htmlspecialchars($phone); ?></strong></p>

    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <form action="" method="post">
        <div class="otp-input-group">
            <input type="text" name="otp[]" maxlength="1" class="otp-input" required>
            <input type="text" name="otp[]" maxlength="1" class="otp-input" required>
            <input type="text" name="otp[]" maxlength="1" class="otp-input" required>
            <input type="text" name="otp[]" maxlength="1" class="otp-input" required>
        </div>

        <button type="submit" class="submit-button">Xác nhận OTP</button>
    </form>
    <div class="spacer"></div>
</div>
<?php include '../includes/footer.php'; ?>
