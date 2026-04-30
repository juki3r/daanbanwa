<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">
            Council Management
        </h2>
    </x-slot>

    <div class="container py-4">

        <div class="card shadow-sm">
            <div class="card-body">

                <!-- SEARCH -->
                <div class="mb-3">
                    <input type="text"
                        id="searchInput"
                        class="form-control"
                        placeholder="Search name or phone...">
                </div>

                <!-- TABLE -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle" id="usersTable">

                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Position</th>
                                <th>Assignment</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($officials as $official)
                                <tr>
                                    <td>{{ $official->id }}</td>

                                    <td class="text-capitalize">
                                        {{ $official->name }}
                                    </td>

                                    <td>{{ $official->phone_number }}</td>

                                    <td>
                                        {{ $official->position }}
                                    </td>

                                    <td>
                                        {{ $official->assignment }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- ================= SCRIPT ================= -->
    <script>
        // LIVE SEARCH
        document.getElementById('searchInput').addEventListener('keyup', function () {
            let value = this.value.toLowerCase();
            let rows = document.querySelectorAll("#usersTable tbody tr");

            rows.forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
            });
        });
    </script>

</x-app-layout>