<?php 
// Ensure session is started
session_start();
$page_title = "Write a Review";
include '../includes/header.php'; 
include '../helpers/apiCaller.php';
include '../helpers/common.php';

//TODO: Thay bằng token thực tế
$access_token = get_access_token();

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

    // var_dump($response);die;

    if (isset($response['status']) && $response['status'] === 'error') {
        $error_message = $response['error'];
    } else {
        $success_message = "Review submitted successfully!";
    }
}
?>
<link rel="stylesheet" href="../assets/css/review.css">
<div class="review-container">
    <button class="back-button" onclick="location.href='profile.php'">Back</button>
    <h2>Write a Review</h2>
    <?php if (!empty($error_message)): ?>
        <p style="color: red;">Error: <?php echo $error_message; ?></p>
    <?php endif; ?>
    <?php if (!empty($success_message)): ?>
        <p style="color: green;"><?php echo $success_message; ?></p>
    <?php endif; ?>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="tour-name">Tour Name</label>
            <input type="text" id="tour-name" name="tour_name" placeholder="Enter the tour name" required>
        </div>

        <div class="form-group">
            <label for="start-date">Start Date</label>
            <input type="date" id="start-date" name="start_date" required>
        </div>

        <div class="form-group">
            <label for="end-date">End Date</label>
            <input type="date" id="end-date" name="end_date" required>
        </div>

        <div class="form-group">
            <label for="guide-name">Guide Name</label>
            <input type="text" id="guide-name" name="guide_name" placeholder="Enter the guide's name">
        </div>

        <div class="form-group">
            <label for="review-content">Review</label>
            <textarea id="review-content" name="review_content" placeholder="Write your review here..." required></textarea>
        </div>

        <div class="form-group">
            <label for="images">Upload Photos (optional)</label>
            <input type="file" id="images" name="images[]" accept="image/*" multiple>
            <small>Maximum 5 images allowed</small>
        </div>

        <button type="submit" class="submit-button">Submit Review</button>
    </form>
</div>
<?php include '../includes/navbar.php'; ?>
<?php include '../includes/footer.php'; ?>
