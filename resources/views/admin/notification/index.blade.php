<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Notifications</h2>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="card shadow-sm">
            <div class="card-body">

                <div class="notification-wrapper">

                    @forelse($notifications as $notif)

                        <div class="card shadow-sm border-0 mb-3 notification-card">

                            <div class="card-body">

                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">

                                    {{-- LEFT CONTENT --}}
                                    <div class="flex-grow-1">

                                        {{-- HEADER --}}
                                        <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">

                                            @if($notif['type'] === 'request')
                                                <span class="badge rounded-pill bg-primary px-3 py-2">
                                                    REQUEST
                                                </span>

                                            @elseif($notif['type'] === 'concern')
                                                <span class="badge rounded-pill bg-warning text-dark px-3 py-2">
                                                    CONCERN
                                                </span>

                                            @elseif($notif['type'] === 'blotter')
                                                <span class="badge rounded-pill bg-danger px-3 py-2">
                                                    BLOTTER
                                                </span>
                                            @endif

                                            <small class="text-muted">
                                                {{ $notif['created_at']->diffForHumans() }}
                                            </small>

                                        </div>

                                        {{-- USER --}}
                                        <h5 class="fw-bold mb-1">
                                            {{ $notif['user'] ?? 'Unknown User' }}
                                        </h5>

                                        {{-- TITLE --}}
                                        <div class="fs-5 fw-semibold text-dark">
                                            {{ $notif['title'] }}
                                        </div>

                                        {{-- SUBTITLE --}}
                                        <div class="text-muted mt-1 mb-3">
                                            {{ $notif['subtitle'] }}
                                        </div>

                                        {{-- DETAILS --}}
                                        <div class="row g-3">

                                            <div class="col-md-4">
                                                <div class="detail-box">
                                                    <small class="text-muted d-block">
                                                        Notification ID
                                                    </small>

                                                    <strong>
                                                        #{{ $notif['id'] }}
                                                    </strong>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="detail-box">
                                                    <small class="text-muted d-block">
                                                        Category
                                                    </small>

                                                    <strong>
                                                        {{ ucfirst($notif['type']) }}
                                                    </strong>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="detail-box">
                                                    <small class="text-muted d-block">
                                                        Created Date
                                                    </small>

                                                    <strong>
                                                        {{ $notif['created_at']->format('M d, Y') }}
                                                    </strong>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                    {{-- ACTION --}}
                                    <div>

                                        <form method="POST"
                                            action="{{ route('notifications.markAsRead', [$notif['type'], $notif['id']]) }}">
                                            @csrf

                                            <button class="btn btn-success rounded-pill px-4 py-2">
                                                Mark Read
                                            </button>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="text-center py-5">

                            <h5 class="text-muted">
                                No notifications found
                            </h5>

                        </div>

                    @endforelse

                </div>

                <!-- PAGINATION -->
                <div class="mt-3">
                    {{ $notifications->links() }}
                </div>

            </div>
        </div>
    </div>

</x-app-layout>