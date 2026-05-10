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

attachPagination();

</script>

</x-app-layout>