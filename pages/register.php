<?php
session_start();
$page_title = "Register";
include '../includes/header.php'; 
include '../helpers/apiCaller.php';

APICaller::init();

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {    
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    $repassword = $_POST['repassword'] ?? '';

    // Validate inputs
    if (empty($name) || empty($phone) || empty($password) || empty($repassword)) {
        $error_message = "All fields are required.";
    } elseif (strlen($password) < 8) {
        $error_message = "Password must be at least 8 characters.";
    } elseif ($password !== $repassword) {
        $error_message = "Password and Reinput Password must match.";
    } else {
        // Call API to register
        $registerResponse = APICaller::post('/register', [
            'name' => $name,
            'phone' => $phone,
            'password' => $password
        ], [
            'Content-Type: application/json'
        ]);

        if (isset($registerResponse['status']) && $registerResponse['status'] === 'success') {
            // Save token to local storage via JavaScript
            echo "<script>
            window.location.href='profile.php?notice=register_success'";
            exit;
        } else {
            $error_message = $registerResponse['message'] ?? 'Registration failed. Please try again.';
        }
    }
}
?>
<link rel="stylesheet" href="../assets/css/register.css">
<div class="register-container">
    <h1>Register</h1>
    <?php if (!empty($error_message)): ?>
        <p style="color: red;"> <?php echo htmlspecialchars($error_message); ?> </p>
    <?php endif; ?>
    <form method="POST">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" placeholder="Enter your name" required>

        <label for="phone">Phone Number</label>
        <input type="text" id="phone" name="phone" placeholder="Enter your phone number" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>

        <label for="repassword">Reinput Password</label>
        <input type="password" id="repassword" name="repassword" placeholder="Re-enter your password" required>

        <button type="submit" name="register">Register</button>
    </form>
    <div class="back-to-login">
        <a href="login.php">← Back to Login</a>
    </div>
    <div class="back-to-home">
        <a href="home.php">← Back to Homepage</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
