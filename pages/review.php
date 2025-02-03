<?php 
// Ensure session is started
session_start();
$page_title = "Write a Review";
include '../includes/header.php'; 
include '../helpers/apiCaller.php';
include '../helpers/common.php';

APICaller::init();

//TODO: Thay bằng token thực tế
$access_token = get_access_token();

// Kiểm tra xem user đã đăng nhập chưa
if ($access_token==null) {
    echo "<script>
    window.location.href='login.php';
</script>";
    exit;
}

// Xử lý form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'tour_name' => $_POST['tour_name'],
        'start_date' => $_POST['start_date'],
        'end_date' => $_POST['end_date'],
        'guide_name' => $_POST['guide_name'],
        'review_content' => $_POST['review_content'],
    ];

    $headers = [
        // 'Content-Type: application/json',
        'Authorization: Bearer ' . $access_token, // Thay bằng token thực tế
    ];

    // Xử lý upload hình ảnh
    $files = [];
    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $files[] = new CURLFile($tmp_name, mime_content_type($tmp_name), $_FILES['images']['name'][$key]);
        }
    }

    // Gửi dữ liệu đến API bằng hàm post
    $response = APICaller::postMultipart('/review', array_merge($data, ['images' => $files]), $headers);            

    if (isset($response['status']) && $response['status'] === 'error') {
        $error_message = $response['error'];
    } else {
        $success_message = "Review submitted successfully!";
    }
}
?>
<link rel="stylesheet" href="../assets/css/review.css">
<div class="review-container">
    <button class="back-button" onclick="location.href='profile.php'">Quay lại</button>
    <h2>Viết đánh giá</h2>
    <?php if (!empty($error_message)): ?>
        <p style="color: red;">Lỗi: <?php echo $error_message; ?></p>
    <?php endif; ?>
    <?php if (!empty($success_message)): ?>
        <p style="color: green;"><?php echo $success_message; ?></p>
    <?php endif; ?>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="tour-name">Tên tour</label>
            <input type="text" id="tour-name" name="tour_name" placeholder="Enter the tour name" required>
        </div>

       <div class="form-group">
            <label for="start-date">Ngày khởi hành</label>
            <input type="date" id="start-date" name="start_date" pattern="\d{2}/\d{2}/\d{4}" required>
        </div>

        <div class="form-group">
            <label for="end-date">Ngày về</label>
            <input type="date" id="end-date" name="end_date" pattern="\d{2}/\d{2}/\d{4}" required>
        </div>

        <div class="form-group">
            <label for="guide-name">Hướng dẫn viên</label>
            <input type="text" id="guide-name" name="guide_name" placeholder="Enter the guide's name">
        </div>

        <div class="form-group">
            <label for="review-content">Nội dung đánh giá</label>
            <textarea id="review-content" name="review_content" placeholder="Write your review here..." required></textarea>
        </div>

        <div class="form-group">
            <label for="images">Đăng ảnh (không bắt buộc)</label>
            <input type="file" id="images" name="images[]" accept="image/*" multiple>
            <small>Chỉ được tối đa 5 ảnh</small>
        </div>

        <button type="submit" class="submit-button">Gửi đánh giá</button>
    </form>
    <div class="spacer"></div>
</div>
<?php include '../includes/navbar.php'; ?>
<?php include '../includes/footer.php'; ?>
