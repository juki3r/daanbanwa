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

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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

                                    <td class="d-flex gap-2">

                                        <!-- EDIT -->
                                        <button class="btn btn-sm btn-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal{{ $request->id }}">
                                            Edit
                                        </button>

                                        <!-- DELETE -->
                                        <form action="{{ route('requests.destroy', $request->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this request?')">
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

    <!-- EDIT MODALS (MUST BE OUTSIDE TABLE) -->
    @foreach($requests as $request)
    <div class="modal fade" id="editModal{{ $request->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">

            <form action="{{ route('requests.update', $request->id) }}"
                method="POST"
                class="modal-content">

                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Edit Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label>Full Name</label>
                            <input type="text" name="full_name"
                                value="{{ $request->full_name }}"
                                class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label>Age</label>
                            <input type="number" name="age"
                                value="{{ $request->age }}"
                                class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label>Gender</label>
                            <input type="text" name="gender"
                                value="{{ $request->gender }}"
                                class="form-control">
                        </div>

                        <div class="col-md-12">
                            <label>Address</label>
                            <input type="text" name="address"
                                value="{{ $request->address }}"
                                class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label>Document Type</label>
                            <input type="text" name="document_type"
                                value="{{ $request->document_type }}"
                                class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label>Purpose</label>
                            <input type="text" name="purpose"
                                value="{{ $request->purpose }}"
                                class="form-control">
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button class="btn btn-success">
                        Update
                    </button>
                </div>

            </form>
        </div>
    </div>
    @endforeach

    <!-- LIVE SEARCH SCRIPT -->
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