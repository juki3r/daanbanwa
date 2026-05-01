<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Concerns Management</h2>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-body">

                <!-- SEARCH -->
                <input type="text"
                    id="searchInput"
                    class="form-control mb-3"
                    placeholder="Search requests...">

                <!-- TABLE -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle" id="requestTable">

                        <thead class="table-dark">
                            <tr>
                                <th>Topic</th>
                                <th>Location</th>
                                <th>Descriptions</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($concerns as $concern)
                                <tr>
                                    <td>{{ $concern->title }}</td>
                                    <td>{{ $concern->location }}</td>
                                    <td>{{ $concern->description }}</td>
                                    <!-- STATUS -->
                                    <td>
                                        <span class="badge
                                            @if($concern->status == 'received') bg-primary
                                            @elseif($concern->status == 'under_review') bg-info
                                            @elseif($concern->status == 'in_progress') bg-secondary
                                            @elseif($concern->status == 'resolved') bg-success
                                            @elseif($concern->status == 'rejected') bg-danger
                                            @else bg-warning
                                            @endif
                                        ">
                                            {{ ucfirst($concern->status) }}
                                        </span>
                                    </td>

                                    <!-- ACTIONS -->
                                    <td class="d-flex gap-2">

                                        <!-- UPDATE STATUS -->
                                        <button class="btn btn-sm btn-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#statusModal{{ $concern->id }}">
                                            Status
                                        </button>

                                        <!-- DELETE -->
                                        <form action="{{ route('concern.destroy', $concern->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Delete this request?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">
                                                Delete
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- STATUS MODALS -->
    @foreach($concerns as $concern)

    <div class="modal fade" id="statusModal{{ $concern->id }}" tabindex="-1">
        <div class="modal-dialog">

            <form action="{{ route('concern.updateStatus', $concern->id) }}"
                method="POST"
                class="modal-content">

                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Update Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <label class="form-label">Status</label>

                    <select name="status" class="form-select" required>

                        <option value="received" {{ $concern->status == 'received' ? 'selected' : '' }}>
                            Received
                        </option>

                        <option value="under_review" {{ $concern->status == 'under_review' ? 'selected' : '' }}>
                            Under Review
                        </option>

                        <option value="in_progress" {{ $concern->status == 'in_progress' ? 'selected' : '' }}>
                            In Progress
                        </option>

                        <option value="resolved" {{ $concern->status == 'resolved' ? 'selected' : '' }}>
                            Resolved
                        </option>

                        <option value="rejected" {{ $concern->status == 'rejected' ? 'selected' : '' }}>
                            Rejected
                        </option>

                    </select>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button class="btn btn-success">
                        Save
                    </button>
                </div>

            </form>

        </div>
    </div>

    @endforeach

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