@forelse($users as $user)

<tr>

    <td>
        {{ ucwords(strtolower($user->last_name)) }}
    </td>
    <td>
        {{ ucwords(strtolower($user->first_name)) }}
    </td>
    <td>
        {{ ucwords(strtolower($user->middle_name)) ?? '-' }}
    </td>

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
        <button class="btn btn-primary btn-sm"
            onclick="openModal({{ $user->id }})">
            Send Notification
        </button>
    </td>

</tr>

@empty

<tr>

    <td colspan="4"
        class="text-center text-muted py-4">

        No news found

    </td>

</tr>

@endforelse