<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Ordinance Management</h2>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="card shadow-sm">
            <div class="card-body">

                <div class="d-flex justify-content-end align-items-center mb-3">
                    <button class="btn btn-success btn-sm d-flex align-items-center gap-2"
                        data-bs-toggle="modal"
                        data-bs-target="#createOrdinanceModal">
                        <i class="bi bi-newspaper"></i>
                        Create Ordinance
                    </button>
                </div>

                <!-- TABLE -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Assignment</th>
                                <th>Phone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody id="tableBody">
                            @include('admin.officials.partials.rows')
                        </tbody>
                    </table>

                    <div id="pagination" class="mt-3">
                        {{ $officials->links() }}
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

    <!-- CREATE OFFICIAL MODAL -->
        <div class="modal fade" id="createOfficialModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <form action="{{ route('officials.store') }}"
                    method="POST"
                    class="modal-content">

                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Create Official</h5>
                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                        </button>
                    </div>

                    <div class="modal-body">

                        {{-- Validation Errors --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row g-3">

                            <!-- NAME -->
                            <div class="col-12">
                                <label class="form-label">Name</label>
                                <input type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    class="form-control @error('name') is-invalid @enderror"
                                    required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- POSITION -->
                            <div class="col-12 col-md-6">
                                <label class="form-label">Position</label>
                                <select name="position"
                                        class="form-select @error('position') is-invalid @enderror"
                                        required>
                                    <option value="">Select Position</option>
                                    <option>Punong Barangay</option>
                                    <option>Barangay Kagawad</option>
                                    <option>Barangay Secretary</option>
                                    <option>Barangay Treasurer</option>
                                    <option>SK Chairman</option>
                                    <option>SK Kagawad</option>
                                    <option>SK Secretary</option>
                                    <option>SK Treasurer</option>
                                    <option>Chief Tanod</option>
                                </select>

                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- PHONE NUMBER -->
                            <div class="col-12 col-md-6">
                                <label class="form-label">Phone Number</label>
                                <input type="text"
                                    name="phone_number"
                                    value="{{ old('phone_number') }}"
                                    class="form-control @error('phone_number') is-invalid @enderror">
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ASSIGNMENT -->
                            <div class="col-12">
                                <label class="form-label">Assignment</label>
                                <input type="text"
                                    name="assignment"
                                    value="{{ old('assignment') }}"
                                    class="form-control @error('assignment') is-invalid @enderror">
                                @error('assignment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-light"
                                data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit"
                                class="btn btn-primary">
                            Create Official
                        </button>
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

            fetch(`{{ route('officials.fetch') }}?page=${page}&search=${search}`)
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

                if (!confirm("Are you sure you want to delete this official?")) {
                    return;
                }

                try {

                    let res = await fetch(`/admin/officials/${id}`, {

                        method: "DELETE",

                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        }

                    });

                    let data = await res.json();

                    if (data.success) {

                        btn.closest("tr").remove();

                         showToast("Official deleted successfully", "success");
                         // get current page
                        let currentPage =
                            new URLSearchParams(window.location.search).get('page') || 1;

                        // reload table
                        let search = document.getElementById('searchInput')?.value ?? '';

                        fetchData(currentPage, search)

                    } else {

                         showToast("Failed to delete official", "danger");

                    }

                } catch (err) {

                    console.error(err);

                     showToast("An error occurred", "danger");

                }

            }

        });

    </script>

</x-app-layout>