<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Cerificate Management</h2>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="card shadow-sm">
            <div class="card-body">

                <div class="d-flex justify-content-end align-items-center">
                    <button class="btn btn-success btn-sm d-flex align-items-center gap-1"
                        data-bs-toggle="modal"
                        data-bs-target="#createRequestModal">

                        <i class="bi bi-plus-circle"></i>
                        Create Request
                    </button>
                </div>

                <!-- TABLE -->
                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Address</th>
                                <th>Document</th>
                                <th>Purpose</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody id="tableBody">
                            @include('admin.requests.partials.rows')
                        </tbody>
                    </table>

                    <div id="pagination" class="mt-3">
                        {{ $requests->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- ===================== SINGLE STATUS MODAL ===================== -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" id="statusForm">
                @csrf
                @method('PUT')

                <input type="hidden" id="requestId">

                <div class="modal-header">
                    <h5 class="modal-title">Update Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <select name="status" id="statusSelect" class="form-select">
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>

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
    
    let timer;

    // ================= FETCH DATA =================
    function fetchData(page = 1, search = '') {

        fetch(`{{ route('requests.fetch') }}?page=${page}&search=${search}`)
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

    attachPagination();

    // ================= OPEN MODAL =================
        document.addEventListener("click", function (e) {
            const btn = e.target.closest(".open-status");
            if (!btn) return;

            // Set request ID
            document.getElementById("requestId").value = btn.dataset.id;

            // Always default to the first option ("Approved")
            const statusSelect = document.getElementById("statusSelect");
            statusSelect.selectedIndex = 0;

            // Show modal
            bootstrap.Modal.getOrCreateInstance(
                document.getElementById("statusModal")
            ).show();
        });

    // ================= UPDATE STATUS =================
    document.getElementById("statusForm").addEventListener("submit", async function (e) {
        e.preventDefault();

        const id = document.getElementById("requestId").value;
        const formData = new FormData(this);

        const res = await fetch(`/admin/requests/${id}/status`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "X-HTTP-Method-Override": "PUT",
                "Accept": "application/json"
            },
            body: formData
        });

        const data = await res.json();

        if (data.success) {

            // update badge instantly
            let badge = document.getElementById(`status-badge-${id}`);

            if (badge) {
                badge.innerText = data.status.charAt(0).toUpperCase() + data.status.slice(1);

                badge.className = "badge";

                if (data.status === "approved") badge.classList.add("bg-success");
                else if (data.status === "rejected") badge.classList.add("bg-danger");
                else badge.classList.add("bg-warning", "text-dark");
            }
            // ✅ REMOVE UNREAD HIGHLIGHT LIVE
                const row = document.getElementById(`request-row-${id}`);
                if (row) {
                    row.classList.remove('table-warning');
                }

            bootstrap.Modal.getInstance(document.getElementById("statusModal")).hide();

            showToast("Status updated", "success");

        } else {
            showToast("Update failed", "danger");
        }
    });


    // ---------------- BUSINESS FIELDS ----------------
    const documentTypeSelect = document.getElementById('documentTypeSelect');
    const businessFields = document.querySelectorAll('.business-fields');

    documentTypeSelect.addEventListener('change', function () {
        if (this.value === 'Business Clearance') {
            businessFields.forEach(field => field.classList.remove('d-none'));
        } else {
            businessFields.forEach(field => field.classList.add('d-none'));
        }
    });

    document.addEventListener("click", async function (e) {

        if (e.target.classList.contains("delete-btn")) {

            let id = e.target.dataset.id;

            if (!confirm("Are you sure you want to delete this request?")) return;

            try {
                let res = await fetch(`/admin/requests/${id}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    }
                });

                let data = await res.json();

                if (data.success) {

                    // remove row instantly
                    e.target.closest("tr").remove();

                    showToast(data.message ?? "Deleted", "success");
                    // get current page
                    let currentPage =
                        new URLSearchParams(window.location.search).get('page') || 1;

                    // reload table
                    let search = document.getElementById('searchInput')?.value ?? '';

                    fetchData(currentPage, search)

                } else {
                    showToast(data.message ?? "Delete failed", "danger");
                }

            } catch (err) {
                console.error(err);
                showToast("Something went wrong", "danger");
            }
        }

    });
</script>

</x-app-layout>