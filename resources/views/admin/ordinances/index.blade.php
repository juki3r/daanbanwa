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
                                <th>Ordinance No</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody id="tableBody">
                            @include('admin.ordinances.partials.rows')
                        </tbody>
                    </table>

                    <div id="pagination" class="mt-3">
                        {{ $ordinances->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>

<!-- ========================================= -->
    <!-- CREATE ORDINANCE MODAL -->
    <!-- ========================================= -->

    <div class="modal fade" id="createOrdinanceModal" tabindex="-1">

        <div class="modal-dialog modal-lg">

            <form action="{{ route('ordinances.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="modal-content">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">
                        Create Ordinance
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-12">

                            <label class="form-label">
                                Ordinance No
                            </label>

                            <input type="text"
                                   name="ordinance_no"
                                   class="form-control text-capitalize"
                                   required>

                        </div>

                        <!-- TITLE -->
                        <div class="col-12">

                            <label class="form-label">
                                Title
                            </label>

                            <input type="text"
                                   name="title"
                                   class="form-control text-capitalize"
                                   required>

                        </div>

                        <!-- CONTENT -->
                        <div class="col-12">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea name="description"
                                      rows="5"
                                      class="form-control"
                                      required></textarea>

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
                            class="btn btn-success">
                        Create Ordinance
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

            fetch(`{{ route('ordinances.fetch') }}?page=${page}&search=${search}`)
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

                if (!confirm("Are you sure you want to delete this ordinance?")) {
                    return;
                }

                try {

                    let res = await fetch(`/admin/ordinances/${id}`, {

                        method: "DELETE",

                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        }

                    });

                    let data = await res.json();

                    if (data.success) {

                        btn.closest("tr").remove();

                         showToast("Ordinances deleted successfully", "success");
                         // get current page
                        let currentPage =
                            new URLSearchParams(window.location.search).get('page') || 1;

                        // reload table
                        let search = document.getElementById('searchInput')?.value ?? '';

                        fetchData(currentPage, search)

                    } else {

                         showToast("Failed to delete ordinances", "danger");

                    }

                } catch (err) {

                    console.error(err);

                     showToast("An error occurred", "danger");

                }

            }

        });

    </script>

</x-app-layout>