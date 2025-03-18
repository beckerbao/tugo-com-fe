<?php
// Bắt đầu session
session_start();
$page_title = "Write a Review for Sale";
include '../helpers/common.php';
include '../includes/header.php';
include '../helpers/apiCaller.php';

APICaller::init();

?>

<link rel="stylesheet" href="../assets/css/review.css?<?php echo $version; ?>">
<div class="review-container">
    <!-- Phần nhúng Google Form -->
    <section class="google-form">
        <h2>Phản hồi đánh giá</h2>
        <iframe src="https://docs.google.com/forms/d/e/1FAIpQLSeN-wiPpjcwwS5nNCRt5BeHIXOHZ6ZxlmFDAZUheLNMh9lxcA/viewform?embedded=true" 
                width="340" height="2952" frameborder="0" marginheight="0" marginwidth="0" scrolling="no">
            Loading…
        </iframe>
    </section>
    <div class="spacer"></div>
</div>

<?php include '../includes/navbar.php'; ?>
<?php include '../includes/footer.php'; ?>
