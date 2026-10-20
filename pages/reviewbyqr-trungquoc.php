<?php
// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$page_title = "Đánh giá chất lượng tour Trung Quốc";
include '../helpers/common.php';
include '../includes/header.php'; 
include '../helpers/apiCaller.php';

APICaller::init();
$version = get_version();

// Bảng Master Data Fallback tĩnh cho 6 nhóm sản phẩm Trung Quốc
$masterDataFallback = [
    'CN_01' => [
        [
            'attractionName' => 'Bến Thượng Hải (Thượng Hải)',
            'questionPrompt' => 'HDV giới thiệu về sự giao thoa kiến trúc phương Tây và sự phát triển hiện đại của Thượng Hải như thế nào?',
            'sortOrder' => 1
        ],
        [
            'attractionName' => 'Ô Trấn - Đông Sách (Cổ trấn Giang Nam)',
            'questionPrompt' => 'Thông tin lịch sử 1.300 năm và văn hóa cổ trấn Giang Nam có rõ ràng, chi tiết không?',
            'sortOrder' => 2
        ],
        [
            'attractionName' => 'Tây Hồ (Hàng Châu)',
            'questionPrompt' => 'HDV truyền tải các điển tích, thi ca và vẻ đẹp truyền thống của Tây Hồ như thế nào?',
            'sortOrder' => 3
        ],
        [
            'attractionName' => 'Phim trường Tam Quốc Thành & Thái Hồ (Vô Tích)',
            'questionPrompt' => 'Trong lúc đi thuyền trên Thái Hồ và tham quan phim trường, HDV có tái hiện lại những điển tích Tam Quốc lừng lẫy hay bối cảnh các bộ phim kinh điển không?',
            'sortOrder' => 4
        ],
    ],
    'CN_02' => [
        [
            'attractionName' => 'Tử Cấm Thành & Quảng Trường Thiên An Môn',
            'questionPrompt' => 'HDV có giới thiệu rõ về ý nghĩa lịch sử của 24 đời vua Minh - Thanh và kiến trúc đặc sắc của Tử Cấm Thành không?',
            'sortOrder' => 1
        ],
        [
            'attractionName' => 'Vạn Lý Trường Thành',
            'questionPrompt' => 'HDV có chia sẻ lôi cuốn về lịch sử xây dựng, mục đích quân sự và các câu chuyện dân gian gắn với Vạn Lý Trường Thành không?',
            'sortOrder' => 2
        ],
        [
            'attractionName' => 'Di Hòa Viên',
            'questionPrompt' => 'HDV có thuyết minh chi tiết về lịch sử Cung điện Mùa Hè không?',
            'sortOrder' => 3
        ],
        [
            'attractionName' => 'Miếu Nhạc Phi',
            'questionPrompt' => 'Câu chuyện lịch sử về vị tướng Nhạc Phi do HDV kể có truyền cảm và giúp quý khách hiểu rõ hơn về di tích không?',
            'sortOrder' => 4
        ],
        [
            'attractionName' => 'Tây Hồ',
            'questionPrompt' => 'HDV truyền tải các điển tích, thi ca và vẻ đẹp truyền thống của Tây Hồ như thế nào?',
            'sortOrder' => 5
        ],
        [
            'attractionName' => 'Bến Thượng Hải',
            'questionPrompt' => 'HDV giới thiệu về sự giao thoa kiến trúc phương Tây và sự phát triển hiện đại của Thượng Hải như thế nào?',
            'sortOrder' => 6
        ],
        [
            'attractionName' => 'Ô Trấn',
            'questionPrompt' => 'Thông tin lịch sử 1.300 năm và văn hóa cổ trấn Giang Nam có rõ ràng, chi tiết không?',
            'sortOrder' => 7
        ],
    ],
    'CN_03' => [
        [
            'attractionName' => 'Cung điện Mỹ Linh',
            'questionPrompt' => 'Thông tin về kiến trúc và câu chuyện Tưởng Giới Thạch & Tống Mỹ Linh có hấp dẫn không?',
            'sortOrder' => 1
        ],
        [
            'attractionName' => 'Bến Thượng Hải',
            'questionPrompt' => 'HDV giới thiệu về sự giao thoa kiến trúc phương Tây và sự phát triển hiện đại của Thượng Hải như thế nào?',
            'sortOrder' => 2
        ],
        [
            'attractionName' => 'Ô Trấn - Đông Sách',
            'questionPrompt' => 'Thông tin lịch sử 1.300 năm và văn hóa cổ trấn Giang Nam có rõ ràng, chi tiết không?',
            'sortOrder' => 3
        ],
        [
            'attractionName' => 'Tây Hồ',
            'questionPrompt' => 'HDV truyền tải các điển tích, thi ca và vẻ đẹp truyền thống của Tây Hồ như thế nào?',
            'sortOrder' => 4
        ],
        [
            'attractionName' => 'Sông Tiền Đường & Thành phố mới G20',
            'questionPrompt' => 'Việc kết nối giữa hiện tượng thủy triều, dấu ấn Truyện Kiều và sự hiện đại của thành phố G20 có thú vị không?',
            'sortOrder' => 5
        ],
    ],
    'CN_04' => [
        [
            'attractionName' => 'Cung điện Mỹ Linh',
            'questionPrompt' => 'Thông tin về kiến trúc và câu chuyện Tưởng Giới Thạch & Tống Mỹ Linh có hấp dẫn không?',
            'sortOrder' => 1
        ],
        [
            'attractionName' => 'Bến Thượng Hải',
            'questionPrompt' => 'HDV giới thiệu về sự giao thoa kiến trúc phương Tây và sự phát triển hiện đại của Thượng Hải như thế nào?',
            'sortOrder' => 2
        ],
        [
            'attractionName' => 'Ô Trấn - Đông Sách',
            'questionPrompt' => 'Thông tin lịch sử 1.300 năm và văn hóa cổ trấn Giang Nam có rõ ràng, chi tiết không?',
            'sortOrder' => 3
        ],
        [
            'attractionName' => 'Tây Hồ',
            'questionPrompt' => 'HDV truyền tải các điển tích, thi ca và vẻ đẹp truyền thống của Tây Hồ như thế nào?',
            'sortOrder' => 4
        ],
        [
            'attractionName' => 'Chùa Phật Ngọc',
            'questionPrompt' => 'HDV có thuyết minh ý nghĩa tâm linh của ngôi chùa không?',
            'sortOrder' => 5
        ],
    ],
    'CN_05' => [
        [
            'attractionName' => 'Miếu Nhạc Phi',
            'questionPrompt' => 'HDV truyền tải câu chuyện lịch sử về tinh thần trung quân ái quốc của danh tướng Nhạc Phi và điển tích các bức tượng sắt Tần Cối quỳ gối tạ tội như thế nào?',
            'sortOrder' => 1
        ],
        [
            'attractionName' => 'Tây Hồ',
            'questionPrompt' => 'HDV thuyết minh về cảnh đẹp Tây Hồ cùng các truyền thuyết dân gian (Lương Sơn Bá – Chúc Anh Đài, Thanh Xà – Bạch Xà) có truyền cảm và cuốn hút Quý khách không?',
            'sortOrder' => 2
        ],
        [
            'attractionName' => 'Cung điện Mỹ Linh',
            'questionPrompt' => 'Quý khách đánh giá thế nào về phần giới thiệu kiến trúc "Versailles Phương Đông" và câu chuyện tình yêu huyền thoại giữa Tưởng Giới Thạch & Tống Mỹ Linh của HDV?',
            'sortOrder' => 3
        ],
        [
            'attractionName' => 'Dinh Tổng Thống Trung Hoa Dân Quốc',
            'questionPrompt' => 'Thông tin lịch sử thời kỳ Dân Quốc và dấu ấn của Tôn Trung Sơn do HDV chia sẻ có rõ ràng, dễ tiếp thu không?',
            'sortOrder' => 4
        ],
        [
            'attractionName' => 'Tháp Duyệt Giang & Chùa Kê Minh',
            'questionPrompt' => 'HDV giới thiệu về kiến trúc cung đình thời Minh (giấc mộng của Chu Nguyên Chương) và giá trị văn hóa - tâm linh 1.700 năm của Chùa Kê Minh có chi tiết không?',
            'sortOrder' => 5
        ],
        [
            'attractionName' => 'Ô Trấn Tây Sách',
            'questionPrompt' => 'Phần thuyết minh của HDV về lịch sử cổ trấn 1.300 năm tuổi, di sản văn hóa UNESCO và kiến trúc nhà cổ vùng sông nước Giang Nam có làm Quý khách hài lòng không?',
            'sortOrder' => 6
        ],
    ],
    'CN_06' => [
        [
            'attractionName' => 'Miếu Nhạc Phi',
            'questionPrompt' => 'HDV có giới thiệu chi tiết về lịch sử danh tướng Nhạc Phi và ý nghĩa của các bức tượng trong miếu không?',
            'sortOrder' => 1
        ],
        [
            'attractionName' => 'Chùa Phật Ngọc',
            'questionPrompt' => 'HDV có thuyết minh ý nghĩa tâm linh của ngôi chùa không?',
            'sortOrder' => 2
        ],
        [
            'attractionName' => 'Hàn Sơn Tự',
            'questionPrompt' => 'HDV có thuyết minh về lịch sử ngôi chùa cổ không?',
            'sortOrder' => 3
        ],
        [
            'attractionName' => 'Ô Trấn Tây Sách',
            'questionPrompt' => 'HDV có giới thiệu về lịch sử 1.300 năm, nét văn hóa sông nước Giang Nam và kiến trúc cổ tại Ô Trấn không?',
            'sortOrder' => 4
        ],
        [
            'attractionName' => 'Bến Thượng Hải',
            'questionPrompt' => 'HDV giới thiệu về sự giao thoa kiến trúc phương Tây và sự phát triển hiện đại của Thượng Hải như thế nào?',
            'sortOrder' => 5
        ],
    ],
];

