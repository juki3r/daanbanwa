<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Concern Management</h2>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="card shadow-sm">
            <div class="card-body">


                <!-- TABLE -->
                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Submitted By</th>
                                <th>Title</th>
                                <th>Location</th>
                                <th>Descriptions</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody id="tableBody">
                            @include('admin.concern.partials.rows')
                        </tbody>
                    </table>

                    <div id="pagination" class="mt-3">
                        {{ $concerns->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- STATUS MODALS -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog">

            <form class="modal-content" id="statusForm">
                @csrf
                @method('PUT')

                <input type="hidden" id="concernId">

                <div class="modal-header">
                    <h5 class="modal-title">Update Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <select name="status" id="statusSelect" class="form-select">
                        <option value="received">Received</option>
                        <option value="under_review">Under Review</option>
                        <option value="in_progress">In Progress</option>
                        <option value="resolved">Resolved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">Save</button>
                </div>

            </form>

        </div>
    </div>


    <!-- LIVE SEARCH -->
    <script>
        let timer;

        // ---------------- FETCH DATA ----------------
        function fetchData(page = 1, search = '') {

            fetch(`{{ route('concern.fetch') }}?page=${page}&search=${search}`)
                .then(res => res.json())
                .then(data => {

                    document.getElementById('tableBody').innerHTML = data.html;
                    document.getElementById('pagination').innerHTML = data.pagination;

                    attachPagination();
                    attachStatusForms();

                });

        }

        // // ---------------- SEARCH ----------------
        document.getElementById('searchInput').addEventListener('keyup', function() {

            clearTimeout(timer);

            timer = setTimeout(() => {
                fetchData(1, this.value);
            }, 300);

        });

        // ---------------- PAGINATION ----------------
        function attachPagination() {

            document.querySelectorAll('#pagination a').forEach(link => {

                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    let page = this.href.split('page=')[1];
                    let search = document.getElementById('searchInput').value;

                    fetchData(page, search);
                });

            });

        }

        document.getElementById("statusForm").addEventListener("submit", async function (e) {
            e.preventDefault();

            const id = document.getElementById("concernId").value;
            const formData = new FormData(this);

            const res = await fetch(`/admin/concerns/${id}/status`, {
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

                let badge = document.getElementById(`status-badge-${id}`);

                if (badge) {
                    badge.innerText = data.status
                        .replaceAll('_', ' ')
                        .replace(/\b\w/g, l => l.toUpperCase());

                    badge.className = "badge";

                    let classMap = {
                            submitted: "bg-warning text-dark",
                            received: "bg-secondary text-white",
                            under_review: "bg-primary text-white",
                            in_progress: "bg-success text-white",
                            resolved: "bg-info text-dark",
                            rejected: "bg-danger text-white"
                        };

                        badge.className = "badge " + (classMap[data.status] || "bg-danger");
                }

                bootstrap.Modal.getInstance(document.getElementById("statusModal")).hide();

                showToast("Status updated", "success");
            }
        });


        document.addEventListener("click", function (e) {

            const btn = e.target.closest(".open-status");
            if (!btn) return;

            document.getElementById("concernId").value = btn.dataset.id;
            document.getElementById("statusSelect").value = btn.dataset.status;

            bootstrap.Modal.getOrCreateInstance(
                document.getElementById("statusModal")
            ).show();

        });

        // INIT
        attachPagination();
        attachStatusForms();


        document.addEventListener("click", async function (e) {

            const btn = e.target.closest(".delete-btn");
            if (!btn) return;

            let id = btn.dataset.id;

            if (!confirm("Are you sure you want to delete this concern?")) return;

            try {
                let res = await fetch(`/admin/concerns/${id}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    }
                });

                let data = await res.json();

                if (data.success) {

                    btn.closest("tr").remove();

                    showToast(data.message ?? "Deleted", "success");

                    let currentPage =
                        new URLSearchParams(window.location.search).get('page') || 1;

                    let search = document.getElementById('searchInput')?.value ?? '';

                    fetchData(currentPage, search);

                } else {
                    showToast(data.message ?? "Delete failed", "danger");
                }

            } catch (err) {
                console.error(err);
                showToast("Something went wrong", "danger");
            }
        });
    </script>

</x-app-layout>