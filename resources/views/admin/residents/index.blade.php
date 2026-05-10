<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Population Management</h2>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="card shadow-sm">
            <div class="card-body">

                <!-- TABLE -->
                <div class="table-responsive">
                    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addResidentModal">
                        + Add Resident
                    </button>
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Suffix</th>
                                <th>Purok</th>
                                <th>Household</th>
                                <th>Sex</th>
                                <th>Birth Date</th>
                                <th>Age</th>
                                <th>Civil Status</th>
                                <th>Voter</th>
                                <th>PWD</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody id="tableBody">
                            @include('admin.residents.partials.rows')
                        </tbody>
                    </table>

                    <div id="pagination" class="mt-3">
                        {{ $residents->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Modal to show resident details --}}
    <div class="modal fade" id="residentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Resident Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" id="residentDetails">
                    Loading...
                </div>

            </div>
        </div>
    </div>

    {{-- Modal for adding new resident --}}
    <div class="modal fade" id="addResidentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <form method="POST" action="{{ route('residents.store') }}">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Add Resident</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="row">

                            <div class="col-md-4">
                                <label>First Name</label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>

                            <div class="col-md-4">
                                <label>Middle Name</label>
                                <input type="text" name="middle_name" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label>Last Name</label>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>

                            <div class="col-md-3 mt-2">
                                <label>Suffix</label>
                                <input type="text" name="suffix" class="form-control">
                            </div>

                            <div class="col-md-3 mt-2">
                                <label>Sex</label>
                                <select name="sex" class="form-control">
                                    <option>Male</option>
                                    <option>Female</option>
                                </select>
                            </div>

                            <div class="col-md-3 mt-2">
                                <label>Birth Date</label>
                                <input type="date" name="birth_date" class="form-control">
                            </div>

                            <div class="col-md-3 mt-2">
                                <label>Civil Status</label>
                                <select name="civil_status" class="form-control">
                                    <option>Single</option>
                                    <option>Married</option>
                                    <option>Widow</option>
                                    <option>Separated</option>
                                </select>
                            </div>

                            <div class="col-md-4 mt-2">
                                <label>Purok</label>
                                <input type="text" name="purok" class="form-control">
                            </div>

                            <div class="col-md-4 mt-2">
                                <label>House Number</label>
                                <input type="text" name="house_number" class="form-control">
                            </div>

                            <div class="col-md-4 mt-2">
                                <label>Street</label>
                                <input type="text" name="street" class="form-control">
                            </div>

                            <div class="col-md-6 mt-2">
                                <label>Household Name</label>
                                <input type="text" name="household_name" class="form-control">
                            </div>

                            <div class="col-md-6 mt-2">
                                <label>Relationship to Head</label>
                                <select name="relationship_to_head" class="form-control">
                                    <option>Head</option>
                                    <option>Spouse</option>
                                    <option>Child</option>
                                    <option>Relative</option>
                                </select>
                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Save Resident
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    

   <script>

let timer;

// ================= FETCH DATA =================
function fetchData(page = 1, search = '') {

    fetch(`{{ route('residents.fetch') }}?page=${page}&search=${search}`)
        .then(res => res.json())
        .then(data => {

            document.getElementById('tableBody').innerHTML = data.html;
            document.getElementById('pagination').innerHTML = data.pagination;

            attachPagination();
        });
}

// ================= SEARCH =================
document.getElementById('searchInput').addEventListener('keyup', function () {

    clearTimeout(timer);

    timer = setTimeout(() => {
        fetchData(1, this.value);
    }, 300);
});

// ================= PAGINATION =================
function attachPagination() {

    document.querySelectorAll('#pagination a').forEach(link => {

        link.addEventListener('click', function (e) {
            e.preventDefault();

            let page = this.href.split('page=')[1];
            let search = document.getElementById('searchInput').value;

            fetchData(page, search);
        });

    });
}

document.addEventListener("click", function (e) {

    let row = e.target.closest(".resident-row");

    if (!row) return;

    let id = row.dataset.id;

    fetch(`/admin/residents/${id}`)
        .then(res => res.json())
        .then(data => {

            document.getElementById('residentDetails').innerHTML = data.html;

            let modal = new bootstrap.Modal(document.getElementById('residentModal'));
            modal.show();
        });
});

attachPagination();

</script>

</x-app-layout>