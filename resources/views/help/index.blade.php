@extends('layouts.app')

@section('title', 'Hướng dẫn sử dụng')

@section('styles')
@vite(['resources/css/help.css'])
@endsection

@section('content')
<div class="help-container">
    <!-- Header -->
    <div class="help-header">
        <div class="help-header-content">
            <i class="ri-question-line"></i>
            <h1>Hướng dẫn sử dụng</h1>
            <p>Tìm hiểu cách sử dụng các tính năng quản lý tài chính</p>
        </div>
    </div>

    <!-- Quick Start -->
    <div class="help-section">
        <h2><i class="ri-rocket-line"></i> Bắt đầu nhanh</h2>
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-number">1</div>
                <div class="step-icon"><i class="ri-wallet-3-line"></i></div>
                <h3>Tạo ví</h3>
                <p>Vào menu <strong>Ví của tôi</strong> và tạo ví đầu tiên để bắt đầu quản lý tiền.</p>
            </div>
            <div class="step-card">
                <div class="step-number">2</div>
                <div class="step-icon"><i class="ri-folder-3-line"></i></div>
                <h3>Thêm danh mục</h3>
                <p>Tùy chỉnh <strong>Danh mục</strong> thu chi theo nhu cầu của bạn (Ăn uống, Di chuyển...).</p>
            </div>
            <div class="step-card">
                <div class="step-number">3</div>
                <div class="step-icon"><i class="ri-exchange-dollar-line"></i></div>
                <h3>Ghi giao dịch</h3>
                <p>Thêm các khoản thu/chi vào <strong>Giao dịch</strong> để theo dõi dòng tiền.</p>
            </div>
            <div class="step-card">
                <div class="step-number">4</div>
                <div class="step-icon"><i class="ri-pie-chart-2-line"></i></div>
                <h3>Đặt ngân sách</h3>
                <p>Thiết lập <strong>Ngân sách</strong> cho từng danh mục để kiểm soát chi tiêu.</p>
            </div>
        </div>
    </div>

    <!-- Features Guide -->
    <div class="help-section">
        <h2><i class="ri-book-open-line"></i> Hướng dẫn chi tiết</h2>
        
        <div class="guide-grid">
            <!-- Dashboard -->
            <div class="guide-card">
                <div class="guide-icon dashboard-color">
                    <i class="ri-dashboard-3-line"></i>
                </div>
                <h3>Tổng quan</h3>
                <ul>
                    <li>Xem tổng số dư từ tất cả các ví</li>
                    <li>Biểu đồ thu chi theo tháng</li>
                    <li>Thống kê chi tiêu theo danh mục</li>
                    <li>Giao dịch gần đây</li>
                </ul>
            </div>

            <!-- Wallets -->
            <div class="guide-card">
                <div class="guide-icon wallet-color">
                    <i class="ri-wallet-3-line"></i>
                </div>
                <h3>Ví của tôi</h3>
                <ul>
                    <li><strong>Tạo ví:</strong> Tiền mặt, Ngân hàng, Thẻ tín dụng...</li>
                    <li><strong>Quản lý số dư:</strong> Theo dõi từng ví riêng biệt</li>
                    <li><strong>Chuyển tiền:</strong> Giữa các ví của bạn</li>
                    <li><strong>Tùy chỉnh:</strong> Icon và màu sắc cho ví</li>
                </ul>
            </div>

            <!-- Transactions -->
            <div class="guide-card">
                <div class="guide-icon transaction-color">
                    <i class="ri-exchange-dollar-line"></i>
                </div>
                <h3>Giao dịch</h3>
                <ul>
                    <li><strong>Thu nhập:</strong> Ghi lại các khoản thu (Lương, Thưởng...)</li>
                    <li><strong>Chi tiêu:</strong> Ghi lại các khoản chi (Ăn, Đi lại...)</li>
                    <li><strong>Chuyển khoản:</strong> Chuyển tiền giữa các ví</li>
                    <li><strong>Lọc & Tìm kiếm:</strong> Theo ngày, loại, danh mục</li>
                </ul>
            </div>

            <!-- Budgets -->
            <div class="guide-card">
                <div class="guide-icon budget-color">
                    <i class="ri-pie-chart-2-line"></i>
                </div>
                <h3>Ngân sách</h3>
                <ul>
                    <li><strong>Đặt giới hạn:</strong> Cho từng danh mục theo tháng</li>
                    <li><strong>Theo dõi:</strong> Xem % đã chi so với giới hạn</li>
                    <li><strong>Cảnh báo:</strong> Nhận thông báo khi vượt ngân sách</li>
                    <li><strong>Phân tích:</strong> Đánh giá thói quen chi tiêu</li>
                </ul>
            </div>

            <!-- Recurring -->
            <div class="guide-card">
                <div class="guide-icon recurring-color">
                    <i class="ri-loop-right-line"></i>
                </div>
                <h3>Định kỳ</h3>
                <ul>
                    <li><strong>Giao dịch lặp:</strong> Tiền nhà, điện nước, lương...</li>
                    <li><strong>Tần suất:</strong> Hàng ngày, tuần, tháng, năm</li>
                    <li><strong>Tự động hóa:</strong> Hệ thống tự tạo giao dịch</li>
                    <li><strong>Bật/Tắt:</strong> Kiểm soát trạng thái dễ dàng</li>
                </ul>
            </div>

            <!-- Calendar -->
            <div class="guide-card">
                <div class="guide-icon calendar-color">
                    <i class="ri-calendar-line"></i>
                </div>
                <h3>Lịch</h3>
                <ul>
                    <li><strong>Xem theo ngày:</strong> Giao dịch trên lịch</li>
                    <li><strong>Màu sắc:</strong> Thu (xanh), Chi (đỏ)</li>
                    <li><strong>Chi tiết:</strong> Click vào ngày để xem</li>
                    <li><strong>Tổng quan:</strong> Thu chi trong tháng</li>
                </ul>
            </div>

            <!-- Categories -->
            <div class="guide-card">
                <div class="guide-icon category-color">
                    <i class="ri-folder-3-line"></i>
                </div>
                <h3>Danh mục</h3>
                <ul>
                    <li><strong>Tùy chỉnh:</strong> Tạo danh mục riêng của bạn</li>
                    <li><strong>Icon & Màu:</strong> Chọn biểu tượng và màu sắc</li>
                    <li><strong>Phân loại:</strong> Thu nhập hoặc Chi tiêu</li>
                    <li><strong>Quản lý:</strong> Sửa/Xóa danh mục dễ dàng</li>
                </ul>
            </div>

            <!-- Profile -->
            <div class="guide-card">
                <div class="guide-icon profile-color">
                    <i class="ri-user-line"></i>
                </div>
                <h3>Hồ sơ</h3>
                <ul>
                    <li><strong>Thông tin:</strong> Cập nhật tên, email, avatar</li>
                    <li><strong>Bảo mật:</strong> Đổi mật khẩu định kỳ</li>
                    <li><strong>Xác thực:</strong> Bảo vệ tài khoản của bạn</li>
                    <li><strong>Cài đặt:</strong> Tùy chỉnh trải nghiệm</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Tips -->
    <div class="help-section">
        <h2><i class="ri-lightbulb-line"></i> Mẹo hữu ích</h2>
        <div class="tips-grid">
            <div class="tip-card">
                <i class="ri-time-line"></i>
                <h4>Ghi chép hàng ngày</h4>
                <p>Hãy ghi lại giao dịch ngay khi phát sinh để không quên.</p>
            </div>
            <div class="tip-card">
                <i class="ri-alarm-warning-line"></i>
                <h4>Đặt ngân sách thực tế</h4>
                <p>Ngân sách nên dựa trên thu nhập và nhu cầu thực tế của bạn.</p>
            </div>
            <div class="tip-card">
                <i class="ri-repeat-line"></i>
                <h4>Dùng giao dịch định kỳ</h4>
                <p>Cho các khoản cố định như tiền nhà, điện nước để tiết kiệm thời gian.</p>
            </div>
            <div class="tip-card">
                <i class="ri-bar-chart-line"></i>
                <h4>Xem báo cáo thường xuyên</h4>
                <p>Kiểm tra dashboard để hiểu rõ thói quen chi tiêu của mình.</p>
            </div>
        </div>
    </div>

    <!-- FAQ -->
    <div class="help-section">
        <h2><i class="ri-questionnaire-line"></i> Câu hỏi thường gặp</h2>
        <div class="faq-list">
            <div class="faq-item">
                <h4>Làm sao để tạo giao dịch chuyển tiền giữa các ví?</h4>
                <p>Vào menu <strong>Giao dịch</strong> → Nhấn <strong>Thêm giao dịch</strong> → Chọn loại <strong>Chuyển khoản</strong> → Chọn ví nguồn và ví đích.</p>
            </div>
            <div class="faq-item">
                <h4>Tại sao tôi nhận được cảnh báo ngân sách?</h4>
                <p>Khi tổng chi tiêu trong danh mục vượt quá 90% hoặc 100% ngân sách đã đặt, hệ thống sẽ cảnh báo để bạn kiểm soát chi tiêu.</p>
            </div>
            <div class="faq-item">
                <h4>Giao dịch định kỳ hoạt động như thế nào?</h4>
                <p>Hệ thống tự động kiểm tra và tạo giao dịch mới theo lịch bạn đã đặt (hàng ngày/tuần/tháng). Bạn có thể bật/tắt bất kỳ lúc nào.</p>
            </div>
            <div class="faq-item">
                <h4>Dữ liệu của tôi có an toàn không?</h4>
                <p>Có! Dữ liệu được mã hóa, mật khẩu được hash bằng Bcrypt, và chỉ bạn mới truy cập được tài khoản của mình.</p>
            </div>
            <div class="faq-item">
                <h4>Tôi có thể xuất dữ liệu không?</h4>
                <p>Hiện tại bạn có thể xem lịch sử giao dịch và in báo cáo. Tính năng xuất Excel/CSV sẽ được bổ sung trong tương lai.</p>
            </div>
        </div>
    </div>

    <!-- Support -->
    <div class="help-section support-section">
        <div class="support-card">
            <i class="ri-customer-service-line"></i>
            <h3>Cần thêm hỗ trợ?</h3>
            <p>Nếu bạn gặp vấn đề hoặc có câu hỏi, đừng ngại liên hệ với chúng tôi.</p>
            <div class="support-actions">
                <a href="mailto:support@quanlychitieu.com" class="btn btn-primary">
                    <i class="ri-mail-line"></i> Email hỗ trợ
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-outline">
                    <i class="ri-arrow-left-line"></i> Quay lại Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
