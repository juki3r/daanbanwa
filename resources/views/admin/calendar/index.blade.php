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

/* SIDEBAR */
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

/* EVENT ITEMS */
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
    color: #111827;
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
    cursor: pointer;
}

.fc-day-today {
    background: #eaf2ff !important;
}

/* EVENTS */
.fc-event {
    border: none !important;
    border-radius: 6px !important;
    padding: 2px 6px;
    font-size: 12px;
}

/* MODAL */
.modal-content {
    border-radius: 16px !important;
    border: none;
    overflow: hidden;
}

.modal-header {
    background: #3b82f6;
    color: white;
}

.btn-close {
    filter: invert(1);
}

.form-control {
    border-radius: 10px !important;
}

.form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 0.2rem rgba(59,130,246,0.15);
}

.btn-primary {
    background: #3b82f6;
    border: none;
    border-radius: 10px;
}

.btn-primary:hover {
    background: #2563eb;
}

.btn-danger,
.btn-secondary {
    border-radius: 10px;
}
</style>

<div class="container-fluid py-4">

    <div class="calendar-layout">

        <!-- LEFT SIDEBAR -->
        <div class="sidebar">

            <!-- TODAY -->
            <div class="card-box">
                <h6 class="fw-bold mb-3">📌 Today’s Events</h6>
                <div id="todayEvents"></div>
            </div>

            <!-- UPCOMING -->
            <div class="card-box">
                <h6 class="fw-bold mb-3">📅 Upcoming Events</h6>
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

                <div class="mb-3">
                    <label>Date</label>
                    <input type="text" id="edit_date" class="form-control" disabled>
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

        allEvents.forEach(ev => {

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
        .then(() => {
            location.reload();
        });

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