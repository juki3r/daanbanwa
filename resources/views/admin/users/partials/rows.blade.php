@forelse($users as $user)

<tr>

    <td>
        {{ ucwords(strtolower($user->last_name)) }}
    </td>
    <td>
        {{ ucwords(strtolower($user->first_name)) }}
    </td>
    <td>
        {{ $user->middle_name ? ucwords(strtolower($user->middle_name)) : '-' }}
    </td>

    <td>{{ $user->phone }}</td>
    <td>
        @if($user->phone_verified)
            <span class="badge bg-success">Verified</span>
        @else
            <span class="badge bg-warning text-dark">Not Verified</span>
        @endif
    </td>

    <td>
        <div class="d-flex gap-2 flex-wrap">

            {{-- Verify Button (only show if not verified) --}}
            @if(!$user->phone_verified)
                <button type="button"
                    class="btn btn-success btn-sm verify-btn"
                    data-id="{{ $user->id }}">
                    <i class="bi bi-patch-check-fill me-1"></i>
                    Verify
                </button>
            @endif

            {{-- Send Notification --}}
            <button type="button"
                class="btn btn-primary btn-sm"
                onclick="openModal({{ $user->id }})">
                <i class="bi bi-bell-fill me-1"></i>
                Notify
            </button>

        </div>
    </td>

</tr>

@empty

<tr>

    <td colspan="6"
        class="text-center text-muted py-4">

        No news found

    </td>

</tr>

@endforelse