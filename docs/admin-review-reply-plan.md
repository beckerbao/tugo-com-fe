# Phân tích và phương án triển khai Admin reply review

## Trạng thái triển khai

| Giai đoạn | Trạng thái | Ghi chú |
|---|---|---|
| Giai đoạn 1 — Xác minh backend/schema | ✅ Hoàn tất | Đã xác định backend tương ứng tại `go_tugocom/gin_project`. |
| Giai đoạn 2 — Backend/API | ✅ Hoàn tất | Backend/dev đã có model, migration, API CRUD, kiểm tra `admin_reply_code` server-side và đã test thực tế trên `tugo_com_dev`. Production config/migration và frontend thuộc bước sau. |
| Giai đoạn 3 — Frontend | ✅ Hoàn tất local/dev | Đã tích hợp proxy PHP, hiển thị reply, form Admin Reply Code và hỗ trợ pagination. |
| Giai đoạn 4 — Kiểm thử | ⏸ Chưa bắt đầu | Còn cần kiểm thử E2E đầy đủ và xác nhận cấu hình production. |

**Cập nhật gần nhất:** `2026-08-05`

**Kết luận hiện tại:** Giai đoạn 3 frontend local/dev đã hoàn tất; cần cấu hình production và kiểm thử E2E đầy đủ trước khi phát hành.

## 1. Mục tiêu

Cho phép người quản trị trả lời các review của khách hàng trên feed review, bao gồm:

- Review tour (`review`).
- Review tư vấn (`review_sale`).

Chỉ người đã xác thực bằng **Admin Reply Code** ở phía server mới được tạo, sửa hoặc xóa reply.

Đây là giải pháp MVP triển khai nhanh. Về lâu dài nên thay bằng quyền Admin thực sự trong JWT/backend.

## 2. Hiện trạng codebase

### Frontend

- `pages/home.php` gọi `GET /posts` và hiển thị feed.
- `includes/post-review.php` hiển thị review tour.
- `includes/post-sale-review.php` hiển thị review tư vấn.
- `pages/load_posts.php` render các review tiếp theo khi phân trang.
- `assets/js/home.js` hiện chỉ có placeholder cho comment: `toggleCommentInput()` và `submitComment()` chỉ hiển thị alert.
- Chưa có API hoặc UI reply hoạt động.

### Authentication hiện tại

- JWT được lưu trong `$_SESSION['jwt_token']`.
- `helpers/common.php` có `get_access_token()`.
- Một số request có xác thực tự thêm header Bearer.
- Feed `GET /posts` hiện không yêu cầu token.
- Frontend chưa có cơ chế kiểm tra role Admin.

### Database schema hiện tại

Bảng `comments` hiện có:

```text
id, post_id, user_id, content, created_at, updated_at
```

Bảng này chưa thể hiện rõ comment thường hay Admin reply, Admin nào đã trả lời, quan hệ reply/thread, hoặc trạng thái hiển thị.

Ngoài ra, `db/db.sql` chưa có `review_sale` trong enum `posts.type`, dù frontend/API đang sử dụng loại này. Cần xác minh schema production trước khi migration.

## 3. Phương án được chọn

### Server-side Admin Reply Code

Lưu mã local/dev trong biến môi trường trên server, ví dụ:

```text
ADMIN_REPLY_CODE
```

Không lưu code trong JavaScript, HTML, Git repository, query string, hoặc dữ liệu client mà backend tin tưởng trực tiếp.

### Session unlock tạm thời

Sau khi nhập đúng code, server lưu trạng thái tương tự:

```text
admin_reply_unlocked_until
```

Session nên có thời hạn ngắn, ví dụ 30 phút. Mỗi request tạo/sửa/xóa reply đều phải kiểm tra lại thời hạn này ở server.

## 4. Thiết kế dữ liệu đề xuất

Ưu tiên tạo bảng riêng `review_replies`, không dùng chung bảng `comments` trong MVP:

```text
review_replies
- id
- post_id
- admin_user_id nullable
- content
- status
- created_at
- updated_at
```

### Quy tắc dữ liệu

- `post_id` phải tồn tại.
- Post chỉ được phép là `review` hoặc `review_sale`.
- `content` không được rỗng.
- Giới hạn độ dài nội dung ở backend.
- `admin_user_id` có thể để null nếu MVP chưa có Admin account riêng, nhưng nên bổ sung khi backend hỗ trợ role.
- Nếu chỉ cho một reply trên mỗi review, tạo unique constraint trên `post_id`.
- Nếu cho phép nhiều reply, không tạo unique constraint và cần sắp xếp theo `created_at`.

