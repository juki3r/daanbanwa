<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 fw-semibold text-dark">Barangay Calendar</h2>
        </div>
    </x-slot>

    <div class="container-fluid py-4 bg-light min-vh-100">

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>

    </div>

    <!-- EVENT MODAL -->
    <div class="modal fade" id="eventModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Add Barangay Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="event_date">

                    <div class="mb-3">
                        <label>Event Title</label>
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

                let modal = new bootstrap.Modal(document.getElementById('eventModal'));
                modal.show();
            },

            eventClick: function(info) {
                alert(
                    "Event: " + info.event.title +
                    "\nDate: " + info.event.startStr
                );
            }
        });

        calendar.render();

        // SAVE EVENT
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

    });
    </script>

</x-app-layout>