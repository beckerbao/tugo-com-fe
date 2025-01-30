<?php 
// Ensure session is started
session_start();
$page_title = "Login";
include '../includes/header.php'; 
include '../helpers/apiCaller.php';
include '../helpers/common.php';

APICaller::init();

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';

    $loginResponse = APICaller::post('/login', [
        'phone' => $phone,
        'password' => $password
    ], []);

    if (isset($loginResponse['status']) && $loginResponse['status'] === 'success') {
        // Save token to session
        $_SESSION['jwt_token'] = $loginResponse['data']['token'];
        // var_dump(get_access_token());


        // Save token to local storage via JavaScript
        echo "<script>
            window.location.href='profile.php?notice=login_success';
        </script>";
        exit;
    } else {
        $error_message = $loginResponse['message'] ?? 'Login failed. Please try again.';
    }
}
?>
<link rel="stylesheet" href="../assets/css/login.css">
<div class="login-container">
    <h1>Login</h1>
    <?php if (!empty($error_message)): ?>
        <p style="color: red;"> <?php echo htmlspecialchars($error_message); ?> </p>
    <?php endif; ?>
    <form method="POST">
        <label for="phone">Phone Number</label>
        <input type="text" id="phone" name="phone"  placeholder="Enter your phone number" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>

        <button type="submit" name="login">Login</button>
    </form>
    <div class="forgot-password">
        <a href="#">Forgot Password?</a>
    </div>
    <div class="back-to-home">
        <a href="home.php">← Back to Homepage</a>
    </div>
    <div class="go-to-register">
        <a href="register.php">Don't have an account? Register here</a>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