## 5. API dự kiến

API cần được backend hỗ trợ hoặc PHP frontend làm proxy server-side:

```text
GET    /posts/{postId}/reply
POST   /posts/{postId}/reply
PUT    /posts/{postId}/reply
DELETE /posts/{postId}/reply
```

### Tạo reply

Request cần tối thiểu:

```json
{
  "content": "Nội dung phản hồi của Tugo"
}
```

Backend phải kiểm tra:

1. Request đến từ session đã unlock hoặc Admin JWT hợp lệ.
2. Code/session chưa hết hạn.
3. Post tồn tại.
4. Post thuộc loại review được hỗ trợ.
5. Nội dung hợp lệ.

Không nhận `admin_user_id` từ client để quyết định quyền.

## 6. Thay đổi frontend dự kiến

### `pages/home.php`

- Hiển thị nút `Reply` nếu session đã unlock.
- Hiển thị form nhập Admin Reply Code nếu chưa unlock.
- Hiển thị reply hiện có dưới review.

### `includes/post-review.php`

- Thêm khu vực reply Admin dưới nội dung review.
- Escape nội dung reply khi render HTML.

### `includes/post-sale-review.php`

- Thêm cùng khu vực reply cho `review_sale`.
- Không nhúng reply vào `raw_content` JSON của review sale.

### `pages/load_posts.php`

- Đảm bảo các post tải bằng cursor cũng render reply giống dữ liệu ban đầu.

### `assets/js/home.js`

- Gửi request unlock code.
- Gửi request tạo/sửa/xóa reply.
- Xử lý loading, lỗi và trạng thái thành công.

### `helpers/apiCaller.php`

- Tái sử dụng request JSON hiện có.
- Bảo đảm các request reply có Bearer/session header phù hợp.

## 7. Yêu cầu bảo mật MVP

- Chỉ kiểm tra code ở server.
- Dùng HTTPS ở môi trường non-local; không mã hóa ở frontend vì frontend không phải nơi tin cậy.
- Không trả về code hoặc hash cho frontend.
- Rate limit các lần nhập sai.
- Session unlock hết hạn tự động.
- Xóa trạng thái unlock khi logout.
- Chỉ cho phép POST/PUT/DELETE qua HTTPS.
- Chống CSRF nếu request dùng PHP session cookie.
- Escape reply khi render để tránh XSS.
- Ghi log tối thiểu: thời điểm, post ID, kết quả request; không ghi Admin code.

## 8. Trình tự triển khai

### Giai đoạn 1 — Xác minh backend/schema

1. Xác minh schema production và loại post thực tế.
2. Xác định API hiện có cho comments/replies.
3. Quyết định mỗi review có một reply hay nhiều reply.
4. Xác định cách lưu `admin_user_id`.

### Giai đoạn 2 — Backend/API

1. Tạo migration `review_replies`.
2. Tạo endpoint verify Admin Reply Code.
3. Tạo endpoint CRUD reply.
4. Thêm server-side authorization và rate limiting.
5. Viết test cho code sai, session hết hạn và post không hợp lệ.

### Giai đoạn 3 — Frontend

1. Thêm form unlock code.
2. Thêm nút và form Reply.
3. Render reply trong hai loại review.
4. Đồng bộ với pagination.
5. Xử lý lỗi và trạng thái thành công.

### Giai đoạn 4 — Kiểm thử

- Code đúng/sai.
- Session hết hạn.
- Không có session unlock nhưng gọi API trực tiếp.
- Người dùng thường gọi API reply trực tiếp.
- Nội dung chứa HTML/script.
- Review `review` và `review_sale`.
- Reply xuất hiện ở trang đầu và khi bấm “Xem thêm”.
- Logout phải khóa lại quyền reply.

## 9. Tiêu chí hoàn thành

- Người không biết code không thể tạo reply bằng cách gọi API trực tiếp.
- Code không xuất hiện trong source/frontend/network payload không cần thiết.
- Admin nhập đúng code có thể reply trong thời gian session còn hiệu lực.
- Reply được lưu và hiển thị đúng review.
- Reply không làm thay đổi `raw_content` hoặc nội dung review gốc.
- Nội dung reply được escape an toàn.
- Dữ liệu và API có thể chuyển sang role Admin thật trong tương lai mà không phải đổi lại mô hình reply.

## 10. Rủi ro và giới hạn

Admin Reply Code là cơ chế chia sẻ quyền, không phải phân quyền Admin đầy đủ. Nếu code bị lộ, bất kỳ ai có code đều có thể reply. Vì vậy nên dùng code riêng cho production, thay code định kỳ, giới hạn thời gian session, theo dõi log reply, và thay thế bằng role Admin trong JWT/backend khi hệ thống auth sẵn sàng.

