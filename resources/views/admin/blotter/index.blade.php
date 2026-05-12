<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Blotter Management</h2>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="card shadow-sm">
            <div class="card-body">

                <!-- TABLE -->
                <div class="table-responsive">
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-success btn-sm d-flex align-items-center gap-2"
                            data-bs-toggle="modal"
                            data-bs-target="#addBlotterModal">
                            <i class="bi bi-journal-plus"></i>
                            Add Blotter
                        </button>
                    </div>
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Case #</th>
                                <th>Name</th>
                                <th>Statement</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody id="tableBody">
                            @include('admin.blotter.partials.rows')
                        </tbody>
                    </table>

                    <div id="pagination" class="mt-3">
                        {{ $blotters->links() }}
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

    <div class="modal fade" id="addBlotterModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="addBlotterForm" class="modal-content">

                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Add Blotter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-2">
                        <label>Complainant Name</label>
                        <input type="text" name="complainant_name" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label>Statement</label>
                        <textarea name="statement" class="form-control" required></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-success">Submit</button>
                </div>

            </form>
        </div>
    </div>


    <!-- LIVE SEARCH -->
    <script>
    
    let timer;

    // ================= FETCH DATA =================
    function fetchData(page = 1, search = '') {

        fetch(`{{ route('blotters.fetch') }}?page=${page}&search=${search}`)
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

        document.getElementById("requestId").value = btn.dataset.id;

        const select = document.getElementById("statusSelect");

        // reset order + force Approved first
        select.innerHTML = `
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
        `;

        // ALWAYS set Approved as selected
        select.value = "approved";

        bootstrap.Modal.getOrCreateInstance(
            document.getElementById("statusModal")
        ).show();

    });

   // ================= UPDATE STATUS =================
    document.getElementById("statusForm").addEventListener("submit", async function (e) {
        e.preventDefault();

        const id = document.getElementById("requestId").value;
        const formData = new FormData(this);

        const res = await fetch(
            `{{ route('blotters.updateStatus', ['id' => '__ID__']) }}`.replace('__ID__', id),
            {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "X-HTTP-Method-Override": "PUT",
                    "Accept": "application/json"
                },
                body: formData
            }
        );

        const data = await res.json();

        if (data.success) {

            let badge = document.getElementById(`status-badge-${id}`);

            if (badge) {
                badge.innerText =
                    data.status.charAt(0).toUpperCase() + data.status.slice(1);

                const classMap = {
                    approved: "bg-success text-white",
                    rejected: "bg-danger text-white"
                };

                badge.className =
                    "badge " + (classMap[data.status] || "bg-secondary");
            }

            // Remove unread highlight
            const row = document.getElementById(`blotter-row-${id}`);
            if (row) {
                row.classList.remove('table-warning');
            }

            // Close modal (works even if getInstance() returns null)
            const modalEl = document.getElementById("statusModal");
            const modal = bootstrap.Modal.getInstance(modalEl)
                        || bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.hide();

            showToast("Status updated", "success");

        } else {
            let badge = document.getElementById(`status-badge-${id}`);

            if (badge) {
                badge.innerText =
                    data.status.charAt(0).toUpperCase() + data.status.slice(1);

                const classMap = {
                    approved: "bg-success text-white",
                    rejected: "bg-danger text-white"
                };

                badge.className =
                    "badge " + (classMap[data.status] || "bg-secondary");
            }

            // Remove unread highlight
            const row = document.getElementById(`blotter-row-${id}`);
            if (row) {
                row.classList.remove('table-warning');
            }
            // Close modal (works even if getInstance() returns null)
            const modalEl = document.getElementById("statusModal");
            const modal = bootstrap.Modal.getInstance(modalEl)
                        || bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.hide()
            showToast(data.message, "danger");
        }
    });


    document.addEventListener("click", async function (e) {

        if (e.target.classList.contains("delete-btn")) {

            let id = e.target.dataset.id;

            if (!confirm("Are you sure you want to delete this blotter?")) return;

            try {
                let res = await fetch(`/admin/blotters/${id}`, {
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

    document.getElementById("addBlotterForm").addEventListener("submit", async function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        const res = await fetch(`{{ route('blotters.store') }}`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: formData
        });

        const data = await res.json();

        if (data.success) {

            showToast(data.message, "success");

            // close modal
            bootstrap.Modal.getInstance(
                document.getElementById("addBlotterModal")
            ).hide();

            // reset form
            this.reset();

            // refresh table
            fetchData(1, document.getElementById('searchInput')?.value ?? '');

        } else {
            showToast("Failed to create blotter", "danger");
        }
    });
</script>

</x-app-layout>