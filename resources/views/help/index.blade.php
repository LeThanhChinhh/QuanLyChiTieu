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
        <div class="steps-grid-compact">
            <div class="step-card-compact">
                <div class="step-number">1</div>
                <h3>Tạo ví</h3>
                <p>Vào <strong>Ví của tôi</strong> để tạo ví và quản lý tiền cá nhân.</p>
            </div>
            <div class="step-card-compact">
                <div class="step-number">2</div>
                <h3>Ghi giao dịch</h3>
                <p>Thêm các khoản thu/chi vào <strong>Giao dịch</strong> để theo dõi dòng tiền.</p>
            </div>
            <div class="step-card-compact">
                <div class="step-number">3</div>
                <h3>Đặt ngân sách</h3>
                <p>Thiết lập <strong>Ngân sách</strong> cho từng danh mục để kiểm soát chi tiêu.</p>
            </div>
            <div class="step-card-compact">
                <div class="step-number">4</div>
                <h3>Tạo nhóm chi tiêu</h3>
                <p>Vào <strong>Nhóm chi tiêu</strong> để chia bill với bạn bè, người thân.</p>
            </div>
        </div>
    </div>

    <!-- Features Guide -->
    <div class="help-section">
        <h2><i class="ri-book-open-line"></i> Hướng dẫn chi tiết</h2>
        
        <div class="guide-grid-compact">
            <!-- Dashboard -->
            <div class="guide-card-compact">
                <div class="guide-icon-compact dashboard-color">
                    <i class="ri-dashboard-3-line"></i>
                </div>
                <h3>Tổng quan</h3>
                <p>Xem tổng số dư, biểu đồ thu chi theo tháng, thống kê chi tiêu theo danh mục và giao dịch gần đây.</p>
            </div>

            <!-- Wallets -->
            <div class="guide-card-compact">
                <div class="guide-icon-compact wallet-color">
                    <i class="ri-wallet-3-line"></i>
                </div>
                <h3>Ví của tôi</h3>
                <p>Tạo và quản lý nhiều ví (Tiền mặt, Ngân hàng, Thẻ tín dụng). Chuyển tiền giữa các ví, tùy chỉnh icon và màu sắc.</p>
            </div>

            <!-- Transactions -->
            <div class="guide-card-compact">
                <div class="guide-icon-compact transaction-color">
                    <i class="ri-exchange-dollar-line"></i>
                </div>
                <h3>Giao dịch</h3>
                <p>Ghi lại thu nhập, chi tiêu và chuyển khoản. Lọc và tìm kiếm theo ngày, loại giao dịch, danh mục.</p>
            </div>

            <!-- Budgets -->
            <div class="guide-card-compact">
                <div class="guide-icon-compact budget-color">
                    <i class="ri-pie-chart-2-line"></i>
                </div>
                <h3>Ngân sách</h3>
                <p>Đặt giới hạn chi tiêu cho từng danh mục theo tháng. Nhận cảnh báo khi vượt 90% hoặc 100% ngân sách.</p>
            </div>

            <!-- Recurring -->
            <div class="guide-card-compact">
                <div class="guide-icon-compact recurring-color">
                    <i class="ri-loop-right-line"></i>
                </div>
                <h3>Định kỳ</h3>
                <p>Tạo giao dịch lặp lại tự động (tiền nhà, lương, điện nước). Chọn tần suất: hàng ngày, tuần, tháng hoặc năm.</p>
            </div>

            <!-- Calendar -->
            <div class="guide-card-compact">
                <div class="guide-icon-compact calendar-color">
                    <i class="ri-calendar-line"></i>
                </div>
                <h3>Lịch</h3>
                <p>Xem giao dịch theo lịch với màu sắc phân biệt: thu (xanh), chi (đỏ). Click vào ngày để xem chi tiết.</p>
            </div>

            <!-- Group Expenses -->
            <div class="guide-card-compact">
                <div class="guide-icon-compact group-color">
                    <i class="ri-group-line"></i>
                </div>
                <h3>Nhóm chi tiêu</h3>
                <p>Chia bill với bạn bè, gia đình. Thêm thành viên, ghi chi tiêu chung, tính toán ai nợ ai bao nhiêu, thanh toán số dư dễ dàng.</p>
            </div>

            <!-- Categories -->
            <div class="guide-card-compact">
                <div class="guide-icon-compact category-color">
                    <i class="ri-folder-3-line"></i>
                </div>
                <h3>Danh mục</h3>
                <p>Tạo danh mục tùy chỉnh cho thu nhập và chi tiêu. Chọn icon, màu sắc phù hợp với nhu cầu của bạn.</p>
            </div>
        </div>
    </div>

    <!-- Group Expenses Detailed Guide -->
    <div class="help-section">
        <h2><i class="ri-group-line"></i> Hướng dẫn Nhóm Chi Tiêu</h2>
        <div class="group-guide-section">
            <div class="group-guide-intro">
                <p>Tính năng <strong>Nhóm Chi Tiêu</strong> giúp bạn dễ dàng quản lý chi tiêu chung với bạn bè, gia đình khi đi du lịch, ăn nhóm, hoặc ở ghép. Hệ thống tự động tính toán ai nợ ai và đưa ra gợi ý thanh toán tối ưu.</p>
            </div>

            <!-- IMPORTANT NOTE -->
            <div class="group-important-note">
                <div class="important-icon">
                    <i class="ri-error-warning-line"></i>
                </div>
                <div class="important-content">
                    <h4>⚠️ Quan trọng: Nhóm chi tiêu KHÔNG tự động ghi vào ví cá nhân</h4>
                    <p><strong>Nhóm chi tiêu</strong> và <strong>Ví cá nhân</strong> là 2 hệ thống riêng biệt, KHÔNG tự động đồng bộ với nhau:</p>
                    <ul>
                        <li><strong>Nhóm chi tiêu:</strong> Chỉ theo dõi "ai nợ ai bao nhiêu" trong nhóm, KHÔNG liên quan đến số dư ví thật của bạn.</li>
                        <li><strong>Ví cá nhân:</strong> Quản lý tiền thật trong ví của BẠN (Tiền mặt, Ngân hàng, Thẻ...).</li>
                    </ul>
                    <p class="example-text"><strong>Ví dụ:</strong> Bạn có ví "Tiền mặt" 5.000.000đ. Trong nhóm, bạn trả 1.000.000đ tiền nhà hàng → Ví "Tiền mặt" vẫn là 5.000.000đ (không tự động trừ). Bạn cần <strong>TỰ GHI</strong> giao dịch chi 1tr vào ví nếu muốn theo dõi số dư thực.</p>
                    <p class="tip-text"><strong>💡 Mẹo:</strong> Sau khi thêm chi tiêu vào nhóm, hãy vào menu <strong>Giao dịch</strong> → <strong>Thêm giao dịch</strong> để ghi lại khoản chi từ ví cá nhân, ghi chú "Tiền nhà hàng (nhóm Du lịch)" để dễ nhớ.</p>
                </div>
            </div>

            <div class="group-steps">
                <div class="group-step">
                    <div class="group-step-header">
                        <span class="group-step-number">1</span>
                        <h4>Tạo nhóm và thêm thành viên</h4>
                    </div>
                    <p>Vào menu <strong>Nhóm chi tiêu</strong> → Click <strong>Tạo nhóm mới</strong> → Đặt tên (VD: "Du lịch Đà Nẵng"), chọn icon và màu sắc. Sau đó vào tab <strong>Thành viên</strong> để thêm bạn bè qua email.</p>
                </div>

                <div class="group-step">
                    <div class="group-step-header">
                        <span class="group-step-number">2</span>
                        <h4>Thêm chi tiêu và chia bill</h4>
                    </div>
                    <p>Click <strong>Thêm Chi Tiêu</strong> → Chọn <strong>Người trả</strong> tiền (ai thực sự đã trả tiền) → Nhập số tiền và mô tả. Chọn cách chia: <strong>Chia đều</strong> (mỗi người trả bằng nhau), <strong>Phần trăm</strong> (theo tỷ lệ %), <strong>Tùy chỉnh</strong> (mỗi người số tiền khác nhau), hoặc <strong>Tỷ lệ</strong> (theo phần share). <span class="highlight-text">Lưu ý: Thao tác này CHỈ ghi vào nhóm, KHÔNG trừ tiền từ ví.</span></p>
                </div>

                <div class="group-step">
                    <div class="group-step-header">
                        <span class="group-step-number">3</span>
                        <h4>Xem số dư và ai nợ ai</h4>
                    </div>
                    <p>Vào tab <strong>Số dư</strong> để xem số dư của từng người. Người có số dư dương (màu xanh) là người được nợ (đã advance tiền cho nhóm), số dư âm (màu đỏ) là người đang nợ (chưa trả đủ phần của mình). Phần <strong>Gợi ý thanh toán</strong> sẽ hiện cách thanh toán tối ưu nhất (ít giao dịch nhất).</p>
                </div>

                <div class="group-step">
                    <div class="group-step-header">
                        <span class="group-step-number">4</span>
                        <h4>Ghi nhận thanh toán</h4>
                    </div>
                    <p>Khi ai đó đã chuyển tiền thật (qua chuyển khoản, tiền mặt...), click nút <strong>Ghi nhận thanh toán</strong> ở gợi ý (hoặc vào tab Số dư). Nhập số tiền và ghi chú (VD: "Chuyển khoản tiền nhà"). Số dư trong nhóm sẽ tự động cập nhật về 0 khi đã thanh toán hết. <span class="highlight-text">Lưu ý: Người nhận tiền cũng cần tự ghi giao dịch thu vào ví cá nhân nếu muốn theo dõi.</span></p>
                </div>
            </div>

            <div class="group-features">
                <h4>✨ Tính năng nổi bật</h4>
                <div class="group-features-grid">
                    <div class="group-feature-item">
                        <i class="ri-calculator-line"></i>
                        <strong>Tự động tính toán</strong>
                        <span>Hệ thống tự động tính ai nợ ai, không cần tính thủ công</span>
                    </div>
                    <div class="group-feature-item">
                        <i class="ri-lightbulb-line"></i>
                        <strong>Gợi ý thanh toán</strong>
                        <span>Đưa ra cách thanh toán tối ưu với ít giao dịch nhất</span>
                    </div>
                    <div class="group-feature-item">
                        <i class="ri-pie-chart-line"></i>
                        <strong>4 cách chia bill</strong>
                        <span>Chia đều, Phần trăm, Tùy chỉnh, Tỷ lệ - linh hoạt mọi tình huống</span>
                    </div>
                    <div class="group-feature-item">
                        <i class="ri-history-line"></i>
                        <strong>Lịch sử chi tiết</strong>
                        <span>Xem lại tất cả chi tiêu và thanh toán trong nhóm</span>
                    </div>
                </div>
            </div>

            <div class="group-example">
                <h4>📝 Ví dụ thực tế</h4>
                <p><strong>Tình huống:</strong> 3 bạn A, B, C đi ăn. A trả tiền nhà hàng 600.000đ, B mua đồ uống 300.000đ.</p>
                <p><strong>Cách chia:</strong> Chọn "Chia đều" cho cả 2 khoản → Mỗi người phải trả 300.000đ (600k + 300k = 900k / 3).</p>
                <p><strong>Số dư:</strong></p>
                <ul>
                    <li>A trả 600k, nợ 300k → Số dư: <strong>+300k</strong> (được nợ)</li>
                    <li>B trả 300k, nợ 300k → Số dư: <strong>0đ</strong> (đã thanh toán)</li>
                    <li>C trả 0k, nợ 300k → Số dư: <strong>-300k</strong> (đang nợ)</li>
                </ul>
                <p><strong>Gợi ý:</strong> C chuyển 300k cho A → Tất cả về 0đ ✅</p>
            </div>
        </div>
    </div>

    <!-- Tips -->
    <div class="help-section">
        <h2><i class="ri-lightbulb-line"></i> Mẹo hữu ích</h2>
        <div class="tips-grid-compact">
            <div class="tip-card-compact">
                <i class="ri-time-line"></i>
                <strong>Ghi chép hàng ngày</strong>
                <p>Ghi lại giao dịch ngay khi phát sinh để không quên.</p>
            </div>
            <div class="tip-card-compact">
                <i class="ri-alarm-warning-line"></i>
                <strong>Đặt ngân sách thực tế</strong>
                <p>Ngân sách nên dựa trên thu nhập thực tế của bạn.</p>
            </div>
            <div class="tip-card-compact">
                <i class="ri-repeat-line"></i>
                <strong>Dùng giao dịch định kỳ</strong>
                <p>Cho các khoản cố định như tiền nhà, điện nước.</p>
            </div>
            <div class="tip-card-compact">
                <i class="ri-bar-chart-line"></i>
                <strong>Xem báo cáo thường xuyên</strong>
                <p>Kiểm tra dashboard để hiểu thói quen chi tiêu.</p>
            </div>
            <div class="tip-card-compact">
                <i class="ri-group-line"></i>
                <strong>Ghi chi tiêu nhóm ngay</strong>
                <p>Thêm chi tiêu vào nhóm ngay sau khi thanh toán.</p>
            </div>
            <div class="tip-card-compact">
                <i class="ri-checkbox-circle-line"></i>
                <strong>Thanh toán định kỳ</strong>
                <p>Thanh toán số dư trong nhóm đều đặn để tránh quên.</p>
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
            <div class="faq-item">
                <h4>Nhóm chi tiêu có tự động trừ tiền từ ví cá nhân không?</h4>
                <p><strong>KHÔNG!</strong> Nhóm chi tiêu và Ví cá nhân là 2 hệ thống riêng biệt, KHÔNG tự động đồng bộ. Khi bạn thêm chi tiêu vào nhóm, hệ thống CHỈ ghi lại "ai nợ ai bao nhiêu", KHÔNG tự động trừ tiền từ ví. Bạn cần <strong>tự ghi thủ công</strong> vào menu Giao dịch nếu muốn theo dõi số dư ví thật.</p>
            </div>
            <div class="faq-item">
                <h4>Nhóm chi tiêu tính số dư như thế nào?</h4>
                <p><strong>Số dư = Tiền đã trả - Tiền phải trả.</strong> VD: Bạn trả 500k nhưng chỉ cần trả 300k → Số dư +200k (bạn được nợ 200k). Khi ai đó ghi nhận thanh toán, số dư sẽ tự động cập nhật về 0.</p>
            </div>
            <div class="faq-item">
                <h4>Gợi ý thanh toán hoạt động ra sao?</h4>
                <p>Hệ thống dùng thuật toán tối ưu để đưa ra cách thanh toán với <strong>ít giao dịch nhất</strong>. VD: A nợ B 100k, A nợ C 50k → Thay vì 2 giao dịch, có thể gợi ý B trả C 50k, A chỉ trả B 50k.</p>
            </div>
            <div class="faq-item">
                <h4>Có thể xóa chi tiêu trong nhóm không?</h4>
                <p>Có! Chỉ người tạo chi tiêu hoặc quản trị viên nhóm mới có thể xóa. Khi xóa, số dư của tất cả thành viên sẽ được tính lại tự động.</p>
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