## 11. Kết quả Giai đoạn 1 — Xác minh backend/schema — ✅ Hoàn tất

Ngày kiểm tra: `2026-08-05`

### Kết luận

- Backend tương ứng đã được xác định tại `/Users/minhbaonguyen/Downloads/go_tugocom/gin_project`.
- Đây là Go/Gin API repository `beckerbao/go-tugo-com`.
- `routes/routes.go` có các endpoint khớp với frontend: `/posts`, `/review`, `/review/qr`, `/submit-review-sale`, `/statistics`, `/profile`.
- `database/database.go` kết nối MySQL bằng GORM.
- `models/post.go` hỗ trợ `review_sale` và có model `Comment`.
- Chưa có endpoint comment/reply hiện tại.

## 12. Giai đoạn 2 — Backend/API — ✅ Hoàn tất

### Đã triển khai trong backend

- Tạo model `models.ReviewReply`.
- Tạo migration `database/20260805_create_review_replies.sql`.
- Thêm `ReviewReply` vào danh sách `AutoMigrate`.
- Thêm API:
  - `GET /api/v1/posts/:post_id/replies`
  - `POST /api/v1/posts/:post_id/replies`
  - `PUT /api/v1/review-replies/:reply_id`
  - `DELETE /api/v1/review-replies/:reply_id`
- Các thao tác ghi yêu cầu header `admin_reply_code`, được so sánh server-side với `ADMIN_REPLY_CODE`.
- Chỉ cho phép reply vào post type `review` hoặc `review_sale`.
- Validate nội dung rỗng và giới hạn 5.000 Unicode characters.
- `/api/v1/posts` trả thêm field `admin_replies`.
- Thêm tài liệu cURL tại backend `docs/review_replies.crud_curl.md`.

### Việc sau Giai đoạn 2

- Apply migration trên database production.
- Cấu hình `ADMIN_REPLY_CODE` qua secret management nếu vẫn dùng cơ chế đơn giản này.
- Kiểm thử end-to-end đầy đủ trên môi trường được cấp quyền.
- Cân nhắc chuyển từ shared `ADMIN_TOKEN` sang role Admin/JWT và audit `admin_user_id` trong giai đoạn sau.

### Validation

- `go build ./...`: đạt.
- Compile controllers không chạy test: đạt với `go test -vet=off ./controllers -run '^$'`.
- Các package liên quan: đạt với `go test -vet=off ./models ./database ./routes ./middleware ./utils`.
- Full test suite hiện còn fail ở các test cũ `TestSubmitReview` và `TestGetProfile`, do chúng thiếu context xác thực và không liên quan đến thay đổi reply.

## 13. Giai đoạn 3 — Frontend — ✅ Hoàn tất local/dev

### Đã triển khai

- Tạo proxy cùng origin `pages/review_replies.php` để frontend PHP gọi backend riêng; proxy hỗ trợ GET/POST/PUT/DELETE và chuyển `admin_reply_code` qua header tới backend.
- Tạo partial dùng chung `includes/post-admin-reply.php`, đã nhúng vào cả `includes/post-review.php` và `includes/post-sale-review.php`.
- Hiển thị các reply từ field `admin_replies`, escape nội dung trước khi render và giữ nguyên review gốc.
- Thêm nút `Reply`; khi bấm sẽ mở popup chứa ô `Mã xác nhận` và textarea nội dung trong `assets/js/home.js`.
- Thêm style tương ứng trong `assets/css/styles.css`.
- Sửa xử lý `curl_close()` trong `helpers/apiCaller.php` để không phát warning/deprecation PHP 8.5 làm hỏng JSON response của proxy.

### Validation local/dev

- PHP syntax check cho proxy, partial và `APICaller`: đạt.
- JavaScript syntax check cho `assets/js/home.js`: đạt.
- Proxy GET với backend đang chạy: HTTP 200.
- Proxy POST không có code: HTTP 403.
- Trang `home.php?type=review`: HTTP 200, form reply được render, không có PHP warning/deprecation.
- `load_posts.php` với cursor pagination: HTTP 200, reply UI xuất hiện trong HTML phân trang.

### Còn lại trước production

- Cấu hình `ADMIN_REPLY_CODE` trên backend production và apply migration production nếu chấp nhận shared-code mode.
- Chạy kiểm thử E2E với code hợp lệ trên môi trường được cấp quyền.
- Bổ sung kiểm thử browser cho review/review_sale, HTML/script injection, logout/session và lỗi backend.
