<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Requests Management</h2>
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
                                <th>Name</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Address</th>
                                <th>Document Type</th>
                                <th>Purpose</th>
                                <th>Company</th>
                                <th>Nature</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($requests as $request)
                                <tr>
                                    <td>{{ $request->full_name }}</td>
                                    <td>{{ $request->age }}</td>
                                    <td class="text-capitalize">{{ $request->gender }}</td>
                                    <td>{{ $request->address }}</td>
                                    <td>{{ $request->document_type }}</td>
                                    <td>{{ $request->purpose }}</td>
                                    <td>{{ $request->company_name ?: 'N/A' }}</td>
                                    <td>{{ $request->business_nature ?: 'N/A' }}</td>

                                    <!-- STATUS -->
                                    <td>
                                        <span class="badge
                                            @if($request->status == 'pending') bg-warning
                                            @elseif($request->status == 'approved') bg-success
                                            @elseif($request->status == 'rejected') bg-danger
                                            @else bg-secondary
                                            @endif
                                        ">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>

                                    <!-- ACTIONS -->
                                    <td class="d-flex gap-2">

                                        <!-- UPDATE STATUS -->
                                        <button class="btn btn-sm btn-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#statusModal{{ $request->id }}">
                                            Status
                                        </button>

                                        <!-- DELETE -->
                                        <form action="{{ route('requests.destroy', $request->id) }}"
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
    @foreach($requests as $request)

    <div class="modal fade" id="statusModal{{ $request->id }}" tabindex="-1">
        <div class="modal-dialog">

            <form action="{{ route('requests.updateStatus', $request->id) }}"
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
                        <option value="pending" {{ $request->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $request->status == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ $request->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
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