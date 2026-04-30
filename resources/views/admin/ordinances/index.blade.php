<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Ordinances</h2>

            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOrdinanceModal">
                Add Ordinance
            </button>
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
                <div class="mb-3">
                    <input type="text"
                        id="searchInput"
                        class="form-control"
                        placeholder="Search ordinance no, title, or description...">
                </div>

                <!-- TABLE -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle" id="ordinancesTable">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Ordinance No</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($ordinances as $ordinance)
                                <tr>
                                    <td>{{ $ordinance->id }}</td>
                                    <td class="text-capitalize">{{ $ordinance->ordinance_no }}</td>
                                    <td>{{ $ordinance->title }}</td>
                                    <td>{{ $ordinance->description }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editOrdinanceModal{{ $ordinance->id }}">
                                            Edit
                                        </button>
                                        <form action="{{ route('ordinances.destroy', $ordinance->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
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

    <!-- ADD ORDINANCE MODAL -->
    <div class="modal fade" id="addOrdinanceModal" tabindex="-1" aria-labelledby="addOrdinanceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('ordinances.store') }}" method="POST" class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="addOrdinanceModalLabel">Add Ordinance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Ordinance No</label>
                            <input type="text" name="ordinance_no" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Save Ordinance
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // LIVE SEARCH
        document.getElementById('searchInput').addEventListener('keyup', function () {
            let value = this.value.toLowerCase();
            let rows = document.querySelectorAll("#ordinancesTable tbody tr");

            rows.forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
            });
        });
    </script>
</x-app-layout>