@forelse($concerns as $concern)

<tr>
    <td>{{ $loop->iteration + ($concerns->firstItem() - 1) }}</td>

    <td>{{ $concern->title }}</td>
    <td>{{ $concern->location }}</td>
    <td>{{ $concern->description }}</td>

    <td>
        @php
            $statusClass = match($concern->status) {
                'submitted' => 'bg-warning text-dark',
                'received' => 'bg-secondary text-white',
                'under_review' => 'bg-primary text-white',
                'in_progress' => 'bg-success text-white',
                'resolved' => 'bg-info text-dark',
                default => 'bg-danger',
            };

            $statusLabel = ucwords(str_replace('_', ' ', $concern->status));
        @endphp

        <span id="status-badge-{{ $concern->id }}" class="badge {{ $statusClass }}">
            {{ $statusLabel }}
        </span>
    </td>

    <td>
        <button class="btn btn-sm btn-primary open-status"
            data-id="{{ $concern->id }}"
            data-status="{{ $concern->status }}">
            <i class="bi bi-pencil-square me-1"></i> Status
        </button>

        <button class="btn btn-sm btn-danger delete-btn"
            data-id="{{ $concern->id }}">
            <i class="bi bi-trash me-1"></i> Delete
        </button>
    </td>
</tr>

@empty

<tr>
    <td colspan="9" class="text-center text-muted py-3">
        No records found
    </td>
</tr>

@endforelse