$templateGroupMap = [
    '1' => 'CN_01',
    '2' => 'CN_02',
    '3' => 'CN_03',
    '4' => 'CN_04',
    '5' => 'CN_05',
    '6' => 'CN_06',
];

if (!function_exists('get_attractions_for_tour')) {
    /**
     * Lấy danh sách điểm tham quan theo template hoặc fallback Master Data
     */
    function get_attractions_for_tour($template_id, $product_group, $masterDataFallback, $templateGroupMap) {
        $attractions = null;
        if (!empty($template_id)) {
            try {
                $attractions = APICaller::getReviewTemplateAttractions($template_id);
            } catch (Exception $e) {
                $attractions = null;
            }
        }

        // Nếu API lỗi, trả về null hoặc mảng rỗng -> kích hoạt Master Data Fallback
        if (empty($attractions)) {
            $groupKey = null;
            if (!empty($template_id) && isset($templateGroupMap[(string)$template_id])) {
                $groupKey = $templateGroupMap[(string)$template_id];
            } elseif (!empty($product_group) && isset($masterDataFallback[$product_group])) {
                $groupKey = $product_group;
            }

            if (!$groupKey || !isset($masterDataFallback[$groupKey])) {
                $groupKey = 'CN_01';
            }

            $attractions = $masterDataFallback[$groupKey];
        }

        return $attractions;
    }
}

