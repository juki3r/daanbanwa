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

                                    <td style="min-width: 320px;">

                                    <div class="d-flex justify-content-between align-items-start">

                                        <div>
                                            {{-- USER --}}
                                            <div class="fw-bold text-dark">
                                                {{ $notif['user'] ?? 'Unknown User' }}
                                            </div>

                                            {{-- TITLE --}}
                                            <div class="mt-1">
                                                {{ $notif['title'] }}
                                            </div>

                                            {{-- SUBTITLE --}}
                                            <small class="text-muted">
                                                {{ $notif['subtitle'] }}
                                            </small>

                                            {{-- DATE --}}
                                            <div class="mt-2">
                                                <small class="text-secondary">
                                                    {{ $notif['created_at']->format('M d, Y h:i A') }}
                                                </small>
                                            </div>
                                        </div>

                                        {{-- TYPE BADGE --}}
                                        <div>
                                            @if($notif['type'] === 'request')
                                                <span class="badge bg-primary">
                                                    Request
                                                </span>

                                            @elseif($notif['type'] === 'concern')
                                                <span class="badge bg-warning text-dark">
                                                    Concern
                                                </span>

                                            @elseif($notif['type'] === 'blotter')
                                                <span class="badge bg-danger">
                                                    Blotter
                                                </span>
                                            @endif
                                        </div>

                                    </div>

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