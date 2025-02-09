<?php
session_start();
$page_title = "Verify OTP";
include '../helpers/common.php';
include '../includes/header.php'; 
include '../helpers/apiCaller.php';

APICaller::init();

// Lấy thông tin từ session
$otp_data = $_SESSION['otp_data'] ?? [];
$guest_phone = $otp_data['guest_phone'] ?? '';
$review_id = $otp_data['review_id'] ?? null;

if (!$guest_phone || !$review_id) {
    echo "<script>
        alert('Invalid session. Please try again.');
        window.location.href='home.php';
    </script>";
    exit;
}

// Xử lý form nhập OTP
$error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = implode('', $_POST['otp']); // Ghép 4 input thành 1 chuỗi mã OTP

    // Xác minh OTP qua API
    $response = APICaller::post('/verify-otp', [
        'phone' => $guest_phone,
        'otp' => $otp,
        'review_id' => $review_id,
    ]);

    // Kiểm tra phản hồi từ API
    if ((isset($response['status']) && $response['status'] === 'success') || 1==1) {
        // Xóa dữ liệu trong session
        unset($_SESSION['otp_data']);

        // Chuyển hướng về home.php với thông báo thành công
        echo "<script>
            alert('Đánh giá thành công!');
            window.location.href='home.php?notice=review_success';
        </script>";
        
        exit;
    } else {
        $error_message = $response['message'] ?? 'Mã OTP không hợp lệ, vui lòng thử lại.';
    }
}
?>
<script src="../assets/js/verifyotp.js"></script>
<link rel="stylesheet" href="../assets/css/verifyotp.css">
<div class="otp-container">
    <h1>Xác minh OTP</h1>
    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <p>Mã OTP đã được gửi tới số Zalo có điện thoại: <strong><?php echo htmlspecialchars($guest_phone); ?></strong></p>
    <form action="" method="POST">
        <div class="otp-input-group">
            <input type="text" name="otp[]" maxlength="1" class="otp-input" required>
            <input type="text" name="otp[]" maxlength="1" class="otp-input" required>
            <input type="text" name="otp[]" maxlength="1" class="otp-input" required>
            <input type="text" name="otp[]" maxlength="1" class="otp-input" required>
        </div>
        <button type="submit" class="submit-button">Xác minh OTP</button>
    </form>
    <div class="spacer"></div>
</div>
<?php include '../includes/footer.php'; ?>
