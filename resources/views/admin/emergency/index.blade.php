<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Emergency Contacts</h2>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="card shadow-sm">
            <div class="card-body">

                <div class="d-flex justify-content-end align-items-center mb-3">
                    <button class="btn btn-success btn-sm d-flex align-items-center gap-2"
                        data-bs-toggle="modal"
                        data-bs-target="#createEmergencyModal">
                        <i class="bi bi-telephone"></i>
                        Add Contacts
                    </button>
                </div>

                <!-- TABLE -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Active</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody id="tableBody">
                            @include('admin.emergency.partials.rows')
                        </tbody>
                    </table>

                    <div id="pagination" class="mt-3">
                        {{ $emergency->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Show validation errors and automatically reopen the modal --}}
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modal = new bootstrap.Modal(
                    document.getElementById('createOrdinanceModal')
                );
                modal.show();
            });
        </script>
    @endif

    <!-- EDIT Contact MODAL -->
        @foreach ($emergency as $item)
            <div class="modal fade" id="editEmergencyModal{{ $item->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form action="{{ route('emergency.update', $item->id) }}" method="POST" class="modal-content">
                        @csrf
                        @method('PUT')

                        <div class="modal-header">
                            <h5 class="modal-title">Edit Emergency Contact</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            <!-- NAME -->
                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text"
                                    name="name"
                                    value="{{ old('name', $item->name) }}"
                                    class="form-control"
                                    required>
                            </div>

                            <!-- NUMBER -->
                            <div class="mb-3">
                                <label>Phone Number</label>
                                <input type="text"
                                    name="phone_number"
                                    value="{{ old('phone_number', $item->phone_number) }}"
                                    class="form-control"
                                    required>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>

                    </form>
                </div>
            </div>
        @endforeach

    <!-- CREATE Contact MODAL -->
       <div class="modal fade" id="createEmergencyModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('emergency.store') }}" method="POST" class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Add Emergency Contact</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <!-- NAME -->
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <!-- NUMBER -->
                    <div class="mb-3">
                        <label>Phone Number</label>
                        <input type="text" name="phone_number" class="form-control" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>

            </form>
        </div>
    </div>

    <!-- ========================================= -->
    <!-- SCRIPTS -->
    <!-- ========================================= -->

    <script>

        let timer;

        // =========================================
        // FETCH DATA
        // =========================================
        function fetchData(page = 1, search = '') {

            fetch(`{{ route('emergency.fetch') }}?page=${page}&search=${search}`)
                .then(res => res.json())
                .then(data => {

                    document.getElementById('tableBody').innerHTML = data.html;

                    document.getElementById('pagination').innerHTML = data.pagination;

                    attachPagination();

                });

        }


        // =========================================
        // LIVE SEARCH
        // =========================================
        document.getElementById('searchInput')
            .addEventListener('keyup', function () {

                clearTimeout(timer);

                timer = setTimeout(() => {

                    fetchData(1, this.value);

                }, 300);

            });


        // =========================================
        // PAGINATION
        // =========================================
        function attachPagination() {

            document.querySelectorAll('#pagination a')
                .forEach(link => {

                    link.addEventListener('click', function (e) {

                        e.preventDefault();

                        let page = this.href.split('page=')[1];

                        let search =
                            document.getElementById('searchInput').value;

                        fetchData(page, search);

                    });

                });

        }

        attachPagination();


        // =========================================
        // DELETE ORDINANCES
        // =========================================
        document.addEventListener("click", async function (e) {

            const btn = e.target.closest(".delete-btn");

            if (btn) {

                let id = btn.dataset.id;

                if (!confirm("Are you sure you want to delete this contact?")) {
                    return;
                }

                try {

                    let res = await fetch(`/admin/emergency/${id}`, {

                        method: "DELETE",

                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        }

                    });

                    let data = await res.json();

                    if (data.success) {

                        btn.closest("tr").remove();

                         showToast("Contact deleted successfully", "success");
                         // get current page
                        let currentPage =
                            new URLSearchParams(window.location.search).get('page') || 1;

                        // reload table
                        let search = document.getElementById('searchInput')?.value ?? '';

                        fetchData(currentPage, search)

                    } else {

                         showToast("Failed to delete Contact", "danger");

                    }

                } catch (err) {

                    console.error(err);

                     showToast("An error occurred", "danger");

                }

            }

        });

    </script>

</x-app-layout>