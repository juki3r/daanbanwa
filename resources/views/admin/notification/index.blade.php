<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Notifications</h2>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="card shadow-sm">
            <div class="card-body">

                <!-- TABLE -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle" id="requestTable">

                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Details</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($notifications as $notif)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        <strong>{{ $notif['user'] ?? 'Unknown User' }}</strong><br>

                                        {{-- TYPE BADGE --}}
                                        @if($notif['type'] === 'request')
                                            <span class="badge bg-primary mb-1">
                                                REQUEST
                                            </span>
                                            <br>
                                            📄 {{ $notif['title'] }}

                                        @elseif($notif['type'] === 'concern')
                                            <span class="badge bg-warning text-dark mb-1">
                                                CONCERN
                                            </span>
                                            <br>
                                            ⚠️ {{ $notif['title'] }}

                                        @elseif($notif['type'] === 'blotter')
                                            <span class="badge bg-danger mb-1">
                                                BLOTTER
                                            </span>
                                            <br>
                                            📁 {{ $notif['title'] }}
                                        @endif

                                        <br>

                                        <small class="text-muted">
                                            {{ $notif['subtitle'] }}
                                        </small>

                                        <br>

                                        <small class="text-secondary">
                                            {{ $notif['created_at']->format('M d, Y h:i A') }}
                                        </small>
                                    </td>

                                    <td class="text-center">
                                        <form method="POST"
                                            action="{{ route('notifications.markAsRead', [$notif['type'], $notif['id']]) }}">
                                            @csrf

                                            <button class="btn btn-sm btn-success">
                                                Mark as Read
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">
                                        No notifications found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>

                    <!-- PAGINATION -->
                    <div class="mt-3">
                        {{ $notifications->links() }}
                    </div>

                </div>

            </div>
        </div>
    </div>

</x-app-layout>