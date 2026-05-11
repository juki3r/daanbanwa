<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">
            Users Management
        </h2>
    </x-slot>

    <div class="container py-4">

        <div class="card shadow-sm">
            <div class="card-body">

                <!-- TABLE -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle" id="usersTable">

                        <thead class="table-dark">
                            <tr>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Phone</th>
                                <th>Phone Verified</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody  id="tableBody">
                            @include('admin.users.partials.rows')
                        </tbody>

                    </table>
                    <div id="pagination" class="mt-3">
                        {{ $users->links() }}
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- ================= MODAL ================= -->
    <div class="modal fade" id="notifModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="notifForm" class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Send Notification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="userId">

                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" id="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Body</label>
                        <textarea id="body" class="form-control" required></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        Send
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- ================= SCRIPT ================= -->
    <script>
        let timer;

        // ---------------- FETCH DATA ----------------
        function fetchData(page = 1, search = '') {

            fetch(`{{ route('users.fetch') }}?page=${page}&search=${search}`)
                .then(res => res.json())
                .then(data => {

                    document.getElementById('tableBody').innerHTML = data.html;
                    document.getElementById('pagination').innerHTML = data.pagination;

                    attachPagination();
                    // attachStatusForms();

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


        // BOOTSTRAP MODAL
        let modal = new bootstrap.Modal(document.getElementById('notifModal'));

        function openModal(id) {
            document.getElementById('userId').value = id;
            document.getElementById('title').value = '';
            document.getElementById('body').value = '';
            modal.show();
        }

        // SEND NOTIFICATION
        document.getElementById('notifForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let id = document.getElementById('userId').value;
            let title = document.getElementById('title').value;
            let body = document.getElementById('body').value;

            fetch(`/send-to-one/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ title, body })
            })
            .then(async res => {
                let data = await res.json().catch(() => null);

                if (!res.ok) {
                    throw data || { message: 'Server error' };
                }

                return data;
            })
            .then(data => {
                alert(data.message);
                modal.hide();
            })
            .catch(err => {
                console.log(err);
                alert(err.message || 'Error sending notification');
            });
        });

        /* ================= VERIFY PHONE (LIVE UPDATE) ================= */
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.verify-btn');
            if (!btn) return;

            const id = btn.dataset.id;

            if (!confirm('Mark this user as verified?')) return;

            fetch(`/admin/users/${id}/verify`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast('User verified successfully', 'success');

                    // Find the current row
                    const row = btn.closest('tr');

                    // Update verification badge
                    const statusCell = row.children[4]; // 5th column = verification status
                    statusCell.innerHTML = `
                        <span class="badge bg-success">
                            <i class="bi bi-check-circle-fill me-1"></i> Verified
                        </span>
                    `;

                    // Remove Verify button
                    btn.remove();
                }
            })
            .catch(() => {
                showToast('Failed to verify user', 'danger');
            });
        });
    </script>

</x-app-layout>