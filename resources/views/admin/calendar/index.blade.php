<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 fw-semibold text-dark">Barangay Calendar</h2>
        </div>
    </x-slot>

<style>
/* ========================================
   COLOR THEME + SMALLER CALENDAR
   Replace your existing <style> with this
======================================== */

/* ===== THEME COLORS =====
Primary: #0f766e   (Teal)
Primary Hover: #0d9488
Accent: #14b8a6
Background: #f0fdfa
Card: #ffffff
Text: #0f172a
Muted: #64748b
Border: #d1fae5
======================================== */

body {
    background: linear-gradient(180deg, #f0fdfa 0%, #f8fafc 100%);
    font-family: system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
}

/* MAIN LAYOUT */
.calendar-layout {
    display: flex;
    gap: 20px;
    align-items: flex-start;
}

/* SIDEBAR */
.sidebar {
    width: 300px;
    display: flex;
    flex-direction: column;
    gap: 16px;
    flex-shrink: 0;
}

/* CARDS */
.card-box,
.card-calendar {
    background: #ffffff;
    border: 1px solid #d1fae5;
    border-radius: 20px;
    box-shadow:
        0 10px 25px rgba(15, 118, 110, 0.06),
        0 2px 6px rgba(15, 23, 42, 0.03);
}

/* SIDEBAR CARD */
.card-box {
    padding: 18px;
}

.card-box h6 {
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 14px;
}

/* EVENT ITEMS */
.event-item {
    padding: 12px 14px;
    border-left: 4px solid #14b8a6;
    background: #f8fffe;
    border-radius: 12px;
    margin-bottom: 10px;
    border: 1px solid #ecfdf5;
    transition: all 0.2s ease;
}

.event-item:hover {
    background: #f0fdfa;
    transform: translateX(3px);
    box-shadow: 0 6px 14px rgba(20, 184, 166, 0.08);
}

.event-title {
    font-size: 14px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 4px;
}

.event-date {
    font-size: 12px;
    color: #64748b;
}

/* CALENDAR CONTAINER */
.calendar-main {
    flex: 1;
    min-width: 0;
}

.card-calendar {
    padding: 20px;
}

/* ========================================
   SMALLER CALENDAR SIZE
======================================== */
#calendar {
    max-width: 100%;
    margin: 0 auto;
}

/* Reduce overall calendar scale */
.fc {
    font-size: 0.90rem;
}

/* Smaller toolbar */
.fc-toolbar {
    margin-bottom: 1rem !important;
}

.fc-toolbar-title {
    font-size: 1.25rem !important;
    font-weight: 700;
    color: #0f172a;
}

/* Smaller buttons */
.fc .fc-button {
    background: #0f766e !important;
    border: none !important;
    border-radius: 10px !important;
    padding: 0.45rem 0.75rem !important;
    font-size: 0.82rem !important;
    font-weight: 600 !important;
    box-shadow: none !important;
    transition: all 0.2s ease;
}

.fc .fc-button:hover {
    background: #0d9488 !important;
}

.fc .fc-button:focus {
    box-shadow: 0 0 0 0.2rem rgba(15, 118, 110, 0.15) !important;
}

/* Calendar border */
.fc-scrollgrid {
    border: 1px solid #e2e8f0 !important;
    border-radius: 14px;
    overflow: hidden;
}

/* Day cells */
.fc-daygrid-day {
    transition: background 0.15s ease;
}

.fc-daygrid-day:hover {
    background: #f8fafc;
    cursor: pointer;
}

/* Header row */
.fc-col-header-cell {
    background: #f8fafc;
}

.fc-col-header-cell-cushion {
    font-size: 0.75rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    padding: 8px 0;
}

/* Day numbers */
.fc-daygrid-day-number {
    font-size: 0.85rem;
    font-weight: 600;
    color: #334155;
    padding: 6px !important;
}

/* Today highlight */
.fc-day-today {
    background: #ecfdf5 !important;
}

/* Event badges */
.fc-event {
    border: none !important;
    border-radius: 8px !important;
    padding: 2px 6px;
    font-size: 0.72rem;
    font-weight: 600;
}

/* ========================================
   MODALS
======================================== */
.modal-content {
    border: none !important;
    border-radius: 18px !important;
    overflow: hidden;
    box-shadow: 0 20px 50px rgba(15, 23, 42, 0.12);
}

.modal-header {
    background: linear-gradient(135deg, #0f766e, #14b8a6);
    color: white;
    border: none;
    padding: 1rem 1.25rem;
}

.modal-title {
    font-size: 15px;
    font-weight: 700;
}

.btn-close {
    filter: invert(1);
}

/* FORM CONTROLS */
label {
    font-size: 12px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 6px;
}

.form-control {
    border-radius: 10px !important;
    border: 1px solid #dbe2ea;
    padding: 10px 12px;
    font-size: 14px;
}

.form-control:focus {
    border-color: #14b8a6;
    box-shadow: 0 0 0 0.2rem rgba(20, 184, 166, 0.15);
}

/* BUTTONS */
.btn-primary {
    background: #0f766e;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    padding: 8px 16px;
}

.btn-primary:hover {
    background: #0d9488;
}

.btn-danger,
.btn-secondary {
    border-radius: 10px;
    font-weight: 600;
}

/* EMPTY STATE */
#todayEvents:empty::after,
#upcomingEvents:empty::after {
    content: "No events";
    display: block;
    color: #94a3b8;
    font-size: 13px;
    font-style: italic;
    padding: 8px 0;
}

