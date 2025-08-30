# FEATURES

## User & authentication
- Session-based authentication with JWT stored in `$_SESSION`.
- Login via phone/password (`login.php`) or phone/OTP (`login2.php` & `login-verify-otp.php`).
- Logout handled in `profile.php`.

## Profile & vouchers
- Profile page displays user info, vouchers, and submitted reviews.
- Edit profile (`editprofile.php`) and view voucher details (`voucher.php`).

## Review system
- Users can submit tour reviews with optional images (`review.php`).
- QR-based review flow:
  - Generate QR for specific tour (`genqr.php`, `genqr-vngroup.php`).
  - Scan QR to open pre-filled review form (`reviewbyqr.php`, `reviewbyqr-vngroup.php`).
  - OTP verification for submissions (`verifyotp.php`, `verifyotpsale.php`).

## Home feed
- `home.php` shows statistics and a paginated feed of posts (review, general, review_sale).
- Infinite loading via AJAX (`load_posts.php`, `assets/js/home.js`).
- Post templates in `includes/post-*.php`.

## Flash sale module
- Campaign homepage (`flashsale_home.php`) with countdown timers, collections, and navigation.
- Tour detail & booking (`flashsale_detail.php`) featuring date selection, price calculations, and API booking call (`assets/js/flashsale.js`).
- Promotion pages and redirection helpers (`reviewsale.php`, `thankyou-vngroup.php`, etc.).

## Miscellaneous pages
- QR code helpers, demo specifications (`spec.html`, `booking_spec.html`), Facebook reels (`reelfb.php`), and mobile redirects (`redirectapp.php`).
- Global navigation (`includes/navbar.php`) and shared tracking scripts.
