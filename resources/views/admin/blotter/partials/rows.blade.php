@forelse($blotters as $blotter)

<tr id="blotter-row-{{ $blotter->id }}" class="{{ $blotter->admin_read == 0 ? 'table-warning' : '' }}">

    <td>{{ $blotter->case_number }}</td>
    <td>{{ $blotter->complainant_name }}</td>
    <td>{{ $blotter->statement }}</td>

    <td>
        @php
            $statusClass = match($blotter->status) {
                'approved' => 'bg-success text-white',
                'rejected' => 'bg-danger text-white',
                default => 'bg-warning text-dark',
            };
        @endphp

        <span id="status-badge-{{ $blotter->id }}" class="badge {{ $statusClass }}">
            {{ ucfirst($blotter->status) }}
        </span>
    </td>

    <td>
        <!-- ✅ OPEN SINGLE MODAL -->
        <button class="btn btn-sm btn-primary open-status"
            data-id="{{ $blotter->id }}"
            data-status="{{ $blotter->status }}">
            <i class="bi bi-pencil-square"></i> Status
        </button>

        <button class="btn btn-sm btn-danger delete-btn"
            data-id="{{ $blotter->id }}">
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




