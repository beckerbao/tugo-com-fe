<?php
// Bắt đầu session
session_start();
$page_title = "Write a Review for Sale";
include '../helpers/common.php';
include '../includes/header.php';
include '../helpers/apiCaller.php';

APICaller::init();

// Biến lưu giá trị hiển thị trên form
$tour_name = '';
$sale_name = '';
$booking_id = '';
$is_zalo = false;
$version = get_version();

// Nếu mở trang lần đầu (GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $tour_name = $_GET['tour_name'] ?? '';
    $sale_name = $_GET['sale_name'] ?? '';
    $booking_id = $_GET['booking_id'] ?? '';

    // Kiểm tra User-Agent có phải từ Zalo không
    $is_zalo = strpos($_SERVER['HTTP_USER_AGENT'], 'Zalo') !== false;

    // Nếu là Zalo thì thông báo mở bằng trình duyệt khác
    if ($is_zalo) {
        $error_message = 'Vui lòng mở bằng trình duyệt khác để gửi đánh giá';
    }
}

// Nếu submit form (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = $_POST['customer_name'] ?? '';
    $customer_phone = preg_replace('/\s+/', '', $_POST['customer_phone'] ?? ''); // Xóa khoảng trắng
    $sale_name = $_POST['sale_name'] ?? '';
    $booking_id = $_POST['booking_id'] ?? '';
    $tour_name = $_POST['tour_name'] ?? '';
    $rating = $_POST['rating'] ?? '';
    $review_content = $_POST['review_content'] ?? '';

    // Xử lý upload hình ảnh
    $files = [];
    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $files[] = new CURLFile($tmp_name, mime_content_type($tmp_name), $_FILES['images']['name'][$key]);
        }
    }

    // Chuẩn bị dữ liệu gửi đến API
    $data = [
        'customer_name' => $customer_name,
        'customer_phone' => $customer_phone,
        'sale_name' => $sale_name,
        'booking_id' => $booking_id,
        'tour_name' => $tour_name,
        'rating' => $rating,
        'review' => $review_content,
    ];

    // Gửi dữ liệu đến API bằng postMultipart
    $response = APICaller::postMultipart('/submit-review-sale', array_merge($data, ['images' => $files]), [
        'Content-Type: multipart/form-data',
    ]);

    // Kiểm tra phản hồi từ API
    if (isset($response['status']) && $response['status'] === 'success') {
        // Lưu thông tin cần thiết vào session để xác minh OTP
        $_SESSION['otp_data'] = [
            'customer_phone' => $customer_phone,
            'review_id' => $response['data']['post_id'] ?? null,
        ];

        // Chuyển sang verifyotpsale.php
        echo "<script>window.location.href='verifyotpsale.php';</script>";
        exit;
    } else {
        $error_message = $response['error'] ?? 'Something went wrong. Please try again.';
    }
}
?>

<link rel="stylesheet" href="../assets/css/review.css?<?php echo $version; ?>">
<div class="review-container">
    <?php if ($is_zalo) { ?>
        <div class="help-container">
            <div class="help-tooltip">Bấm vào nút ... để mở trình duyệt khác</div>
            <div class="help-icon"><i>&uarr;</i></div>
        </div>
    <?php } ?>

    <h2>🎉 Cảm ơn bạn đã đặt tour! Hãy chia sẻ trải nghiệm của bạn nhé! 🎉</h2>

    <?php if (!empty($error_message)): ?>
        <p style="color: red;">Lỗi: <?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="tour-name">Bạn vừa book tour</label>
            <input type="text" id="tour-name" name="tour_name" value="<?php echo htmlspecialchars($tour_name); ?>" readonly>
        </div>

        <div class="form-group">
            <label for="sale-name">Nhân viên sale hỗ trợ</label>
            <input type="text" id="sale-name" name="sale_name" value="<?php echo htmlspecialchars($sale_name); ?>" readonly>
        </div>

        <div class="form-group">
            <label for="booking-id">Mã số booking của bạn</label>
            <input type="text" id="booking-id" name="booking_id" value="<?php echo htmlspecialchars($booking_id); ?>" readonly>
        </div>

        <div class="form-group">
            <label for="customer-name">Tên bạn là</label>
            <input type="text" id="customer-name" name="customer_name" placeholder="Nhập tên khách" required <?php if ($is_zalo) echo 'readonly'; ?>>
        </div>

        <div class="form-group">
            <label for="customer-phone">Số điện thoại liên lạc</label>
            <input type="text" id="customer-phone" name="customer_phone" placeholder="Nhập số điện thoại" required <?php if ($is_zalo) echo 'readonly'; ?>>
        </div>

        <div class="form-group">
            <label for="review-content">Theo bạn dịch vụ của bạn sale được đánh giá thế nào?</label>
            <textarea id="review-content" name="review_content" placeholder="Nhập nội dung đánh giá..." required <?php if ($is_zalo) echo 'readonly'; ?>></textarea>
        </div>

        <div class="form-group">
            <label for="rating">Bạn chấm mấy điểm cho dịch vụ của bạn sale? (1-10)</label>
            <input type="number" id="rating" name="rating" min="1" max="10" required <?php if ($is_zalo) echo 'readonly'; ?>>
        </div>

        <div class="form-group">
            <label for="images">Bạn có muốn khoe ảnh đã chụp tại Tugo hoặc với bạn sale không? (không bắt buộc)</label>
            <input type="file" id="images" name="images[]" accept="image/*">
            <small>Chỉ được tối đa 1 ảnh</small>
        </div>

        <button type="submit" class="submit-button" <?php if ($is_zalo) echo 'disabled'; ?>>Gửi đánh giá</button>
    </form>
    <div class="spacer"></div>
</div>

<?php include '../includes/navbar.php'; ?>
<?php include '../includes/footer.php'; ?>
