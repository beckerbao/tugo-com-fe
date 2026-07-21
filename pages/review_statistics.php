<?php
session_start();
$page_title = 'Thống kê đánh giá tour';
$body_class = 'statistics-page';
include '../helpers/common.php';
include '../includes/header.php';

$statistics_endpoint = 'https://api.review.tugo.com.vn/api/v2/statistics';
$statistics_response = null;
$statistics_error = null;

$curl = curl_init($statistics_endpoint);
curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTPHEADER => ['Accept: application/json'],
]);
$raw_response = curl_exec($curl);
$curl_error = curl_error($curl);
$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

if ($raw_response === false) {
    $statistics_error = 'Không thể kết nối tới API thống kê: ' . $curl_error;
} else {
    $statistics_response = json_decode($raw_response, true);
    if ($http_code < 200 || $http_code >= 300 || ($statistics_response['status'] ?? '') !== 'success') {
        $statistics_error = 'API thống kê trả về dữ liệu không hợp lệ (HTTP ' . $http_code . ').';
    }
}

$statistics = $statistics_response['data'] ?? [];
$reviews_by_guide = $statistics['reviews_by_guide'] ?? [];
$monthly_stats = $statistics['tours_by_month_year'] ?? [];
$tour_groups = $statistics['grouped_by_tour_date_guide'] ?? [];
$top_guide = $reviews_by_guide[0] ?? ['guide_name' => 'Chưa có dữ liệu', 'review_count' => 0];
$peak_month = ['year' => null, 'month' => null, 'review_count' => 0];
foreach ($monthly_stats as $month_stat) {
    if ((int) ($month_stat['review_count'] ?? 0) > (int) $peak_month['review_count']) $peak_month = $month_stat;
}
$json_flags = JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
<link rel="stylesheet" href="../assets/css/review-statistics.css?v=<?php echo htmlspecialchars(get_version()); ?>">

<main class="statistics-shell">
    <section class="statistics-hero">
        <div class="statistics-kicker">GoReview / Intelligence</div>
        <h1>Toàn cảnh đánh giá tour</h1>
        <p>Theo dõi sức khoẻ review, hiệu suất HDV và những đoàn tour được yêu thích nhất.</p>
        <button class="refresh-button" type="button" onclick="window.location.reload()">↻ Làm mới dữ liệu</button>
    </section>

    <?php if ($statistics_error): ?>
        <div class="api-error" role="alert"><?php echo htmlspecialchars($statistics_error); ?></div>
    <?php else: ?>
        <section class="kpi-grid" aria-label="Các chỉ số chính">
            <article class="kpi-card"><div class="kpi-label">Tổng số review</div><div class="kpi-value"><?php echo number_format((int) ($statistics['total_reviews'] ?? 0)); ?></div><div class="kpi-meta">Tất cả đánh giá đã ghi nhận</div></article>
            <article class="kpi-card"><div class="kpi-label">Tour độc lập</div><div class="kpi-value"><?php echo number_format((int) ($statistics['total_unique_tours'] ?? 0)); ?></div><div class="kpi-meta">Có phát sinh review</div></article>
            <article class="kpi-card"><div class="kpi-label">HDV dẫn đầu</div><div class="kpi-value"><?php echo number_format((int) $top_guide['review_count']); ?></div><div class="kpi-meta"><?php echo htmlspecialchars($top_guide['guide_name']); ?></div></article>
            <article class="kpi-card"><div class="kpi-label">Tháng cao điểm</div><div class="kpi-value"><?php echo $peak_month['month'] ? sprintf('%02d/%d', $peak_month['month'], $peak_month['year']) : '--'; ?></div><div class="kpi-meta"><?php echo number_format((int) $peak_month['review_count']); ?> review</div></article>
        </section>

        <section class="dashboard-grid">
            <article class="dashboard-card wide"><div class="card-heading"><div><h2>Xu hướng theo tháng</h2><p>Số tour và review theo thời gian</p></div></div><div class="chart-wrap"><canvas id="monthlyChart"></canvas></div></article>
            <article class="dashboard-card"><div class="card-heading"><div><h2>Top 10 HDV</h2><p>Xếp hạng theo số bài review</p></div></div><div class="chart-wrap guide-chart"><canvas id="guideChart"></canvas></div></article>
            <article class="dashboard-card"><div class="card-heading"><div><h2>Danh sách HDV</h2><p>Tìm kiếm và phân trang nhanh</p></div></div><div class="table-wrap"><table id="guideTable" class="statistics-table"><thead><tr><th>#</th><th>Hướng dẫn viên</th><th>Review</th></tr></thead><tbody><?php foreach ($reviews_by_guide as $index => $guide): ?><tr><td><?php echo $index + 1; ?></td><td><?php echo htmlspecialchars($guide['guide_name'] ?? 'Chưa xác định'); ?></td><td class="review-count"><?php echo number_format((int) ($guide['review_count'] ?? 0)); ?></td></tr><?php endforeach; ?></tbody></table></div></article>
            <article class="dashboard-card wide"><div class="card-heading"><div><h2>Đoàn tour chi tiết</h2><p>Tra cứu theo tên tour, HDV hoặc tháng khởi hành</p></div><input id="tourMonthFilter" class="date-filter" type="month" aria-label="Lọc theo tháng khởi hành"></div><div class="table-wrap"><table id="tourGroupTable" class="statistics-table"><thead><tr><th>STT</th><th>Tên tour</th><th>Ngày khởi hành</th><th>Hướng dẫn viên</th><th>Review</th></tr></thead><tbody><?php foreach ($tour_groups as $index => $tour): ?><tr><td><?php echo $index + 1; ?></td><td><?php echo ($tour['tour_name'] ?? '') !== '' ? htmlspecialchars($tour['tour_name']) : '<span class="badge bg-secondary">Chưa xác định</span>'; ?></td><td data-order="<?php echo htmlspecialchars($tour['start_date'] ?? ''); ?>" data-search="<?php echo htmlspecialchars($tour['start_date'] ?? ''); ?>"><?php echo !empty($tour['start_date']) ? date('d/m/Y', strtotime($tour['start_date'])) : '<span class="badge bg-secondary">--</span>'; ?></td><td><?php echo ($tour['guide_name'] ?? '') !== '' ? htmlspecialchars($tour['guide_name']) : '<span class="badge bg-light text-dark">Chưa phân công</span>'; ?></td><td class="review-count"><?php echo number_format((int) ($tour['post_count'] ?? 0)); ?></td></tr><?php endforeach; ?></tbody></table></div></article>
        </section>
    <?php endif; ?>