if (!function_exists('convert_date_to_db_format')) {
    /**
     * Chuyển đổi định dạng ngày từ DD/MM/YYYY sang YYYY-MM-DD
     */
    function convert_date_to_db_format($dateStr) {
        $dateStr = trim($dateStr ?? '');
        if (empty($dateStr)) {
            return '';
        }
        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $dateStr, $matches)) {
            return sprintf('%04d-%02d-%02d', $matches[3], $matches[2], $matches[1]);
        }
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateStr)) {
            return $dateStr;
        }
        $timestamp = strtotime(str_replace('/', '-', $dateStr));
        return $timestamp ? date('Y-m-d', $timestamp) : '';
    }
}

// Khởi tạo các biến hiển thị
$template_id = $_GET['template_id'] ?? $_POST['template_id'] ?? '';
$product_group = $_GET['product_group'] ?? $_POST['product_group'] ?? '';
$tour_name = $_GET['tour_name'] ?? $_POST['tour_name'] ?? '';
$guide_name = $_GET['guide_name'] ?? $_POST['guide_name'] ?? '';
$start_date_display = $_GET['start_date'] ?? $_POST['start_date'] ?? '';
$end_date_display = $_GET['end_date'] ?? $_POST['end_date'] ?? '';

$local_guide_name = '';
$local_guide_general_review = '';
$tour_review = '';
$guest_name = '';
$guest_phone = '';
$review_content = '';
$attraction_reviews = [];
$error_message = '';

