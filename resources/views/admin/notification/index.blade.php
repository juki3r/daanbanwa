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

                        <div class="card shadow-sm border-0 mb-2 notification-card">

                            <div class="card-body p-3">

                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">

                                    {{-- LEFT CONTENT --}}
                                    <div class="flex-grow-1">

                                        {{-- HEADER --}}
                                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">

                                            @if($notif['type'] === 'request')
                                                <span class="badge rounded-pill bg-primary px-2 py-1 small">
                                                    REQUEST
                                                </span>

                                            @elseif($notif['type'] === 'concern')
                                                <span class="badge rounded-pill bg-warning text-dark px-2 py-1 small">
                                                    CONCERN
                                                </span>

                                            @elseif($notif['type'] === 'blotter')
                                                <span class="badge rounded-pill bg-danger px-2 py-1 small">
                                                    BLOTTER
                                                </span>
                                            @endif

                                            <small class="text-muted">
                                                {{ $notif['created_at']->diffForHumans() }}
                                            </small>

                                        </div>

                                        {{-- USER --}}
                                        <div class="fw-bold text-dark small">
                                            {{ $notif['user'] ?? 'Unknown User' }}
                                        </div>

                                        {{-- TITLE --}}
                                        <div class="fw-semibold text-dark">
                                            {{ $notif['title'] }}
                                        </div>

                                        {{-- SUBTITLE --}}
                                        <div class="text-muted small mb-2">
                                            {{ $notif['subtitle'] }}
                                        </div>

                                        {{-- DETAILS --}}
                                        <div class="row g-2">

                                            <div class="col-md-4">
                                                <div class="detail-box small">
                                                    <small class="text-muted d-block">
                                                        ID
                                                    </small>

                                                    <strong>
                                                        #{{ $notif['id'] }}
                                                    </strong>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="detail-box small">
                                                    <small class="text-muted d-block">
                                                        Category
                                                    </small>

                                                    <strong>
                                                        {{ ucfirst($notif['type']) }}
                                                    </strong>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="detail-box small">
                                                    <small class="text-muted d-block">
                                                        Date
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
                                            action="{{ route('notifications.markAsRead', $notif['id']) }}">
                                            @csrf

                                            <button class="btn btn-success btn-sm rounded-pill px-3">
                                                Read
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