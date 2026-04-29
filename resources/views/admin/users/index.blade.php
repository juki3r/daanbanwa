<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">
            Users Management
        </h2>
    </x-slot>

    <div class="container py-4">

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <input type="text"
                        id="searchInput"
                        class="form-control"
                        placeholder="Search name or phone...">
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle" id="usersTable">

                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td class="text-capitalize">{{ $user->first_name }} {{ $user->last_name }}</td>
                                    <td>{{ $user->phone }}</td>

                                    <td>
                                        @if($user->role === 'admin')
                                            <span class="badge bg-danger">Admin</span>
                                        @elseif($user->role === 'resident')
                                            <span class="badge bg-success">Resident</span>
                                        @else
                                            <span class="badge bg-secondary">
                                                {{ $user->role }}
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#sendNotificationModal" onclick="prepareNotification({{ $user->id }})">
                                            Send Notification
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                    <!-- Modal -->
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
                                        <label>Title</label>
                                        <input type="text" id="title" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label>Body</label>
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












                    <script>
                        document.getElementById('searchInput').addEventListener('keyup', function () {
                            let value = this.value.toLowerCase();
                            let rows = document.querySelectorAll("#usersTable tbody tr");

                            rows.forEach(row => {
                                let text = row.innerText.toLowerCase();
                                row.style.display = text.includes(value) ? "" : "none";
                            });
                        });

                        let modal = new bootstrap.Modal(document.getElementById('notifModal'));

                        function openModal(id) {
                            document.getElementById('userId').value = id;
                            modal.show();
                        }

                        document.getElementById('notifForm').addEventListener('submit', function(e) {
                            e.preventDefault();

                            let id = document.getElementById('userId').value;
                            let title = document.getElementById('title').value;
                            let body = document.getElementById('body').value;

                            fetch(`/send-to-one/${id}`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ title, body })
                            })
                            .then(res => res.json())
                            .then(data => {
                                alert(data.message);
                                modal.hide();
                            })
                            .catch(err => {
                                alert('Error sending notification');
                            });
                        });
                    </script>
                </div>

            </div>
        </div>

    </div>
</x-app-layout>