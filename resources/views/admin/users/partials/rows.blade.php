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

    <td>
        <strong>
            {{ substr($user->phone, 0, 4) }}-{{ substr($user->phone, 4, 3) }}-{{ substr($user->phone, 7, 4) }}
        </strong>
    </td>
    <td>
        @if($user->phone_verified)
            <span class="badge bg-success">Verified</span>
        @else
            <span class="badge bg-warning text-dark">Not Verified</span>
        @endif
    </td>
    <td>
        @if($user->granted)
            <span class="badge bg-success">Granted</span>
        @else
            <span class="badge bg-warning text-dark">Pending</span>
        @endif
    </td>
    <td>
            <div class="d-flex gap-2 flex-wrap">

                {{-- 1. NOT VERIFIED --}}
                @if(!$user->phone_verified)

                    <button type="button"
                        class="btn btn-success btn-sm verify-btn d-flex align-items-center"
                        data-id="{{ $user->id }}">
                        <i class="bi bi-patch-check-fill me-1"></i>
                        Verify
                    </button>

                {{-- 2. VERIFIED BUT NOT GRANTED --}}
                @elseif($user->phone_verified && !$user->granted)

                    <button type="button"
                        class="btn btn-warning text-dark btn-sm granted-btn d-flex align-items-center"
                        data-id="{{ $user->id }}">
                        <i class="bi bi-patch-check-fill me-1"></i>
                        Grant Access
                    </button>

                {{-- 3. VERIFIED + GRANTED --}}
                @else

                    <button type="button"
                        class="btn btn-danger btn-sm decline-btn d-flex align-items-center"
                        data-id="{{ $user->id }}">
                        <i class="bi bi-x-circle-fill me-1"></i>
                        Decline Access
                    </button>

                    <button type="button"
                        class="btn btn-primary btn-sm d-flex align-items-center"
                        onclick="openModal({{ $user->id }})">
                        <i class="bi bi-bell-fill me-1"></i>
                        Notify
                    </button>

                @endif

            </div>
        </td>

</tr>

@empty

<tr>

    <td colspan="7"
        class="text-center text-muted py-4">

        No news found

    </td>

</tr>

@endforelse