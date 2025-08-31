# TASKS
> Cập nhật: 31/08/2025 – hoàn thành bởi ChatGPT.

## 1. Khởi tạo dự án React
- [x] Tạo dự án bằng Vite hoặc CRA, cấu hình TypeScript.
- [x] Thiết lập ESLint/Prettier theo convention hiện tại.
- [x] Khai báo `.env` với `BASE_URL`, `DOMAIN_URL`, `VERSION`.

## 2. Cấu trúc & layout chung
- [x] Tạo thư mục `components`, `pages`, `services`, `hooks`, `styles`.
- [x] Chuyển `includes/header.php` thành `<Header />` chứa thẻ meta và tracking.
- [x] Chuyển `includes/navbar.php` thành `<Navbar />` dùng cho các trang chính.
- [x] Chuyển `includes/footer.php` thành `<Footer />`.
- [x] Viết `App.tsx` với React Router và layout chung.

## 3. Tầng API & tiện ích
- [x] Viết `apiClient` thay cho `APICaller` với các phương thức GET/POST/PUT/DELETE.
- [x] Tạo helper `getImageUrl`, `getTimeAgo` tương tự `helpers/common.php`.
- [x] Viết hook `useAuth` để quản lý JWT trong `localStorage`.

## 4. Xác thực & đăng nhập
- [x] Tạo trang `/login` với form số điện thoại + mật khẩu.
- [x] Tạo trang `/login-otp` gửi OTP (`login2.php`).
- [x] Tạo trang `/login-verify-otp` nhận mã OTP và lưu JWT.
- [x] Tạo trang `/register` theo `register.php`.
- [x] Viết route guard cho các trang cần đăng nhập.

## 5. Trang chủ (`home.php`)
- [x] Tạo trang `/` gọi API `/statistics` và `/posts`.
- [x] Hiển thị số liệu thống kê (đã review, tổng sao...).
- [x] Tạo component `PostReview`, `PostGeneral`, `PostReviewSale` dựa trên `includes/post-*.php`.
- [x] Cài đặt infinite scroll theo `assets/js/home.js`.
- [x] Thêm bộ lọc `type` như tham số query.

## 6. Hồ sơ & voucher
- [x] Tạo trang `/profile` lấy dữ liệu từ `/profile`.
- [x] Hiển thị thông tin người dùng, danh sách voucher, review.
- [x] Thêm nút logout xoá JWT.
- [x] Tạo trang `/edit-profile` với form cập nhật và upload avatar (`editprofile.php`).
- [x] Tạo trang `/voucher/:id` hiển thị chi tiết voucher.

## 7. Gửi review
- [x] Tạo trang `/review` cho review tự do (`review.php`).
- [x] Tạo trang `/reviewbyqr` nhận tham số `tour_id` từ QR.
- [x] Form review cho phép upload nhiều ảnh bằng `FormData`.
- [x] Sau khi submit, điều hướng đến trang `/verify-otp` (`verifyotp.php`).
- [x] Trang `/verify-otp` nhập OTP và hoàn tất gửi review.

## 8. QR workflow
- [x] Tạo trang `/genqr` sinh QR cho URL review (`genqr.php`).
- [x] Bổ sung tuỳ chọn `genqr-vngroup`.
- [x] Viết logic nhận biết trình duyệt Zalo và chuyển hướng phù hợp (`redirectapp.php`).

## 9. Flash sale
- [x] Tạo trang `/flashsale` giống `flashsale_home.php` với menu bộ sưu tập và countdown.
- [x] Tạo trang `/flashsale/:id` giống `flashsale_detail.php`:
  - Chọn ngày khởi hành, số lượng, tính giá.
  - Gọi API đặt tour và hiển thị popup thành công.
- [x] Tạo component `FlashSaleMenu` dựa trên `flashsale-menu.php` nếu có.

## 10. Trang phụ
- [x] Di chuyển `reviewsale.php`, `thankyou-vngroup.php`, `reelfb.php` sang các route tương ứng.
- [x] Render nội dung HTML tĩnh hoặc thông điệp cảm ơn như trang gốc.

## 11. Styling
- [x] Chuyển từng file CSS trong `assets/css` sang CSS Modules hoặc Tailwind.
- [x] Đảm bảo UI responsive như bản PHP.
- [x] Giữ nguyên nội dung tiếng Việt và icon hiện có.

## 12. Kiểm thử
- [x] Viết unit test cho helper và component chính bằng Jest + RTL.
- [x] Viết test e2e tối thiểu cho luồng đăng nhập và đặt tour (có thể dùng Playwright).
- [x] Thiết lập GitHub Actions chạy `npm test` và `npm build`.

## 13. Hướng dẫn chạy & test case
- [x] Tạo README với hướng dẫn chạy và danh sách use case kiểm thử.
