<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Cerificate Management</h2>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="card shadow-sm">
            <div class="card-body">


                <!-- TABLE -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle" id="requestTable">

                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Details</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($unreadRequests as $unreadRequest)
                                <tr>
                                  <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $unreadRequest->user->full_name }}</strong><br>
                                        {{ $unreadRequest->document_type }} <br>
                                        {{ $unreadRequest->purpose }}
                                    </td>
                                    <td>
                                        {{-- <a href="{{ route('notifications.markAsRead', $unreadRequest->id) }}" class="btn btn-sm btn-primary">Mark as Read</a> --}}
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center py-4 text-muted">
                                            No notifications found.
                                        </td>
                                    </tr>
                                @endforelse
                        </tbody>
                        
                    </table>
                    {{-- <div class="mt-3">
                        {{ $unreadRequests->links() }}
                    </div> --}}
                </div>

            </div>
        </div>
    </div>

    

    

    <!-- LIVE SEARCH -->
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function () {
            let value = this.value.toLowerCase();
            let rows = document.querySelectorAll("#requestTable tbody tr");

            rows.forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(value)
                    ? ""
                    : "none";
            });
        });

       
    </script>

</x-app-layout>