/* RESPONSIVE */
@media (max-width: 992px) {
    .calendar-layout {
        flex-direction: column;
    }

    .sidebar {
        width: 100%;
    }

    .fc {
        font-size: 0.82rem;
    }

    .fc-toolbar {
        flex-direction: column;
        gap: 10px;
    }
}
</style>

<div class="container-fluid py-4">

    <div class="calendar-layout">

        <!-- SIDEBAR -->
        <div class="sidebar">

            <div class="card-box">
                <h6 class="fw-bold mb-3">📌 Today</h6>
                <div id="todayEvents"></div>
            </div>

            <div class="card-box">
                <h6 class="fw-bold mb-3">📅 Upcoming</h6>
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

<!-- ADD EVENT MODAL -->
<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Add Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="event_date">

                <div class="mb-3">
                    <label>Title</label>
                    <input type="text" id="title" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Description</label>
                    <textarea id="description" class="form-control"></textarea>
                </div>

                <div class="mb-3">
                    <label>Color</label>
                    <input type="color" id="color" class="form-control form-control-color" value="#3b82f6">
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" id="saveEvent">Save</button>
            </div>

        </div>
    </div>
</div>

<!-- VIEW / EDIT MODAL -->
<div class="modal fade" id="viewEventModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Event Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="edit_event_id">

                <div class="mb-3">
                    <label>Title</label>
                    <input type="text" id="edit_title" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Description</label>
                    <textarea id="edit_description" class="form-control"></textarea>
                </div>

                <div class="mb-3">
                    <label>Color</label>
                    <input type="color" id="edit_color" class="form-control form-control-color">
                </div>

                <!-- FORMATTED DATE -->
                <div class="mb-3">
                    <label>Date</label>
                    <input type="text" id="edit_date" class="form-control bg-light" disabled>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-danger" id="deleteEvent">Delete</button>
                <button class="btn btn-primary" id="updateEvent">Update</button>
            </div>

        </div>
    </div>
</div>

<!-- FULLCALENDAR -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let calendarEl = document.getElementById('calendar');
    let selectedDate = null;
    let allEvents = [];

    // ✅ DATE FORMATTER
    function formatDate(dateString) {
        let date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }

    let calendar = new FullCalendar.Calendar(calendarEl, {

        initialView: 'dayGridMonth',
        height: 650,
        events: '/admin/calendar/events',

        eventDidMount: function(info) {
            allEvents.push(info.event);
            renderSidebar();
        },

        dateClick: function(info) {
            selectedDate = info.dateStr;
            document.getElementById('event_date').value = selectedDate;
            new bootstrap.Modal(document.getElementById('eventModal')).show();
        },

        eventClick: function(info) {

            let event = info.event;

            document.getElementById('edit_event_id').value = event.id;
            document.getElementById('edit_title').value = event.title;
            document.getElementById('edit_description').value = event.extendedProps.description ?? '';
            document.getElementById('edit_color').value = event.backgroundColor || '#3b82f6';

            // ✅ FORMATTED DATE HERE
            document.getElementById('edit_date').value = formatDate(event.startStr);

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

        allEvents.forEach(ev => {

            let date = ev.startStr;

            let html = `
                <div class="event-item">
                    <div class="event-title">${ev.title}</div>
                    <div class="event-date">${formatDate(date)}</div>
                </div>
            `;

            if (date === today) {
                todayEl.innerHTML += html;
            } else {
                upcomingEl.innerHTML += html;
            }

        });
    }

    // SAVE
    document.getElementById('saveEvent').addEventListener('click', function () {

        fetch('/admin/calendar/events', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                title: document.getElementById('title').value,
                description: document.getElementById('description').value,
                start_date: selectedDate,
                color: document.getElementById('color').value
            })
        })
        .then(() => location.reload());

    });

    // UPDATE
    document.getElementById('updateEvent').addEventListener('click', function () {

        let id = document.getElementById('edit_event_id').value;

        fetch(`/admin/calendar/events/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                title: document.getElementById('edit_title').value,
                description: document.getElementById('edit_description').value,
                color: document.getElementById('edit_color').value
            })
        })
        .then(() => location.reload());

    });

    // DELETE
    document.getElementById('deleteEvent').addEventListener('click', function () {

        let id = document.getElementById('edit_event_id').value;

        if (!confirm("Delete this event?")) return;

        fetch(`/admin/calendar/events/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(() => location.reload());

    });

});
</script>

</x-app-layout>