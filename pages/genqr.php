<?php
$page_title = "Generate QR Code";
include '../includes/header.php'; 
include '../helpers/common.php';

// Xử lý form submit
$qr_code_url = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tour_name = $_POST['tour_name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $guide_name = $_POST['guide_name'];

    // Tạo URL với query string
    $base_url = get_current_domain();
    $base_url = $base_url . "/pages/reviewbyqr.php";
    $query_string = http_build_query([
        'tour_name' => $tour_name,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'guide_name' => $guide_name
    ]);

    $qr_code_url = $base_url . '?' . $query_string;
}
?>
<link rel="stylesheet" href="../assets/css/genqr.css">

<div class="genqr-container">
    <h1>Generate QR Code</h1>
    <form method="POST">
        <div class="form-group">
            <label for="tour_name">Tour Name</label>
            <input type="text" id="tour_name" name="tour_name" placeholder="Enter the tour name" required>
        </div>

        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" id="start_date" name="start_date" placeholder="dd/mm/yyyy" pattern="\d{2}/\d{2}/\d{4}" required>
        </div>

        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" id="end_date" name="end_date" placeholder="dd/mm/yyyy" pattern="\d{2}/\d{2}/\d{4}" required>
        </div>

        <div class="form-group">
            <label for="guide_name">Guide Name</label>
            <input type="text" id="guide_name" name="guide_name" placeholder="Enter the guide's name" required>
        </div>

        <button type="submit" class="submit-button">Generate QR Code</button>
    </form>

    <?php if ($qr_code_url): ?>
        <div class="qr-code-section">
            <h2>QR Code</h2>
            <p>Scan this QR code to review the tour:</p>
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?php echo urlencode($qr_code_url); ?>" alt="QR Code">
            <p><strong>URL:</strong> <a href="<?php echo htmlspecialchars($qr_code_url); ?>" target="_blank"><?php echo htmlspecialchars($qr_code_url); ?></a></p>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/navbar.php'; ?>
<?php include '../includes/footer.php'; ?>
