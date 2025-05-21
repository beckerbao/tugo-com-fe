<?php
include '../helpers/common.php';
include '../helpers/apiCaller.php';
function fetch_api($url) {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $res = curl_exec($ch);
  curl_close($ch);
  return json_decode($res, true);
}
APICaller::init();

$tour_id = $_GET['tour_id'] ?? null;
$campaign_id = $_GET['campaign_id'] ?? 1;
$tourDetail = null;
$departures = [];
$airline = '';
$tour_name = 'Tour du lịch';
$tour_duration = '';
$tour_image = '';
$tour_summary = '';
$tour_highlights = [];
$tour_itinerary = [];
$tour_services = [];
$tour_gallery = [];

if ($tour_id) {
  // gọi API tour-detail qua APICaller
  $response = APICaller::get('/flashsale/tour-detail', [
      'tour_id'       => $tour_id,
      'force_refresh' => 'true',
      'campaign_id'   => $campaign_id
  ]);

  if ($response['status'] === 'success') {
    $data = $response['data']['tour']['output_json']['data'];
    $tourDetail = $data;
    $departures = $response['data']['prices'] ?? [];

    $tour_name = $data['name'] ?? $tour_name;
    $tour_duration = $data['duration'] ?? '';
    $tour_image = $data['image'] ?? '';
    $tour_summary = $response['data']['tour']['summary'] ?? '';
    $tour_highlights = $data['highlights'] ?? [];
    $tour_itinerary = $data['itinerary'] ?? [];
    $tour_services = $data['whats_included'] ?? [];
    $tour_gallery = $data['photo_gallery'] ?? [];
    $collection_name = $response['data']['tour']['collection_name'] ?? '';
    $campaign_start_date = $response['data']['campaign_info']['start_time'] ?? '';
    $campaign_end_date = $response['data']['campaign_info']['end_time'] ?? '';

    //convert campaign end date to GMT+7
    $campaign_end_date = new DateTime($campaign_end_date);
    $campaign_end_date->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));
    $campaign_end_date = $campaign_end_date->format('Y-m-d H:i:s');

    //convert campaign end date to number of days hours minutes and seconds from now
    $campaign_end_date = strtotime($campaign_end_date);
    $days = floor(($campaign_end_date - time()) / (60 * 60 * 24));
    $hours = floor((($campaign_end_date - time()) % (60 * 60 * 24)) / (60 * 60));
    $minutes = floor((($campaign_end_date - time()) % (60 * 60)) / 60);
    $seconds = floor(($campaign_end_date - time()) % 60);

    $countdown = sprintf('Còn %02d ngày %02d:%02d:%02d là kết thúc', $days, $hours, $minutes, $seconds);

    //convert campaign start date to GMT+7
    $campaign_start_date = new DateTime($campaign_start_date);
    $campaign_start_date->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));
    $campaign_start_date = $campaign_start_date->format('Y-m-d H:i:s');

    //convert campaign start date to number of days hours minutes and seconds from now
    $campaign_start_date = strtotime($campaign_start_date);
    $start_days = floor(($campaign_start_date - time()) / (60 * 60 * 24));
    $start_hours = floor((($campaign_start_date - time()) % (60 * 60 * 24)) / (60 * 60));
    $start_minutes = floor((($campaign_start_date - time()) % (60 * 60)) / 60);
    $start_seconds = floor(($campaign_start_date - time()) % 60);

    $commingText = sprintf('Chương trình sẽ bắt đầu vào ngày %s', date('d-m-Y', $campaign_start_date));

    //if start date > now then the campaign has started
    $campaign_start = true;
    if ($campaign_start_date > time() || $campaign_end_date < time()) {
      $campaign_start = false;
    }

    //force campaign start by GET[force_start]
    if (isset($_GET['force_start']) && $_GET['force_start'] === 'true') {
      $campaign_start = true;
    }
    

    foreach ($tour_highlights as $hl) {
      if (strtolower($hl['title']) === 'hãng bay' || strtolower($hl['title']) === 'hãng hàng không') {
        $airline = $hl['description'];
        break;
      }
    }
  }
}

