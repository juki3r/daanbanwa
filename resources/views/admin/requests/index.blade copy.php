<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Cerificate Management</h2>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="card shadow-sm">
            <div class="card-body">

                <div class="d-flex justify-content-end align-items-center mb-3">
                    <button class="btn btn-success"
                        data-bs-toggle="modal"
                        data-bs-target="#createRequestModal">
                        + Create Request
                    </button>
                </div>

                <!-- TABLE -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle" id="requestTable">

                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
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
                            @forelse($requests as $request)
                                <tr>
                                    <td>{{ $loop->iteration + ($requests->firstItem() - 1) }}</td>
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
                                    <td class="text-center align-middle">
                                        <div class="d-flex flex-row align-items-center justify-content-center gap-2">

                                            <!-- UPDATE STATUS -->
                                            <button class="btn btn-sm btn-primary d-flex align-items-center gap-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#statusModal{{ $request->id }}">
                                                
                                                <i class="bi bi-pencil-square"></i>
                                                Status
                                            </button>

                                            <!-- DELETE -->
                                            <form action="{{ route('requests.destroy', $request->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Delete this request?')"
                                                class="m-0">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="btn btn-sm btn-danger d-flex align-items-center gap-1">

                                                    <i class="bi bi-trash"></i>
                                                    Delete
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center py-4 text-muted">
                                            No records found.
                                        </td>
                                    </tr>
                                @endforelse
                        </tbody>
                        
                    </table>
                    <div class="mt-3">
                        {{ $requests->links() }}
                    </div>
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

    <div class="modal fade" id="createRequestModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('requests.store') }}" method="POST" class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Create New Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row g-3">

                    <!-- Resident -->
                    <div class="col-md-12 d-none">
                        <label class="form-label">Select Resident</label>
                        <input type="text" name="user_id" class="form-control" value="{{ auth()->id() }}" required>
                    </div>

                    <!-- Full Name -->
                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control" required>
                    </div>

                    <!-- Age -->
                    <div class="col-md-6">
                        <label class="form-label">Age</label>
                        <input type="number" name="age" class="form-control" required>
                    </div>

                    <!-- Gender -->
                    <div class="col-md-6">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select" required>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="prefer not to say">Prefer not to say</option>
                        </select>
                    </div>

                    <!-- Address -->
                    <div class="col-md-6">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control" required>
                    </div>

                    <!-- Document Type -->
                    <div class="col-md-12">
                        <label class="form-label">Document Type</label>
                        <select name="document_type" class="form-select" id="documentTypeSelect" required>
                            <option>Barangay Certification</option>
                            <option>Barangay Clearance</option>
                            <option>Certificate of Residency</option>
                            <option>Certificate of Indigency</option>
                            <option>Business Clearance</option>
                            <option>Certificate of Good Moral</option>
                            <option>Certificate of Solo Parent</option>
                            <option>Certificate of Late Registration</option>
                            <option>First Time Job Seeker Certificate</option>
                            <option>Certificate of Unemployment</option>
                        </select>
                    </div>

                    <!-- Purpose -->
                    <div class="col-md-12">
                        <label class="form-label">Purpose</label>
                        <textarea name="purpose" class="form-control" rows="3" required></textarea>
                    </div>

                    <!-- Business Fields -->
                    <div class="col-md-6 business-fields d-none">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company_name" class="form-control">
                    </div>

                    <div class="col-md-6 business-fields d-none">
                        <label class="form-label">Business Nature</label>
                        <input type="text" name="business_nature" class="form-control">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Submit Request</button>
                </div>
            </form>
        </div>
    </div>

    <!-- LIVE SEARCH -->
    <script>
        // document.getElementById('searchInput').addEventListener('keyup', function () {
        //     let value = this.value.toLowerCase();
        //     let rows = document.querySelectorAll("#requestTable tbody tr");

        //     rows.forEach(row => {
        //         row.style.display = row.innerText.toLowerCase().includes(value)
        //             ? ""
        //             : "none";
        //     });
        // });

        document.getElementById('searchInput').addEventListener('keyup', function () {
            let value = this.value;

            window.location.href = "?search=" + value;
        });

        const documentTypeSelect = document.getElementById('documentTypeSelect');
            const businessFields = document.querySelectorAll('.business-fields');

            documentTypeSelect.addEventListener('change', function () {
                if (this.value === 'Business Clearance') {
                    businessFields.forEach(field => field.classList.remove('d-none'));
                } else {
                    businessFields.forEach(field => field.classList.add('d-none'));
                }
            });
    </script>

</x-app-layout>