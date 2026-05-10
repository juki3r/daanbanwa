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

                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Name</th>
                            </tr>
                        </thead>

                        <tbody id="tableBody">
                            @include('admin.residents.partials.rows')
                        </tbody>
                    </table>

                    {{-- <div id="pagination" class="mt-3">
                        {{ $residents->links() }}
                    </div> --}}
                </div>

            </div>
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