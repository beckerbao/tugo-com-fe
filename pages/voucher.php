<?php 
$page_title = "Voucher Details";
include '../includes/header.php'; 
?>
<link rel="stylesheet" href="../assets/css/voucher.css">
<div class="voucher-container">
    <button class="back-button" onclick="history.back()">Back</button>
    <div class="voucher-header">
        <img src="../assets/images/qrcode-placeholder.png" alt="QR Code" style="width: 150px; height: 150px; margin: 0 auto; display: block;">        
    </div>
    <div class="voucher-details">
        <h2>Discount 20% off</h2>
        <p><strong>Code:</strong> SAVE20</p>
        <p><strong>Valid Until:</strong> 2024-01-15</p>
        <p><strong>Status:</strong> <span style="color: green;">Active</span></p>
        <div class="voucher-policy" style="border: 1px solid #ddd; padding: 10px; border-radius: 5px;">
            <h3>Policy</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin mollis urna non augue cursus, in lacinia elit eleifend. Vivamus in feugiat quam, non cursus ligula. (Lipsum dài 3000 từ).</p>
        </div>
    </div>
</div>
<?php include '../includes/navbar.php'; ?>
<?php include '../includes/footer.php'; ?>
