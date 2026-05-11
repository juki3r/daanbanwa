<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 fw-semibold text-dark">Barangay Calendar</h2>
        </div>
    </x-slot>

<style>
body {
    background: #f4f6fb;
    font-family: system-ui, -apple-system, Segoe UI, Roboto;
}

/* MAIN LAYOUT */
.calendar-layout {
    display: flex;
    gap: 20px;
}

/* LEFT PANEL */
.sidebar {
    width: 320px;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.card-box {
    background: #fff;
    border-radius: 16px;
    padding: 16px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.05);
}

/* EVENT LIST */
.event-item {
    padding: 10px;
    border-left: 4px solid #3b82f6;
    background: #f9fafb;
    border-radius: 10px;
    margin-bottom: 10px;
    transition: 0.2s;
}

.event-item:hover {
    background: #eef2ff;
    transform: translateX(2px);
}

.event-title {
    font-weight: 600;
    font-size: 14px;
}

.event-date {
    font-size: 12px;
    color: #6b7280;
}

/* CALENDAR */
.calendar-main {
    flex: 1;
}

.card-calendar {
    background: #fff;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.05);
}

/* FULLCALENDAR */
.fc-toolbar-title {
    font-size: 18px !important;
    font-weight: 700;
}

.fc-button {
    background: #3b82f6 !important;
    border: none !important;
    border-radius: 10px !important;
}

.fc-button:hover {
    background: #2563eb !important;
}

.fc-daygrid-day:hover {
    background: #f1f5f9;
}

/* MODAL */
.modal-content {
    border-radius: 16px !important;
}

.modal-header {
    background: #3b82f6;
    color: white;
}
.btn-close { filter: invert(1); }
</style>

<div class="container-fluid py-4">

    <div class="calendar-layout">

        <!-- LEFT SIDEBAR -->
        <div class="sidebar">

            <!-- TODAY -->
            <div class="card-box">
                <h6 class="fw-bold mb-2">📌 Today</h6>
                <div id="todayEvents"></div>
            </div>

            <!-- UPCOMING -->
            <div class="card-box">
                <h6 class="fw-bold mb-2">📅 Upcoming Events</h6>
                <div id="upcomingEvents"></div>
            </div>

        </div>

        <!-- CALENDAR -->
        <div class="calendar-main">
            <div class="card-calendar">
                <div id="calendar"></div>
            </div>
        </div>

    </div>

</div>

<!-- MODALS (UNCHANGED) -->
@include('calendar.modals') {{-- optional if you want split --}}

<!-- FULLCALENDAR -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let eventsCache = [];

    let calendarEl = document.getElementById('calendar');

    let calendar = new FullCalendar.Calendar(calendarEl, {

        initialView: 'dayGridMonth',
        height: 650,
        events: '/admin/calendar/events',

        eventDidMount: function(info) {
            eventsCache.push(info.event);
            renderSidebar();
        },

        dateClick: function(info) {
            document.getElementById('event_date').value = info.dateStr;
            new bootstrap.Modal(document.getElementById('eventModal')).show();
        },

        eventClick: function(info) {
            let event = info.event;

            document.getElementById('edit_event_id').value = event.id;
            document.getElementById('edit_title').value = event.title;
            document.getElementById('edit_description').value = event.extendedProps.description ?? '';
            document.getElementById('edit_color').value = event.backgroundColor || '#3b82f6';
            document.getElementById('edit_date').value = event.startStr;

            new bootstrap.Modal(document.getElementById('viewEventModal')).show();
        }
    });

    calendar.render();

    function renderSidebar() {

        let todayEl = document.getElementById('todayEvents');
        let upcomingEl = document.getElementById('upcomingEvents');

        todayEl.innerHTML = "";
        upcomingEl.innerHTML = "";

        let today = new Date().toISOString().split('T')[0];

        eventsCache.forEach(ev => {

            let date = ev.startStr;

            let html = `
                <div class="event-item">
                    <div class="event-title">${ev.title}</div>
                    <div class="event-date">${date}</div>
                </div>
            `;

            if (date === today) {
                todayEl.innerHTML += html;
            } else {
                upcomingEl.innerHTML += html;
            }

        });
    }

});
</script>

</x-app-layout>