</main>
<?php include '../includes/navbar.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script>
const monthlyStats = <?php echo json_encode(array_reverse($monthly_stats), $json_flags); ?>;
const guideStats = <?php echo json_encode(array_slice($reviews_by_guide, 0, 10), $json_flags); ?>;
if (monthlyStats.length) new Chart(document.getElementById('monthlyChart'), { data: { labels: monthlyStats.map(item => `${String(item.month).padStart(2, '0')}/${item.year}`), datasets: [{ type: 'bar', label: 'Số tour', data: monthlyStats.map(item => item.tour_count), backgroundColor: '#d9c2e2', borderRadius: 5, yAxisID: 'y' }, { type: 'line', label: 'Số review', data: monthlyStats.map(item => item.review_count), borderColor: '#12a889', backgroundColor: '#12a889', tension: .35, pointRadius: 3, yAxisID: 'y1' }] }, options: { responsive: true, maintainAspectRatio: false, interaction: { mode: 'index', intersect: false }, scales: { y: { beginAtZero: true, grid: { color: '#eef1f2' } }, y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false } } }, plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } } } } });
if (guideStats.length) new Chart(document.getElementById('guideChart'), { type: 'bar', data: { labels: guideStats.map(item => item.guide_name), datasets: [{ label: 'Review', data: guideStats.map(item => item.review_count), backgroundColor: ['#6b2f80', '#805092', '#956da4', '#aa89b6', '#bfa6c7', '#cdb7d4', '#dac7df', '#e0d0e5', '#e6d9e9', '#ece2ee'], borderRadius: 5 }] }, options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true, grid: { color: '#eef1f2' } }, y: { grid: { display: false }, ticks: { autoSkip: false, font: { size: 11 } } } } } });
new DataTable('#guideTable', { pageLength: 8, lengthChange: false, language: { search: 'Tìm:', info: '_START_–_END_ / _TOTAL_', paginate: { previous: '‹', next: '›' } } });
const tourTable = new DataTable('#tourGroupTable', { pageLength: 10, language: { search: 'Tìm:', lengthMenu: 'Hiển thị _MENU_', info: '_START_–_END_ / _TOTAL_', paginate: { previous: '‹', next: '›' }, emptyTable: 'Chưa có dữ liệu' } });
document.getElementById('tourMonthFilter')?.addEventListener('change', event => { const value = event.target.value; tourTable.column(2).search(value ? `^${value}` : '', true, false).draw(); });
</script>
<?php include '../includes/footer.php'; ?>