// Kiểm tra User-Agent Zalo WebView
$is_zalo = (strpos($_SERVER['HTTP_USER_AGENT'] ?? '', 'Zalo') !== false);
if ($is_zalo && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $error_message = 'Vui lòng mở bằng trình duyệt khác để gửi đánh giá';
}

// Xử lý nộp form POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tour_name = $_POST['tour_name'] ?? '';
    $guide_name = $_POST['guide_name'] ?? '';
    $start_date_display = $_POST['start_date'] ?? '';
    $end_date_display = $_POST['end_date'] ?? '';
    $template_id = $_POST['template_id'] ?? '';
    $product_group = $_POST['product_group'] ?? '';

    $guest_name = trim($_POST['guest_name'] ?? '');
    $guest_phone = preg_replace('/\s+/', '', $_POST['guest_phone'] ?? '');
    $review_content = trim($_POST['review_content'] ?? '');
    $local_guide_name = trim($_POST['local_guide_name'] ?? '');
    $local_guide_general_review = trim($_POST['local_guide_general_review'] ?? '');
    $tour_review = trim($_POST['tour_review'] ?? '');
    $attraction_reviews = $_POST['attraction_reviews'] ?? [];

    $start_date = convert_date_to_db_format($start_date_display);
    $end_date = convert_date_to_db_format($end_date_display);

    // Validate bắt buộc
    if (empty($guest_name) || mb_strlen($guest_name) < 2) {
        $error_message = 'Vui lòng nhập tên khách hàng (tối thiểu 2 ký tự).';
    } elseif (empty($guest_phone) || !preg_match('/^[0-9]{9,11}$/', $guest_phone)) {
        $error_message = 'Vui lòng nhập số điện thoại hợp lệ (9-11 chữ số).';
    } elseif (empty($review_content)) {
        $error_message = 'Vui lòng nhập nội dung đánh giá trưởng đoàn từ Việt Nam.';
    }

    // Xử lý upload và validate ảnh nếu có
    $files = [];
    if (empty($error_message) && !empty($_FILES['images']['name'][0])) {
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxFileSize = 10 * 1024 * 1024; // 10MB

        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            if (empty($tmp_name) || $_FILES['images']['error'][$key] === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            if ($_FILES['images']['error'][$key] !== UPLOAD_ERR_OK) {
                $error_message = 'Có lỗi xảy ra khi tải ảnh lên. Vui lòng thử lại.';
                break;
            }

            if ($_FILES['images']['size'][$key] > $maxFileSize) {
                $error_message = 'Dung lượng ảnh vượt quá giới hạn (tối đa 10MB).';
                break;
            }

            $mime = mime_content_type($tmp_name);
            if (!in_array($mime, $allowedMimes)) {
                $error_message = 'Định dạng file không được hỗ trợ. Vui lòng chọn file ảnh (JPG, PNG, GIF, WEBP).';
                break;
            }

            $files[] = new CURLFile($tmp_name, $mime, $_FILES['images']['name'][$key]);
        }
    }

    // Nếu đạt validate, chuẩn bị dữ liệu gửi API
    if (empty($error_message)) {
        // Gom dữ liệu local_guide_review theo đúng định dạng chuẩn
        $localGuideReviewLines = [];
        $localGuideReviewLines[] = "[Đánh giá chung HDV địa phương]: " . ($local_guide_general_review !== '' ? $local_guide_general_review : '');
        $localGuideReviewLines[] = "--- ĐÁNH GIÁ THUYẾT MINH CÁC ĐIỂM THAM QUAN ---";

        $i = 1;
        if (is_array($attraction_reviews)) {
            foreach ($attraction_reviews as $attName => $attReview) {
                $trimmedReview = trim($attReview ?? '');
                $localGuideReviewLines[] = $i . ". " . $attName . ": " . $trimmedReview;
                $i++;
            }
        }
        $local_guide_review = implode("\n", $localGuideReviewLines);

        $payload = [
            'guest_name' => $guest_name,
            'guest_phone' => $guest_phone,
            'review_content' => $review_content,
            'tour_name' => $tour_name,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'guide_name' => $guide_name,
            'local_guide_name' => $local_guide_name,
            'local_guide_review' => $local_guide_review,
            'tour_review' => $tour_review,
        ];

        if (!empty($files)) {
            $payload['images'] = $files;
        }

        $endpoint = APICaller::getReviewQrEndpoint();
        $response = APICaller::postMultipart($endpoint, $payload, [
            'Content-Type: multipart/form-data',
        ]);

        if (isset($response['status']) && $response['status'] === 'success') {
            echo "<script>
                window.location.href='thankyou-trungquoc.php';
            </script>";
            exit;
        } else {
            $error_message = !empty($response['message']) ? $response['message'] : ($response['error'] ?? 'Có lỗi xảy ra khi gửi đánh giá. Vui lòng thử lại.');
        }
    }
}

