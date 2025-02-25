<?php
session_start();
$page_title = "Verify OTP for Sale Review";
include '../helpers/common.php';
include '../includes/header.php';
include '../helpers/apiCaller.php';

APICaller::init();

// Lấy thông tin từ session
$otp_data = $_SESSION['otp_data'] ?? [];
$customer_phone = $otp_data['customer_phone'] ?? '';
$review_id = $otp_data['review_id'] ?? null;

if (!$customer_phone || !$review_id) {
    echo "<script>
        alert('Invalid session. Please try again.');
        window.location.href='reviewsale.php';
    </script>";
    exit;
}

// Xử lý xác thực OTP
$error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = implode('', $_POST['otp']); // Ghép 4 input thành 1 chuỗi OTP

    // Gửi OTP để xác minh qua API
    $response = APICaller::post('/verify-otp', [
        'phone' => $customer_phone,
        'otp' => $otp,
        'review_id' => $review_id,
    ]);

    // Xử lý kết quả trả về từ API
    if (isset($response['status']) && $response['status'] === 'success') {
        // Xóa session sau khi xác thực thành công
        unset($_SESSION['otp_data']);

        // Chuyển hướng về trang thành công
        echo "<script>
            alert('Đánh giá đã được xác nhận thành công!');
            window.location.href='home.php?notice=review_success';
        </script>";
        exit;
    } else {
        $error_message = $response['message'] ?? 'Mã OTP không hợp lệ, vui lòng thử lại.';
    }
}
?>

<link rel="stylesheet" href="../assets/css/verifyotp.css">
<script src="../assets/js/verifyotp.js"></script>

<div class="otp-container">
    <h2>Xác thực OTP</h2>
    
    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <p>Mã OTP đã được gửi tới số Zalo có điện thoại: <strong><?php echo htmlspecialchars($customer_phone); ?></strong></p>
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
