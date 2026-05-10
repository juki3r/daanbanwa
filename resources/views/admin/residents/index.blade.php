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
                                <th>Actions</th>
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

    {{-- ADD RESIDENT MODAL --}}
    <div class="modal fade" id="addResidentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <form method="POST" id="residentForm" action="{{ route('residents.store') }}">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Add Resident</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="row">
                            <input type="hidden" name="id" id="resident_id">

                            {{-- FIRST NAME --}}
                            <div class="col-md-4">
                                <label>First Name <span class="required-star">*</span></label>
                                <input type="text" name="first_name"
                                    class="form-control required-field text-upper" required>
                            </div>

                            {{-- MIDDLE NAME --}}
                            <div class="col-md-4">
                                <label>Middle Name</label>
                                <input type="text" name="middle_name"
                                    class="form-control text-upper">
                            </div>

                            {{-- LAST NAME --}}
                            <div class="col-md-4">
                                <label>Last Name <span class="required-star">*</span></label>
                                <input type="text" name="last_name"
                                    class="form-control required-field text-upper" required>
                            </div>

                            {{-- SUFFIX --}}
                            <div class="col-md-3 mt-2">
                                <label>Suffix</label>
                                <input type="text" name="suffix"
                                    class="form-control text-upper">
                            </div>

                            {{-- SEX --}}
                            <div class="col-md-3 mt-2">
                                <label>Sex <span class="required-star">*</span></label>
                                <select name="sex" class="form-control required-field" required>
                                    <option value="">-- Select --</option>
                                    <option>Male</option>
                                    <option>Female</option>
                                </select>
                            </div>

                            {{-- BIRTH DATE --}}
                            <div class="col-md-3 mt-2">
                                <label>Birth Date <span class="required-star">*</span></label>
                                <input type="date" name="birth_date"
                                    class="form-control required-field" required>
                            </div>

                            {{-- CIVIL STATUS --}}
                            <div class="col-md-3 mt-2">
                                <label>Civil Status <span class="required-star">*</span></label>
                                <select name="civil_status" class="form-control required-field" required>
                                    <option value="">-- Select --</option>
                                    <option>Single</option>
                                    <option>Married</option>
                                    <option>Widow</option>
                                    <option>Separated</option>
                                </select>
                            </div>

                            {{-- PUROK --}}
                            <div class="col-md-4 mt-2">
                                <label>Purok <span class="required-star">*</span></label>
                                <input type="text" name="purok"
                                    class="form-control required-field" required>
                            </div>

                            {{-- HOUSE NUMBER --}}
                            <div class="col-md-4 mt-2">
                                <label>House Number <span class="required-star">*</span></label>
                                <input type="text" name="house_number"
                                    class="form-control required-field" required>
                            </div>

                            {{-- STREET --}}
                            <div class="col-md-4 mt-2">
                                <label>Street</label>
                                <input type="text" name="street" class="form-control">
                            </div>

                            {{-- HOUSEHOLD --}}
                            <div class="col-md-6 mt-2">
                                <label>Household Name <span class="required-star">*</span></label>
                                <input type="text" name="household_name"
                                    class="form-control required-field text-upper" required>
                            </div>

                            {{-- RELATIONSHIP --}}
                            <div class="col-md-6 mt-2">
                                <label>Relationship to Head <span class="required-star">*</span></label>
                                <select name="relationship_to_head"
                                    class="form-control required-field" required>
                                    <option value="">-- Select --</option>
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
                        <button type="submit" id="submitBtn" class="btn btn-primary" disabled>
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



const form = document.querySelector('#addResidentModal form');
const submitBtn = document.getElementById('submitBtn');
const requiredFields = form.querySelectorAll('.required-field');

/* ================= REAL-TIME VALIDATION ================= */
function validateForm() {
    let valid = true;

    requiredFields.forEach(field => {

        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            valid = false;
        } else {
            field.classList.remove('is-invalid');
        }

    });

    submitBtn.disabled = !valid;
}

/* ================= EVENT LISTENERS ================= */
requiredFields.forEach(field => {
    field.addEventListener('input', validateForm);
    field.addEventListener('change', validateForm);
});

/* ================= AUTO UPPERCASE ================= */
document.querySelectorAll('.text-upper').forEach(input => {
    input.addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });
});

/* ================= AGE CALCULATION ================= */
document.querySelector('input[name="birth_date"]').addEventListener('change', function () {

    let birthDate = new Date(this.value);
    let today = new Date();

    let age = today.getFullYear() - birthDate.getFullYear();
    let m = today.getMonth() - birthDate.getMonth();

    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }

    console.log("Age:", age);
});


/* ================= ROW CLICK (CLEAN FIX) ================= */
document.addEventListener("click", function (e) {

    if (e.target.closest(".edit-btn") || e.target.closest(".delete-btn")) {
        return;
    }

    let row = e.target.closest(".resident-row");
    if (!row) return;

    let id = row.dataset.id;

    fetch(`/admin/residents/${id}`)
        .then(res => res.json())
        .then(data => {

            document.getElementById('residentDetails').innerHTML = data.html;

            new bootstrap.Modal(document.getElementById('residentModal')).show();
        });
});

/* ================= DELETE ================= */
document.addEventListener("click", function (e) {

    let btn = e.target.closest(".delete-btn");
    if (!btn) return;

    let id = btn.dataset.id;

    if (!confirm("Delete this resident?")) return;

    fetch(`/admin/residents/${id}`, {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            btn.closest("tr").remove();
        }
    });

});

/* ================= EDIT ================= */
document.addEventListener("click", function (e) {

    let btn = e.target.closest(".edit-btn");
    if (!btn) return;

    let id = btn.dataset.id;

    fetch(`/admin/residents/${id}`)
        .then(res => res.json())
        .then(resident => {

            // OPEN MODAL
            let modal = new bootstrap.Modal(document.getElementById('addResidentModal'));
            modal.show();

            // CHANGE FORM TO UPDATE MODE
            document.getElementById('residentForm').action = `/admin/residents/edit/${id}`;
            document.getElementById('residentForm').insertAdjacentHTML(
                'beforeend',
                '<input type="hidden" name="_method" value="PUT">'
            );

            // FILL FIELDS
            document.querySelector('input[name="first_name"]').value = resident.first_name;
            document.querySelector('input[name="middle_name"]').value = resident.middle_name ?? '';
            document.querySelector('input[name="last_name"]').value = resident.last_name;
            document.querySelector('input[name="suffix"]').value = resident.suffix ?? '';
            document.querySelector('select[name="sex"]').value = resident.sex;
            document.querySelector('input[name="birth_date"]').value = resident.birth_date;
            document.querySelector('select[name="civil_status"]').value = resident.civil_status;
            document.querySelector('input[name="purok"]').value = resident.purok;
            document.querySelector('input[name="house_number"]').value = resident.house_number;
            document.querySelector('input[name="street"]').value = resident.street ?? '';
            document.querySelector('input[name="household_name"]').value = resident.household_name;
            document.querySelector('select[name="relationship_to_head"]').value = resident.relationship_to_head;

        });

});

document.getElementById('addResidentModal').addEventListener('hidden.bs.modal', function () {

    document.getElementById('residentForm').reset();

    document.getElementById('residentForm').action = "{{ route('residents.store') }}";

    // remove PUT method if exists
    let method = document.querySelector('input[name="_method"]');
    if (method) method.remove();
});


/* ================= INITIAL CHECK ================= */
validateForm();

attachPagination();

</script>

</x-app-layout>