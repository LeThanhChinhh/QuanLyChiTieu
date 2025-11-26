@extends('layouts.app')

@section('title', 'Lịch Giao dịch')

@section('content')
<style>
    /* Custom List View Styles for Glassmorphism */
    .fc-list {
        border: none !important;
    }
    .fc-list-day-cushion {
        background: rgba(255, 255, 255, 0.5) !important;
        backdrop-filter: blur(5px);
    }
    .fc-list-event:hover td {
        background: rgba(255, 255, 255, 0.8) !important;
        cursor: pointer;
    }
    .fc-list-event-dot {
        border-width: 5px !important;
    }
    .fc-list-event-title {
        font-weight: 600 !important;
        color: #1f2937 !important; /* Dark text */
    }
    .fc-list-event-time {
        color: #4b5563 !important;
    }
    .fc-theme-standard .fc-list {
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
</style>

<div class="container-fluid">
    <div class="glass-card p-4">
        <div id="calendar"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales/vi.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'vi',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,viewWeek,viewDay'
            },
            buttonText: {
                today: 'Hôm nay',
                month: 'Tháng',
                viewWeek: 'Tuần',
                viewDay: 'Ngày'
            },
            views: {
                viewWeek: {
                    type: 'listWeek',
                    buttonText: 'Tuần'
                },
                viewDay: {
                    type: 'listDay',
                    buttonText: 'Ngày'
                }
            },
            titleFormat: { year: 'numeric', month: 'long' },
            dayHeaderFormat: { weekday: 'long' },
            slotLabelFormat: {
                hour: 'numeric',
                minute: '2-digit',
                omitZeroMinute: false,
                meridiem: 'short'
            },
            eventTimeFormat: {
                hour: 'numeric',
                minute: '2-digit',
                meridiem: 'short'
            },
            events: '{{ route("calendar.events") }}',
            eventClick: function(info) {
    const props = info.event.extendedProps;
    const amount = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(props.amount);
    const time = info.event.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    
    // Config màu sắc và icon
    let config = { label: 'Khác', color: '#6B7280', icon: props.category_icon };
    if (props.type === 'income') config = { label: 'Thu nhập', color: '#10B981', icon: 'ri-arrow-up-circle-fill' };
    if (props.type === 'expense') config = { label: 'Chi tiêu', color: '#EF4444', icon: 'ri-arrow-down-circle-fill' };
    if (props.type === 'transfer') config = { label: 'Chuyển khoản', color: '#3B82F6', icon: 'ri-exchange-fill' };

    // URL cho nút sửa/xóa
    const editUrl = `/transactions/${props.id}/edit`;
    const deleteUrl = `/transactions/${props.id}`;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    Swal.fire({
        customClass: { popup: 'glass-modal' },
        showConfirmButton: false,
        showCloseButton: true,
        html: `
            <div class="text-center mb-4">
                <div style="width: 60px; height: 60px; background: ${config.color}15; color: ${config.color}; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto; font-size: 2rem;">
                    <i class="${config.icon}"></i>
                </div>
                <h4 class="fw-bold mb-1">${props.category_name}</h4>
                <div class="fs-2 fw-bolder" style="color: ${config.color}">${amount}</div>
            </div>

            <div class="bg-white rounded-4 p-3 mb-4 shadow-sm border">
                <div class="detail-row">
                    <span class="detail-label"><i class="ri-time-line"></i> Thời gian</span>
                    <span class="detail-value">${time}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="ri-wallet-3-line"></i> Ví nguồn</span>
                    <span class="detail-value">${props.wallet_name}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"><i class="ri-file-text-line"></i> Ghi chú</span>
                    <span class="detail-value">${props.description || 'Không có'}</span>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="${editUrl}" class="btn btn-light flex-grow-1 border">
                    <i class="ri-pencil-line me-1"></i> Sửa
                </a>
                <button onclick="deleteTransaction(${props.id})" class="btn btn-danger flex-grow-1 text-white">
                    <i class="ri-delete-bin-line me-1"></i> Xóa
                </button>
            </div>
        `
    });
            },
            height: 'auto',
            contentHeight: 600,
        });
        calendar.render();
    });

    // Hàm xóa giao dịch (đặt ngoài dom ready để gọi được từ html string)
    window.deleteTransaction = function(id) {
        if(confirm('Bạn có chắc muốn xóa giao dịch này?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/transactions/${id}`;
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';

            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }
    };
</script>

<style>
    /* FullCalendar Glassmorphism Overrides */
    :root {
        --fc-border-color: rgba(0, 0, 0, 0.1);
        --fc-button-text-color: #1F2937;
        --fc-button-bg-color: rgba(255, 255, 255, 0.5);
        --fc-button-border-color: rgba(0, 0, 0, 0.1);
        --fc-button-hover-bg-color: rgba(255, 255, 255, 0.8);
        --fc-button-hover-border-color: rgba(0, 0, 0, 0.2);
        --fc-button-active-bg-color: #10B981;
        --fc-button-active-border-color: #10B981;
        --fc-event-bg-color: #3788d8;
        --fc-event-border-color: #3788d8;
        --fc-event-text-color: #fff;
        --fc-today-bg-color: rgba(16, 185, 129, 0.1);
    }

    #calendar {
        color: #1F2937;
    }

    .fc-theme-standard td, .fc-theme-standard th {
        border-color: var(--fc-border-color);
    }

    .fc .fc-toolbar-title {
        color: #1F2937;
        font-weight: 600;
    }

    .fc .fc-button-primary {
        background-color: var(--fc-button-bg-color);
        border-color: var(--fc-button-border-color);
        color: var(--fc-button-text-color);
        backdrop-filter: blur(10px);
        font-weight: 500;
    }

    .fc .fc-button-primary:hover {
        background-color: var(--fc-button-hover-bg-color);
        border-color: var(--fc-button-hover-border-color);
        color: #000;
    }

    .fc .fc-button-primary:not(:disabled).fc-button-active, 
    .fc .fc-button-primary:not(:disabled):active {
        background-color: var(--fc-button-active-bg-color);
        border-color: var(--fc-button-active-border-color);
        color: white;
    }

    .fc-daygrid-day-number {
        color: #4B5563;
        text-decoration: none;
        font-weight: 500;
    }

    .fc-col-header-cell-cushion {
        color: #1F2937;
        text-decoration: none;
        font-weight: 600;
        padding: 8px 0;
    }

    .fc-event {
        cursor: pointer;
        border: none;
        padding: 6px 10px;
        border-radius: 8px;
        margin-bottom: 4px !important;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        font-size: 0.85rem;
        font-weight: 500;
        transition: transform 0.2s, box-shadow 0.2s;
        display: flex;
        align-items: center;
    }

    .fc-event:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        filter: brightness(1.05);
    }

    .fc-h-event {
        background-color: var(--fc-event-bg-color);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .fc-daygrid-event-dot {
        border-color: white;
    }
    
    /* Make the All Day section look cleaner */
    .fc-timegrid-axis-cushion,
    .fc-timegrid-slot-label-cushion {
        color: #6B7280;
    }
    
    .fc-scrollgrid-section-header th {
        background-color: rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(5px);
    }
    
    .fc-day-today {
        background-color: var(--fc-today-bg-color) !important;
    }

    /* List View Customization */
    .fc-list {
        border: none !important;
    }
    
    .fc-list-day-cushion {
        background-color: rgba(255, 255, 255, 0.5) !important;
        backdrop-filter: blur(5px);
    }

    .fc-list-day-text, .fc-list-day-side-text {
        color: #1F2937 !important;
        font-weight: 600;
    }

    .fc-list-event:hover td {
        background-color: rgba(255, 255, 255, 0.3) !important;
    }

    .fc-list-event-title {
        color: #1F2937;
        font-weight: 500;
    }

    .fc-list-event-time {
        color: #4B5563;
        white-space: nowrap;
        padding-right: 15px !important;
        min-width: 60px;
        vertical-align: middle !important;
    }
    
    .fc-list-event-graphic {
        padding: 0 15px !important;
        vertical-align: middle !important;
        display: table-cell !important; /* Ensure it behaves like a cell */
    }
    
    .fc-list-event-dot {
        border-width: 6px;
        box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.5);
        display: inline-block; /* Ensure it renders correctly */
    }
    
    .fc-list-table td {
        border-color: rgba(0, 0, 0, 0.05);
        padding: 12px 8px !important; /* Add breathing room */
    }
    
    .fc-list-event-title {
        vertical-align: middle !important;
    }

    /* Thêm vào cuối phần <style> */
    .swal2-popup.glass-modal {
        background: rgba(255, 255, 255, 0.9) !important;
        backdrop-filter: blur(20px) !important;
        border: 1px solid rgba(255, 255, 255, 0.5) !important;
        border-radius: 24px !important;
        box-shadow: 0 25px 50px rgba(0,0,0,0.1) !important;
        padding: 2rem !important;
    }

    .swal2-title {
        font-family: 'Be Vietnam Pro', sans-serif !important;
        color: #1F2937 !important;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px dashed #E5E7EB;
    }
    .detail-row:last-child { border-bottom: none; }
    .detail-label { color: #6B7280; font-size: 0.9rem; display: flex; align-items: center; gap: 6px; }
    .detail-value { font-weight: 600; color: #374151; font-size: 0.95rem; text-align: right; }
</style>
@endsection
