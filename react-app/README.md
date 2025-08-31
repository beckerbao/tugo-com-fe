# Hướng dẫn chạy & Use case kiểm thử

## Yêu cầu môi trường
- Node.js >= 18
- npm hoặc yarn
- Trình duyệt Chrome / Chromium (cho test E2E)

## Cài đặt
```bash
cd react-app
npm install            # hoặc yarn
cp .env.example .env   # thiết lập VITE_BASE_URL, VITE_DOMAIN_URL, VITE_VERSION
```

## Chạy dự án
```bash
npm run dev            # chạy server dev
npm run build          # build production
npm run preview        # xem bản build
npm run lint           # ESLint
npm test               # Jest unit test
npx playwright test    # E2E test
```

## Use case cần kiểm thử

### 1. Xác thực (Login/Register)
- Đăng nhập bằng số điện thoại & mật khẩu  
- Gửi OTP, nhập OTP, lưu JWT  
- Đăng ký người dùng mới  
- Logout xóa JWT

### 2. Trang Home
- Tải thống kê và danh sách bài viết  
- Infinite scroll & lọc `type` qua query  
- Hiển thị các kiểu bài: Review, General, Review Sale

### 3. Hồ sơ & Voucher
- Xem thông tin profile, danh sách review và voucher  
- Chỉnh sửa profile + upload avatar  
- Trang chi tiết voucher (`/voucher/:id`)  

### 4. Gửi Review
- Review tự do `/review`  
- Review theo QR `/reviewbyqr?tour_id=`  
- Upload nhiều ảnh, submit & verify OTP

### 5. QR workflow
- Sinh QR `/genqr` và `/genqr-vngroup`  
- Kiểm tra redirect khi mở bằng Zalo

### 6. Flash Sale
- Trang `/flashsale` hiển thị countdown và menu  
- Trang `/flashsale/:id` chọn ngày, số lượng, đặt tour  

### 7. Trang phụ
- `/reviewsale`, `/thankyou-vngroup`, `/reelfb` hiển thị nội dung tĩnh

### 8. Kiểm tra responsive & style
- Xem trên mobile/desktop  
- Kiểm tra icon, text tiếng Việt

### 9. Unit & E2E tests
- Chạy `npm test` kiểm tra component, hook  
- Chạy `npx playwright test` cho luồng: đăng nhập, đặt tour, gửi review

---

Sau khi chạy xong từng use case, ghi chú vào `TASKS.md` (ví dụ `[x] Login flow – 2025-02-14`).

## Styling
Toàn bộ dự án sử dụng **CSS Modules**. Tất cả các tệp CSS được đặt trong thư mục `src/styles` với hậu tố `.module.css` và được nhập vào component bằng cú pháp:

```ts
import styles from '../styles/example.module.css'

return <div className={styles.wrapper}>Nội dung</div>
```

Điều này giúp cô lập phạm vi của lớp CSS và hỗ trợ khả năng responsive.

## ESLint
Chạy `npm run lint` để kiểm tra lint cho toàn dự án.
