<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Blotter Management</h2>
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
                    <table class="table table-bordered table-hover align-middle" id="blotterTable">

                        <thead class="table-dark">
                            <tr>
                                <th>Case number</th>
                                <th>Name</th>
                                <th>Statement</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($blotters as $blotter)
                                <tr>
                                    <td>{{ $blotter->case_number }}</td>
                                    <td>{{ $blotter->complainant_name }}</td>
                                    <td>{{ $blotter->statement }}</td>
                                    <!-- STATUS -->
                                    <td>
                                        <span class="badge
                                            @if($blotter->status == 'pending') bg-warning
                                            @elseif($blotter->status == 'under_review') bg-success
                                            @else bg-warning
                                            @endif
                                        ">
                                            {{ ucfirst($blotter->status) }}
                                        </span>
                                    </td>

                                    <!-- ACTIONS -->
                                    <td class="d-flex gap-2">

                                        <!-- UPDATE STATUS -->
                                        <button class="btn btn-sm btn-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#statusModal{{ $blotter->id }}">
                                            Status
                                        </button>

                                        <!-- DELETE -->
                                        <form action="{{ route('blotter.destroy', $blotter->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Delete this blotter?')">
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
    @foreach($blotters as $blotter)

    <div class="modal fade" id="statusModal{{ $blotter->id }}" tabindex="-1">
        <div class="modal-dialog">

            <form action="{{ route('blotter.updateStatus', $blotter->id) }}"
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

                        <option value="received" {{ $blotter->status == 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>

                        <option value="under_review" {{ $blotter->status == 'approved' ? 'selected' : '' }}>
                            Approved
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
            let rows = document.querySelectorAll("#blotterTable tbody tr");

            rows.forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(value)
                    ? ""
                    : "none";
            });
        });
    </script>

</x-app-layout>