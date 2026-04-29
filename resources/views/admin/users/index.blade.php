<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">
            Users Management
        </h2>
    </x-slot>

    <div class="container py-4">

        <div class="card shadow-sm">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Created</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td class="text-uppercase">{{ $user->first_name }} {{ $user->last_name }}</td>
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

                                    <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

    </div>
</x-app-layout>