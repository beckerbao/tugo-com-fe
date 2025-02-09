<?php
// Ensure session is started
session_start();
$page_title = "Write a Review by QR";
include '../includes/header.php'; 
include '../helpers/apiCaller.php';
include '../helpers/common.php';

APICaller::init();

// Biến lưu giá trị để hiển thị trên form
$tour_name = '';
$guide_name = '';
$start_date_display = '';
$end_date_display = '';
$is_zalo = false;

// Nếu là mở trang lần đầu (GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $tour_name = $_GET['tour_name'] ?? '';
    $guide_name = $_GET['guide_name'] ?? '';
    $start_date_display = $_GET['start_date'] ?? ''; // dd/mm/yyyy
    $end_date_display = $_GET['end_date'] ?? '';     // dd/mm/yyyy

    //tạo URL để redirect qua safari hoặc chrome
    $redirect_url = get_current_domain() . "/pages/reviewbyqr.php?tour_name=" . urlencode($tour_name) . "&start_date=" . urlencode($start_date_display) . "&end_date=" . urlencode($end_date_display) . "&guide_name=" . urlencode($guide_name);

    //kiểm tra user-agent nếu có chữ Zalo thì ghi nhận là true
    $is_zalo = strpos($_SERVER['HTTP_USER_AGENT'], 'Zalo') !== false;

    // nếu $is_zalo = true thì hiển thị error message "Bạn vui lòng mở bằng trình duyệt khác để gửi đánh giá"
    if ($is_zalo) {
        $error_message = 'Vui lòng mở bằng trình duyệt khác để gửi đánh giá';
    }
}

// Nếu là submit form (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Chuyển đổi ngày từ định dạng dd/mm/yyyy sang yyyy-mm-dd
    $start_date = $_POST['start_date'] ? date('Y-m-d', strtotime(str_replace('/', '-', $_POST['start_date']))) : '';
    $end_date = $_POST['end_date'] ? date('Y-m-d', strtotime(str_replace('/', '-', $_POST['end_date']))) : '';

    // Chuẩn bị dữ liệu gửi đến API
    $data = [
        'tour_name' => $_POST['tour_name'],
        'start_date' => $start_date,
        'end_date' => $end_date,
        'guide_name' => $_POST['guide_name'],
        'guest_name' => $_POST['guest_name'],
        'guest_phone' => $_POST['guest_phone'],
        'review_content' => $_POST['review_content'],
    ];

    // Xử lý upload hình ảnh
    $files = [];
    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $files[] = new CURLFile($tmp_name, mime_content_type($tmp_name), $_FILES['images']['name'][$key]);
        }
    }

    // Gửi dữ liệu đến API bằng hàm postMultipart
    $response = APICaller::postMultipart('/review/qr', array_merge($data, ['images' => $files]), [
        'Content-Type: multipart/form-data',
    ]);    

    // Kiểm tra kết quả từ API
    if (isset($response['status']) && $response['status'] === 'success') {
        // Lưu thông tin cần thiết vào session để sử dụng trên trang verifyotp.php
        $_SESSION['otp_data'] = [
            'guest_phone' => $_POST['guest_phone'],
            'review_id' => $response['data']['id'] ?? null,
        ];

        // Redirect sang verifyotp.php để xử lý OTP
        echo "<script>
            window.location.href='verifyotp.php';
        </script>";
        exit;
    } else {
        $error_message = $response['error'] ?? 'Something went wrong. Please try again.';

        $tour_name = $_POST['tour_name'] ?? '';
        $guide_name = $_POST['guide_name'] ?? '';
        $start_date_display = $_POST['start_date'] ?? '';
        $end_date_display = $_POST['end_date'] ?? '';
        $guest_name = $_POST['guest_name'] ?? '';
        $guest_phone = $_POST['guest_phone'] ?? '';
        $review_content = $_POST['review_content'] ?? '';
    }
}
?>
<link rel="stylesheet" href="../assets/css/review.css">
<div class="review-container">
    <!-- <button class="back-button" onclick="location.href='home.php'">Quay lại</button> -->
    <!-- Help Section -->
    <?php
    if ($is_zalo) {
    ?>
    <div class="help-container">
        <div class="help-tooltip">
            Bấm vào nút ... để mở trình duyệt khác
        </div>
        <div class="help-icon">
            <i>&uarr;</i>
        </div>
    </div>
    <?php
    }
    ?>
    <h2>Viết đánh giá</h2>
    <?php if (!empty($error_message)): ?>
        <p style="color: red;">Lỗi: <?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="tour-name">Tên tour</label>
            <input type="text" id="tour-name" name="tour_name" value="<?php echo htmlspecialchars($tour_name); ?>" readonly>
        </div>

        <div class="form-group">
            <label for="start-date">Ngày khởi hành</label>
            <input type="text" id="start-date" name="start_date" value="<?php echo htmlspecialchars($start_date_display); ?>" readonly>
        </div>

        <div class="form-group">
            <label for="end-date">Ngày về</label>
            <input type="text" id="end-date" name="end_date" value="<?php echo htmlspecialchars($end_date_display); ?>" readonly>
        </div>

        <div class="form-group">
            <label for="guide-name">Hướng dẫn viên</label>
            <input type="text" id="guide-name" name="guide_name" value="<?php echo htmlspecialchars($guide_name); ?>" readonly>
        </div>

        <!-- nếu $is_zalo = true thì readonly -->
        <div class="form-group">
            <label for="guest-name">Tên khách</label>
            <input type="text" id="guest-name" name="guest_name" placeholder="Nhập tên khách" required <?php if ($is_zalo) echo 'readonly'; ?>>
        </div>

        <div class="form-group">
            <label for="guest-phone">Số điện thoại  (có sử dụng Zalo)</label>
            <input type="text" id="guest-phone" name="guest_phone" placeholder="Nhập số điện thoại" required <?php if ($is_zalo) echo 'readonly'; ?>>
        </div>

        <div class="form-group">
            <label for="review-content">Nội dung đánh giá</label>
            <textarea id="review-content" name="review_content" placeholder="Nhập nội dung đánh giá..." required <?php if ($is_zalo) echo 'readonly'; ?>></textarea>
        </div>

        <div class="form-group">
            <label for="images">Đăng ảnh (không bắt buộc)</label>
            <input type="file" id="images" name="images[]" accept="image/*" multiple>
            <small>Chỉ được tối đa 5 ảnh</small>
        </div>
        <!-- nếu $is_zalo = true thì disable button -->
        <button type="submit" class="submit-button" <?php if ($is_zalo) echo 'disabled'; ?>>Gửi đánh giá</button>
    </form>
    <div class="spacer"></div>
</div>
<?php include '../includes/navbar.php'; ?>
<?php include '../includes/footer.php'; ?>
