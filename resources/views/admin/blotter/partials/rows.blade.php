@forelse($blotters as $blotter)

<tr id="blotter-row-{{ $blotter->id }}" class="{{ $blotter->admin_read == 0 ? 'table-warning' : '' }}">

    <td>{{ $blotter->case_number }}</td>
   <td>{{ ($blotter->user->first_name ?? '') . ' ' . ($blotter->user->last_name ?? '') ?: 'N/A' }}</td>
    <td>{{ $blotter->complainant_name }}</td>
    <td style="max-width: 300px; white-space: normal; word-break: break-word;">{{ $blotter->statement }}</td>

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
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-sm btn-primary open-status d-flex align-items-center gap-1"
                data-id="{{ $blotter->id }}"
                data-status="{{ $blotter->status }}">
                <i class="bi bi-pencil-square"></i> Status
            </button>

            <button class="btn btn-sm btn-danger delete-btn d-flex align-items-center gap-1"
                data-id="{{ $blotter->id }}">
                <i class="bi bi-trash me-1"></i> Delete
            </button>
        </div>
    </td>
</tr>



@empty

<tr>
    <td colspan="9" class="text-center text-muted py-3">
        No records found
    </td>
</tr>

@endforelse




