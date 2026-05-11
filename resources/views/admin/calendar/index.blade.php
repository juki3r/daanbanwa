<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 fw-semibold text-dark">Barangay Calendar</h2>
        </div>
    </x-slot>

    <!-- ✅ INLINE STYLE ONLY -->
    <style>
/* =========================
   GLOBAL BACKGROUND
========================= */
body {
    background: #f6f8fc;
    font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial;
}

/* =========================
   CALENDAR WRAPPER CARD
========================= */
.card {
    border: none !important;
    border-radius: 18px !important;
    box-shadow: 0 10px 30px rgba(0,0,0,0.06) !important;
    overflow: hidden;
}

.card-body {
    padding: 24px;
    background: #ffffff;
}

/* =========================
   FULLCALENDAR HEADER
========================= */
.fc-toolbar {
    margin-bottom: 18px !important;
}

.fc-toolbar-title {
    font-size: 18px !important;
    font-weight: 700;
    color: #111827;
}

/* Buttons - softer modern look */
.fc-button {
    background: #3b82f6 !important;
    border: none !important;
    border-radius: 10px !important;
    padding: 6px 10px !important;
    font-size: 13px !important;
    font-weight: 500 !important;
    box-shadow: none !important;
}

.fc-button:hover {
    background: #2563eb !important;
}

.fc-button:active {
    transform: scale(0.97);
}

/* =========================
   CALENDAR GRID (CLEAN LOOK)
========================= */
.fc-scrollgrid {
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #e5e7eb !important;
}

.fc-daygrid-day {
    transition: all 0.15s ease;
}

.fc-daygrid-day:hover {
    background: #f3f4f6;
}

/* TODAY HIGHLIGHT */
.fc-day-today {
    background: #eff6ff !important;
}

/* =========================
   EVENTS (MINIMAL + CLEAN)
========================= */
.fc-event {
    border: none !important;
    border-radius: 6px !important;
    padding: 2px 6px;
    font-size: 12px;
    font-weight: 500;
    opacity: 0.95;
}

.fc-event:hover {
    opacity: 1;
    transform: scale(1.02);
}

/* =========================
   MODALS (SOFT UI)
========================= */
.modal-content {
    border-radius: 16px !important;
    border: none !important;
    overflow: hidden;
}

.modal-header {
    background: #3b82f6;
    color: white;
    border: none;
}

.modal-title {
    font-weight: 600;
    font-size: 15px;
}

.btn-close {
    filter: invert(1);
}

/* =========================
   INPUT FIELDS
========================= */
label {
    font-size: 12px;
    font-weight: 600;
    color: #374151;
}

.form-control {
    border-radius: 10px !important;
    border: 1px solid #e5e7eb;
    padding: 10px;
    font-size: 14px;
}

.form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 0.2rem rgba(59,130,246,0.15);
}

/* =========================
   BUTTONS
========================= */
.btn-primary {
    background: #3b82f6;
    border: none;
    border-radius: 10px;
    font-weight: 500;
}

.btn-primary:hover {
    background: #2563eb;
}

.btn-danger {
    border-radius: 10px;
}

.btn-secondary {
    border-radius: 10px;
}
</style>

    <div class="container-fluid py-4 bg-light min-vh-100">

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div id="calendar"></div>
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
                        <input type="color" id="color" class="form-control form-control-color" value="#0d6efd">
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

        let calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 700,
            events: '/admin/calendar/events',

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
                document.getElementById('edit_color').value = event.backgroundColor || event.color;
                document.getElementById('edit_date').value = event.startStr;

                new bootstrap.Modal(document.getElementById('viewEventModal')).show();
            }
        });

        calendar.render();

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
            .then(res => res.json())
            .then(() => {
                bootstrap.Modal.getInstance(document.getElementById('eventModal')).hide();
                calendar.refetchEvents();
            });

        });

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
            .then(res => res.json())
            .then(() => {
                bootstrap.Modal.getInstance(document.getElementById('viewEventModal')).hide();
                calendar.refetchEvents();
            });

        });

        document.getElementById('deleteEvent').addEventListener('click', function () {

            let id = document.getElementById('edit_event_id').value;

            if (!confirm("Delete this event?")) return;

            fetch(`/admin/calendar/events/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(() => {
                bootstrap.Modal.getInstance(document.getElementById('viewEventModal')).hide();
                calendar.refetchEvents();
            });

        });

    });
    </script>

</x-app-layout>