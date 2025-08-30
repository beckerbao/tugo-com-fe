<?php
session_start();
$page_title = "Voucher Details";
include '../helpers/common.php';
include '../includes/header.php';
include '../helpers/apiCaller.php';

APICaller::init();

$voucher_id = $_GET['id'] ?? null;
$access_token = get_access_token();
$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $access_token,
];

$voucher = [];
$voucher_response = [];
$error_message = '';
if ($voucher_id) {
    $voucher_response = APICaller::getWithHeaders('/voucher/' . $voucher_id, [], $headers);
    if (isset($voucher_response['status']) && $voucher_response['status'] === 'success') {
        $voucher = $voucher_response['data'] ?? [];
    } else {
        $error_message = $voucher_response['message'] ?? 'Không tìm thấy voucher.';
    }
} else {
    $error_message = 'Voucher không hợp lệ.';
}
?>
<link rel="stylesheet" href="../assets/css/voucher.css">
<div class="voucher-container">
    <button class="back-button" onclick="history.back()">Back</button>
    <?php if ($error_message): ?>
        <p><?php echo $error_message; ?></p>
    <?php else: ?>
        <div class="voucher-header">
            <img src="<?php echo isset($voucher['qr_image']) ? (hasHttpOrHttps($voucher['qr_image']) ? $voucher['qr_image'] : get_image_domain() . $voucher['qr_image']) : '../assets/images/qrcode-placeholder.png'; ?>" alt="QR Code">
        </div>
        <div class="voucher-details">
            <h2><?php echo $voucher['name'] ?? ''; ?></h2>
            <p><strong>Mã voucher:</strong> <?php echo $voucher['code'] ?? ''; ?></p>
            <p><strong>Ngày hiệu lực:</strong> <?php echo $voucher['valid_until'] ?? ''; ?></p>
            <p><strong>Trạng thái:</strong> <span class="<?php echo strtolower($voucher['status'] ?? ''); ?>"><?php echo $voucher['status'] ?? ''; ?></span></p>
            <div class="policy-container">
                <h3>Điều kiện áp dụng</h3>
                <p><?php echo $voucher['policy'] ?? ''; ?></p>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php include '../includes/navbar.php'; ?>
<?php include '../includes/footer.php'; ?>