// Default departure
$departure = $departures[0] ?? [
  'departure_date' => date('Y-m-d', strtotime('+7 days')),
  'price' => 10000000,
  'available_slots' => 10
];
$departure_date = date('d/m/Y', strtotime($departure['departure_date']));
$available_slots = $departure['available_slots'];
$tour_price = $departure['price'];
$tour_price_strike = ceil($tour_price * 1.15);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($tour_name) ?> | Tugo</title>
  <!-- Open Graph Meta Tags -->
  <meta property="og:title" content="Flash Sale 5.5 - Mua Tour Giá Gốc">
  <meta property="og:description" content="Chỉ trong ngày 5.5! Đặt tour với giá gốc, số lượng có hạn.">
  <meta property="og:image" content="https://review.tugo.com.vn/assets/images/flash55.png">
  <meta property="og:url" content="https://review.tugo.com.vn/pages/flashsale_detail.php">
  <meta property="og:type" content="website">

  <!-- Twitter Card Tags (tùy chọn) -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Flash Sale 5.5 - Mua Tour Giá Gốc">
  <meta name="twitter:description" content="Chỉ trong ngày 5.5! Đặt tour với giá gốc, số lượng có hạn.">
  <meta name="twitter:image" content="https://yourdomain.com/images/flashsale-5-5.jpg">

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap"
    rel="stylesheet"
  />
  <link
    href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css"
    rel="stylesheet"
  />
  <script src="https://cdn.tailwindcss.com/3.4.16"></script>
  <link href="../assets/css/flashsale-detail.css" rel="stylesheet">
  <link href="../assets/css/flashsale-menu.css?v1" rel="stylesheet">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: "#660066",
            secondary: "#9933CC",
          },
          borderRadius: {
            button: "8px",
          },
        },
      },
    };
  </script>  
  <?php include '../includes/tracking.php'; ?>
</head>
<body class="bg-gray-50">
<script>
  window.TOUR_ID = <?php echo $tour_id ?>;
  window.API_URL = "<?php echo APICaller::getBaseUrl() ?: 'http://localhost:9090/api/v1/flashsale/booking' ?>";
  window.API_GO_URL = "<?php echo APICaller::getBaseGoUrl() ?: 'http://localhost:9090' ?>";
</script>
<style>
/* Overlay đen mờ */
#loadingOverlay {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

/* Khối nội dung loading */
#loadingContent {
    text-align: center;
    color: white;
    font-family: Arial, sans-serif;
}

#loadingIcon {
    width: 40px;
    height: 40px;
    margin-bottom: 10px;
    border: 5px solid #f3f3f3;
    border-top: 5px solid #800080;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

/* Hiệu ứng quay */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
<!-- Navbar -->
<?php include '../includes/flashsale-header.php'; ?>

