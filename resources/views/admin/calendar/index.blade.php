<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 fw-semibold text-dark">Calendar</h2>
        </div>
    </x-slot>

    <div class="container-fluid py-4 bg-light min-vh-100">
        <div id="calendar"></div>


       
    </div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: '/admin/calendar/events',

        dateClick: function(info) {
            let title = prompt("Event Title:");
            if (title) {
                fetch('/admin/calendar/events', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        title: title,
                        start_date: info.dateStr
                    })
                }).then(() => calendar.refetchEvents());
            }
        }
    });

    calendar.render();
});
</script>

</x-app-layout>