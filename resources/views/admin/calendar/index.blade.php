<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 fw-semibold text-dark">Barangay Calendar</h2>
        </div>
    </x-slot>

    <!-- ✅ INLINE STYLE ONLY -->
    <style>
        body {
            background: #eef2f7;
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial;
        }

        .card {
            border-radius: 16px !important;
            border: none !important;
            box-shadow: 0 8px 25px rgba(0,0,0,0.05) !important;
        }

        .card-body {
            padding: 20px;
        }

        /* FULLCALENDAR */
        .fc-toolbar-title {
            font-size: 20px !important;
            font-weight: 700;
            color: #1f2937;
        }

        .fc-button {
            background: #2563eb !important;
            border: none !important;
            border-radius: 10px !important;
            padding: 6px 12px !important;
            font-weight: 500 !important;
        }

        .fc-button:hover {
            background: #1d4ed8 !important;
        }

        .fc-daygrid-day:hover {
            background: #f1f5f9;
            cursor: pointer;
        }

        .fc-event {
            border: none !important;
            border-radius: 8px !important;
            padding: 3px 6px;
            font-size: 12px;
            font-weight: 500;
        }

        /* MODAL */
        .modal-content {
            border-radius: 16px !important;
            border: none !important;
            overflow: hidden;
        }

        .modal-header {
            background: #2563eb;
            color: white;
        }

        .btn-close {
            filter: invert(1);
        }

        .form-control {
            border-radius: 10px !important;
            padding: 10px;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 0.2rem rgba(37,99,235,0.15);
        }

        .btn-primary {
            background: #2563eb;
            border: none;
            border-radius: 10px;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-danger,
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