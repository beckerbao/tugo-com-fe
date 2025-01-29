<?php 
// Ensure session is started
session_start();
$page_title = "Profile";
include '../includes/header.php'; 
include '../helpers/apiCaller.php';
include '../helpers/common.php';

APICaller::init();

//TODO: Thay bằng token thực tế
$access_token = get_access_token();

// Gọi API để lấy dữ liệu profile
$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $access_token, // Thay bằng token thực tế
];

// Handle logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    $logoutResponse = APICaller::post('/logout', [], $headers);

    if ($logoutResponse['status'] === 'success') {
        // Redirect to home page with a logout success message
        session_destroy();
        echo "<script>    
                window.location.href='home.php?notice=logout_success';
            </script>";
        exit;
    } else {
        $error_message = $logoutResponse['message'] ?? 'Logout failed.';
    }
}

$profile = APICaller::getWithHeaders('/profile', ["voucher"=>"true", "review"=> "true"], $headers);
//if profile[status]==error then redirect to login
if (isset($profile['status']) && $profile['status'] === 'error') {
    // var_dump($profile);
    // var_dump($headers);
    session_destroy();
    echo "<script>
    window.location.href='login.php';
</script>";
    exit;
}
?>
<link rel="stylesheet" href="../assets/css/profile.css">
<div class="profile-container">
    <?php if (isset($profile['error'])): ?>
        <p style="color: red;">Error: <?php echo $profile['error']; ?></p>
    <?php else: 
        $profile = isset($profile['data']) ? $profile['data'] : [];
        $user = isset($profile['user']) ? $profile['user'] : [];
        $vouchers = isset($profile['vouchers']) ? $profile['vouchers'] : [];
        $reviews = isset($profile['reviews']) ? $profile['reviews'] : [];               
    ?>
    <!-- Header Section -->
    <div class="profile-header">
        <img src="<?php echo $user['profile_image']; ?>" alt="Avatar">
        <div class="profile-info">
            <h2><?php echo $user['name']; ?></h2>
            <p>Level: <?php echo $user['level']; ?></p>
            <p>Points: <?php echo $user['points']; ?></p>
        </div>
        <div class="edit-profile">
            <button onclick="location.href='edit-profile.php'">Edit Profile</button>
            <form method="POST" style="margin-top: 10px;">
                <button type="submit" name="logout" class="logout-button">Logout</button>
            </form>
        </div>
    </div>

    <!-- Vouchers Section -->
    <h3 class="section-title">Your Vouchers</h3>
    <ul class="voucher-list">
        <?php
        foreach ($vouchers as $voucher) {
        ?>
        <li>
            <div class="voucher-details">
                <strong>Name: <?php echo $voucher['discount']; ?></strong>
                <p class="voucher-code">Code: <?php echo $voucher['code']; ?></p>
                <p>Valid until: <?php echo $voucher['valid_until']; ?></p>
            </div>
            <div class="voucher-actions">
                <button onclick="location.href='voucher.php'">Xem chi tiết</button>
            </div>
            <div class="voucher-status <?php echo strtolower($voucher['status']); ?>"><?php echo $voucher['status']; ?></div>
        </li>
        <?php
        }
        ?>        
    </ul>

    <!-- Reviews Section -->
    <h3 class="section-title">
        Your Reviews
        <button onclick="location.href='review.php'">Write Review</button>
    </h3>
    <div class="review-list">
        <?php 
        foreach ($reviews as $review) {
        ?>        
        <div class="post">
            <div class="user">
                <img src="<?php echo $user['profile_image']; ?>" alt="User Profile">
                <div class="name"><?php echo $user['name']; ?></div>
            </div>
            <div class="meta">
                <i>🗺️</i> <span>Tour: <?php echo $review['tour_name']; ?></span>
            </div>
            <div class="meta">
                <i>🗺️</i> <span>Guide Name: <?php echo $review['guide_name']; ?></span>
            </div>
            <div class="content">
                <?php echo $review['content']; ?>
            </div>
            <div class="time">Posted: <?php echo $review['created_at']; ?></div>
        </div>
        <?php
        }
        ?>        
    </div>
    <?php endif; ?>
</div>

<?php include '../includes/navbar.php'; ?>
<?php include '../includes/footer.php'; ?>