<!-- Hero Banner -->
<div class="relative h-96">
  <img
    src="<?= htmlspecialchars($tour_image) ?>"
    alt="<?= htmlspecialchars($tour_name) ?>"
    class="w-full h-full object-cover object-top"
  />
  <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
  <div class="absolute top-4 left-4 sm:top-6 sm:left-6">
    <a
      href="/pages/flashsale_home.php"
      class="inline-flex items-center px-3 py-2 bg-white/20 backdrop-blur-sm text-white rounded-full hover:bg-white/30 transition"
    >
        <div class="w-4 h-4 mr-1 flex items-center justify-center">
            <i class="ri-arrow-left-line"></i>
        </div>
      <span>Quay lại</span>
    </a>
  </div>
  <div class="absolute bottom-0 left-0 w-full p-6">
    <div class="max-w-7xl mx-auto">
      <div class="flex items-center">        
        <h1 class="text-3xl font-bold text-white"><?= htmlspecialchars($tour_name) ?></h1>
      </div>
      <div class="mt-2 flex flex-wrap items-center gap-3 text-white">
        <div class="flex items-center"><i class="ri-map-pin-line mr-1"></i> <?php echo $collection_name;?></div>
        <div class="flex items-center"><i class="ri-time-line mr-1"></i> <?= $tour_duration ?></div>
        <div class="inline-flex items-center px-3 py-1 bg-primary text-white text-sm font-medium rounded-full countdown">
          <i class="ri-flashlight-line mr-1"></i>
          <?php
          if ($campaign_start) {
          ?>
            <span><?php echo $countdown; ?></span>
          <?php
          }else{
          ?>
            <!-- <span><?php echo $commingText; ?></span> -->
          <?php
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
  <div class="flex flex-col lg:flex-row gap-8">
    <!-- Cột trái -->
    <div class="w-full lg:w-2/3">
        <!-- Info Box -->
        <!-- <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8"> -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex flex-wrap gap-4 text-sm">
            <div class="flex items-center"><i class="ri-calendar-line text-primary mr-2"></i> Khởi hành: <strong><?= $departure_date ?></strong></div>
            <div class="flex items-center"><i class="ri-group-line text-primary mr-2"></i> Số chỗ còn nhận: <strong><?= $available_slots ?></strong></div>            
            </div>
            
            <div class="mt-6 flex items-center justify-between">
              <div>
                  <span class="text-gray-500 line-through text-lg"><?= number_format($tour_price_strike) ?>₫</span>
                  <div class="text-2xl font-bold text-primary"><?= number_format($tour_price) ?>₫</div>
                  <span class="text-sm text-gray-500">Giá/khách (đã bao gồm thuế VAT)</span>
                  <div class="text-sm text-gray-500">Giá chưa bao gồm TIP</div>
              </div>
              <div style="margin-top: 10px;">
                <button 
                    id="customTourBtn"
                    style="background-color: #800080; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
                    Thiết kế tour riêng
                </button>
                <!-- <p id="customTourStatus" style="margin-top: 10px; color: #555;"></p> -->
            </div>
            <!-- Overlay loading -->
            <div id="loadingOverlay">
                <div id="loadingContent">
                    <div id="loadingIcon"></div>
                    <div style="font-size: 18px; font-weight: bold;">Hệ thống đang xử lý...</div>
                </div>
            </div>            
            </div>
        </div>
        <!-- </div> -->

        <!-- Tab Navigation -->
        <div class="bg-white rounded-lg shadow-sm mb-6 sticky top-16 z-40">
            <div class="flex overflow-x-auto scrollbar-hide">
                <button data-target="tab-overview" class="tab-active flex-1 px-4 py-3 text-center font-medium border-b-2 whitespace-nowrap">Tổng quan</button>
                <button data-target="tab-booking" class="tab-active flex-1 px-4 py-3 text-center font-medium border-b-2 whitespace-nowrap">Đặt tour</button>
                <button data-target="tab-itinerary" class="flex-1 px-4 py-3 text-center font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 whitespace-nowrap">Lịch trình</button>
                <button data-target="tab-attractions" class="flex-1 px-4 py-3 text-center font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 whitespace-nowrap">Điểm tham quan</button>
                <button data-target="tab-includes" class="flex-1 px-4 py-3 text-center font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 whitespace-nowrap">Dịch vụ bao gồm</button>
            </div>
        </div>

        <!-- Tổng quan về tour -->
        <div id="tab-overview" class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Tổng quan về tour</h2>
        <?php echo $tour_summary; ?>       
        </div>
        <!-- Lịch trình chi tiết -->
        <div id="tab-itinerary" class="bg-white rounded-lg shadow-sm p-6 mb-6">                          

          <?php include_once('../includes/map.php'); ?>

        <?php foreach ($tour_itinerary as $i => $day): ?>
            <div class="relative pl-10 pb-8">
            <div class="timeline-dot relative"></div>
            <?php if ($i < count($tour_itinerary) - 1): ?>
                <div class="timeline-line relative"></div>
            <?php endif; ?>
            <h3 class="font-bold text-gray-900 mb-2"><?= htmlspecialchars($day['day'] . ': ' . $day['title']) ?></h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-gray-700"><?= nl2br(htmlspecialchars($day['description'])) ?></p>
            </div>
            </div>
        <?php endforeach; ?>
        </div>

        <!-- Điểm tham quan -->
        <div id="tab-attractions" class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Điểm nổi bật của tour</h2>
        <ul class="space-y-2 text-gray-800">
            <?php foreach ($tour_highlights as $hl): ?>
            <li class="flex items-start gap-2">
                <i class="ri-checkbox-circle-line text-[#660066] mt-1"></i>
                <span><?= htmlspecialchars($hl['description']) ?></span>
            </li>
            <?php endforeach; ?>
        </ul>
        </div>

        <!-- Thư viện hình ảnh -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Thư viện hình ảnh</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php foreach ($tour_gallery as $photo): ?>
            <div class="overflow-hidden rounded-lg shadow-sm">
                <img src="<?= htmlspecialchars($photo['image']) ?>" alt="Gallery" class="w-full h-40 object-cover gallery-img" />
            </div>
            <?php endforeach; ?>
        </div>
        </div>

        <!-- Dịch vụ bao gồm -->
        <div id="tab-includes" class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Dịch vụ bao gồm</h2>
        <ul class="space-y-2 text-gray-800">
            <?php foreach ($tour_services as $item): ?>
            <li class="flex items-start gap-2">
                <i class="ri-check-line text-green-600 mt-1"></i>
                <span><?= htmlspecialchars($item['description']) ?></span>
            </li>
            <?php endforeach; ?>
        </ul>
        </div>
    </div>
    <!-- Cột phải -->
    <div class="w-full lg:w-1/3">            
        <!-- Đặt tour ngay -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div id="tab-booking" class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Đặt tour ngay</h2>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Chọn ngày khởi hành</label>
                <div class="space-y-2">
                    <?php foreach ($departures as $i => $dep): ?>
                    <?php
                    //if available slots > 0 then show
                    if ($dep['available_slots'] > 0) {
                    ?>
                      <div class="date-option <?= $i === 0 ? 'selected' : '' ?> border border-gray-200 rounded p-3 flex justify-between items-center cursor-pointer" 
                      data-date="<?= $dep['departure_date'] ?>"
                      data-price="<?= $dep['price'] ?>"
                      >
                          <div>
                          <div class="font-medium"><?= date('d/m/Y', strtotime($dep['departure_date'])) ?></div>
                          <div class="text-sm text-gray-500">Còn <?= $dep['available_slots'] ?> chỗ</div>
                          <?php
                          //if short_title is not null then show
                          if ($dep['short_title']!='') {                            
                          ?>
                            <div class="text-xs text-red-600">(<?php echo $dep['short_title']?>)</div>
                          <?php
                          } 
                          ?>
                          </div>
                          <div class="text-primary font-bold"><?= number_format($dep['price']) ?>₫</div>
                      </div>
                    <?php
                    }
                    ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Số lượng khách</label>
                <div class="flex items-center justify-between border border-gray-200 rounded p-3 mb-2">
                  <div>
                    <div class="font-medium">Người lớn</div>
                    <div class="text-sm text-gray-500">Từ 12 tuổi trở lên</div>
                  </div>
                  <div class="flex items-center">
                    <button id="minusButton" class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-full text-gray-500 hover:bg-gray-100">
                      <div class="w-4 h-4 flex items-center justify-center">
                        <i class="ri-subtract-line"></i>
                      </div>
                    </button>
                    <input id="tour_quantity" type="number" value="1" min="1" class="w-10 text-center mx-2 border-none focus:outline-none">
                    <button id="plusButton" class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-full text-gray-500 hover:bg-gray-100">
                      <div class="w-4 h-4 flex items-center justify-center">
                        <i class="ri-add-line"></i>
                      </div>
                    </button>
                  </div>
                </div>                
              </div>
            <?php
            if ($campaign_start || 1==1){              
            ?>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1" for="contact_name">Họ và tên</label>
                <input type="text" id="contact_name" name="contact_name" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-primary" placeholder="Nhập họ tên của bạn">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1" for="contact_phone">Số điện thoại</label>
                <input type="tel" id="contact_phone" name="contact_phone" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-primary" placeholder="Nhập số điện thoại liên hệ">
            </div>
            <div class="border-t border-gray-200 pt-4 mb-4">
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Giá tour cơ bản</span>
                    <span class="font-medium" id="subtotal-text" data-base="<?= $tour_price ?>"><?= number_format($tour_price) ?>₫ x <span id="current-qty">1</span></span>
                </div>            
                <div class="flex justify-between font-bold text-lg pt-2 border-t border-gray-200">
                    <span>Tổng cộng</span>
                    <span class="text-primary" id="total-price"><?= number_format(($tour_price)) ?>₫</span>                    
                </div>
                <div class="text-sm text-gray-600 mt-1">
                    Giá chưa bao gồm TIP
                  </div>
            </div>

            <button id="bookButton" class="w-full bg-primary text-white py-3 px-4 rounded-button font-medium hover:bg-primary/90 flex justify-center items-center gap-2">
                <svg id="spinner" class="animate-spin hidden h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span id="button-text">Đặt ngay</span>
            </button>
            <p class="text-sm text-gray-500 text-center mt-2">Không mất phí khi đặt, chỉ thanh toán khi xác nhận</p>
            <?php
            }else{
            ?>
            <button id="not start" class="w-full bg-primary text-white py-3 px-4 rounded-button font-medium hover:bg-primary/90 flex justify-center items-center gap-2">
                <svg id="spinner" class="animate-spin hidden h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span id="button-text">Chương trình chưa bắt đầu</span>
            </button>
            <?php        
            }
            ?>
        </div>
        </div>
    </div>
</div>
</div>
<script src="../assets/js/flashsale.js?v3.1"></script>
<!-- Footer -->
<?php
include_once('../includes/flashsale-footer.php');
?>
</body>
</html>
