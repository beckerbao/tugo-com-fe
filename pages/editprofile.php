<?php
session_start();
$page_title = "Edit Profile";
include '../includes/header.php'; 
include '../helpers/apiCaller.php';
include '../helpers/common.php';

APICaller::init();

// Check if the user is logged in
$access_token = get_access_token();
if ($access_token === null) {    
    echo "<script>
        window.location.href='login.php?notice=not_logged_in';
    </script>";
        exit;
}

// Gọi API để lấy dữ liệu profile
$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $access_token, // Thay bằng token thực tế
];

$profile = APICaller::getWithHeaders('/profile', ["voucher"=>"false", "review"=> "false"], $headers);

if (isset($profile['error'])) {
    die("Error fetching profile: " . htmlspecialchars($profile['error']));
}

$profile = $profile['data']['user'] ?? [];
// var_dump($profile);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    $profileImage = $_FILES['profile_image'] ?? null;

    $errors = [];

    // Validate input
    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($phone)) {
        $errors[] = "Phone number is required.";
    }
    if (!empty($password) && strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }

    // If no errors, send data to API
    if (empty($errors)) {
        $data = [
            'name' => $name,
            'phone' => $phone,
        ];
        if (!empty($password)) {
            $data['password'] = $password;
        }
        if ($profileImage && $profileImage['tmp_name']) {
            $data['profile_image'] = new CURLFile($profileImage['tmp_name'], $profileImage['type'], $profileImage['name']);
        }

        $updateResponse = APICaller::postMultipart('/profile/update', $data, [
            'Authorization: Bearer ' . $access_token,
        ]);

        if (isset($updateResponse['error'])) {
            $errors[] = $updateResponse['error'];
        } else {            
            echo "<script>
                window.location.href='profile.php?notice=update_success';
            </script>";
                exit;
        }
    }
}
?>
<link rel="stylesheet" href="../assets/css/editprofile.css">
<div class="edit-profile-container">
    <h1>Edit Profile</h1>
    <?php if (!empty($errors)): ?>
        <div class="error-message">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($profile['name'] ?? ''); ?>" required>

        <label for="phone">Phone Number</label>
        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($profile['phone'] ?? ''); ?>" required>

        <label for="password">Password (leave blank to keep current)</label>
        <input type="password" id="password" name="password" placeholder="Enter a new password">

        <label for="profile_image">Profile Image</label>
        <input type="file" id="profile_image" name="profile_image" accept="image/*">

        <button type="submit">Save Changes</button>
    </form>
    <div class="back-to-profile">
        <a href="profile.php">← Back to Profile</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>