// Lấy danh sách điểm tham quan theo query/post
$attractions = get_attractions_for_tour($template_id, $product_group, $masterDataFallback, $templateGroupMap);
?>
<link rel="stylesheet" href="../assets/css/review-trungquoc.css?v=<?php echo $version; ?>">
<div class="review-container">
    <?php if ($is_zalo): ?>
    <div class="help-container">
        <div class="help-tooltip">
            Bấm vào nút ... để mở trình duyệt khác
        </div>
        <div class="help-icon">
            <i>&uarr;</i>
        </div>
    </div>
    <?php endif; ?>

    <h2>Viết đánh giá chất lượng tour Trung Quốc</h2>

    <?php if (!empty($error_message)): ?>
        <p style="color: #dc3545; font-weight: 600; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px 14px; border-radius: 6px;">
            Lỗi: <?php echo htmlspecialchars($error_message); ?>
        </p>
    <?php endif; ?>

    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="template_id" value="<?php echo htmlspecialchars($template_id); ?>">
        <input type="hidden" name="product_group" value="<?php echo htmlspecialchars($product_group); ?>">

        <!-- Thông tin tour (Readonly) -->
        <div class="form-group">
            <label for="tour-name">Tên tour</label>
            <input type="text" id="tour-name" name="tour_name" value="<?php echo htmlspecialchars($tour_name); ?>" readonly>
        </div>

        <div class="form-group">
            <label for="start-date">Ngày khởi hành</label>
            <input type="text" id="start-date" name="start_date" value="<?php echo htmlspecialchars($start_date_display); ?>" readonly>
        </div>

        <div class="form-group">
            <label for="end-date">Ngày về</label>
            <input type="text" id="end-date" name="end_date" value="<?php echo htmlspecialchars($end_date_display); ?>" readonly>
        </div>

        <div class="review-section-divider"></div>

        <!-- Trưởng đoàn Việt Nam -->
        <div class="form-group">
            <label for="guide-name">Trưởng đoàn từ Việt Nam</label>
            <input type="text" id="guide-name" name="guide_name" value="<?php echo htmlspecialchars($guide_name); ?>" readonly>
        </div>

        <div class="form-group">
            <label for="review-content">Đánh giá Trưởng đoàn từ Việt Nam <span style="color: red;">*</span></label>
            <div class="review-suggestions">
                <strong>Gợi ý:</strong><br>
                - HDV tại VN có hỗ trợ mình checkin tốt không?<br>
                - HDV tại VN có hướng dẫn dặn dò mình khi làm thủ tục xuất nhập cảnh không?<br>
                - HDV tại VN có hỗ trợ đoàn trên đường tour không?
            </div>
            <textarea id="review-content" name="review_content" placeholder="Nhập đánh giá về trưởng đoàn từ Việt Nam..." required <?php if ($is_zalo) echo 'readonly'; ?>><?php echo htmlspecialchars($review_content); ?></textarea>
        </div>

        <div class="review-section-divider"></div>

        <!-- Hướng dẫn viên địa phương -->
        <div class="form-group">
            <label for="local-guide-name">Hướng dẫn viên địa phương</label>
            <small>Nếu tour có hướng dẫn viên địa phương, vui lòng nhập tên tại đây.</small>
            <input type="text" id="local-guide-name" name="local_guide_name" placeholder="Nhập tên hướng dẫn viên địa phương nếu có" value="<?php echo htmlspecialchars($local_guide_name); ?>" <?php if ($is_zalo) echo 'readonly'; ?>>
        </div>

        <div class="form-group">
            <label for="local-guide-general-review">Đánh giá chung Hướng dẫn viên địa phương</label>
            <div class="review-suggestions">
                <strong>Gợi ý:</strong><br>
                - HDV địa phương có nói Tiếng Việt tốt không?<br>
                - Có thuyết minh khi đến các điểm tham quan không?<br>
                - Có hỗ trợ đoàn nhiệt tình, đúng giờ không?
            </div>
            <textarea id="local-guide-general-review" name="local_guide_general_review" placeholder="Nhập nhận xét chung về hướng dẫn viên địa phương..." <?php if ($is_zalo) echo 'readonly'; ?>><?php echo htmlspecialchars($local_guide_general_review); ?></textarea>
        </div>

        <!-- Danh sách Đánh giá Thuyết minh từng Điểm tham quan -->
        <div class="attraction-section">
            <h3 style="color: #1e293b; font-size: 18px; margin: 24px 0 12px 0;">Đánh giá thuyết minh theo từng điểm tham quan</h3>
            <?php if (!empty($attractions) && is_array($attractions)): ?>
                <?php foreach ($attractions as $idx => $att): ?>
                    <div class="attraction-card">
                        <div class="attraction-header">
                            <span class="attraction-badge">Điểm <?php echo ($idx + 1); ?></span>
                        </div>
                        <h4 class="attraction-title"><?php echo htmlspecialchars($att['attractionName']); ?></h4>
                        <?php if (!empty($att['questionPrompt'])): ?>
                            <div class="attraction-prompt">
                                <strong>Gợi ý:</strong> <?php echo htmlspecialchars($att['questionPrompt']); ?>
                            </div>
                        <?php endif; ?>
                        <div class="form-group" style="margin-bottom: 0;">
                            <textarea name="attraction_reviews[<?php echo htmlspecialchars($att['attractionName']); ?>]" placeholder="Nhận xét phần thuyết minh của HDV tại điểm này..." <?php if ($is_zalo) echo 'readonly'; ?>><?php echo htmlspecialchars($attraction_reviews[$att['attractionName']] ?? ''); ?></textarea>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="review-section-divider"></div>

        <!-- Đánh giá Tour chung -->
        <div class="form-group">
            <label for="tour-review">Đánh giá chung dịch vụ tour</label>
            <div class="review-suggestions">
                <strong>Gợi ý:</strong><br>
                - Món ăn tại địa phương có phù hợp với mình không?<br>
                - Thời gian di chuyển có phù hợp không?<br>
                - Khách sạn có phù hợp với nhu cầu của mình không?
            </div>
            <textarea id="tour-review" name="tour_review" placeholder="Nhập đánh giá về chương trình tour, điểm tham quan, khách sạn, ẩm thực..." <?php if ($is_zalo) echo 'readonly'; ?>><?php echo htmlspecialchars($tour_review); ?></textarea>
        </div>

        <div class="review-section-divider"></div>

        <!-- Thông tin khách hàng -->
        <div class="form-group">
            <label for="guest-name">Tên khách hàng <span style="color: red;">*</span></label>
            <input type="text" id="guest-name" name="guest_name" placeholder="Nhập tên khách hàng" value="<?php echo htmlspecialchars($guest_name); ?>" required <?php if ($is_zalo) echo 'readonly'; ?>>
        </div>

        <div class="form-group">
            <label for="guest-phone">Số điện thoại (có sử dụng Zalo) <span style="color: red;">*</span></label>
            <input type="text" id="guest-phone" name="guest_phone" placeholder="Nhập số điện thoại (9 - 11 chữ số)" value="<?php echo htmlspecialchars($guest_phone); ?>" required <?php if ($is_zalo) echo 'readonly'; ?>>
        </div>

        <div class="form-group">
            <label for="images">Đăng ảnh thực tế (không bắt buộc)</label>
            <input type="file" id="images" name="images[]" accept="image/*">
            <small>Chỉ được tối đa 1 ảnh (định dạng JPG, PNG, GIF, WEBP; dung lượng &lt; 10MB)</small>
        </div>

        <button type="submit" class="submit-button" <?php if ($is_zalo) echo 'disabled'; ?>>Gửi đánh giá</button>
    </form>
    <div class="spacer"></div>
</div>
<?php include '../includes/navbar.php'; ?>
<?php include '../includes/footer.php'; ?>
