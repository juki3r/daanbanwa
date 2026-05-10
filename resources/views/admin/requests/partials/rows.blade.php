@forelse($requests as $request)

<tr id="request-row-{{ $request->id }}" class="{{ $request->admin_read == 0 ? 'table-warning' : '' }}">
    <td>{{ $loop->iteration + ($requests->firstItem() - 1) }}</td>

    <td>{{ $request->full_name }}</td>
    <td>{{ $request->age }}</td>
    <td>{{ $request->gender }}</td>
    <td>{{ $request->address }}</td>
    <td>{{ $request->document_type }}</td>
    <td>{{ $request->purpose }}</td>

    <td>
        @php
            $statusClass = match($request->status) {
                'pending' => 'bg-warning text-dark',
                'approved' => 'bg-success',
                default => 'bg-danger',
            };
        @endphp

        <span id="status-badge-{{ $request->id }}" class="badge {{ $statusClass }}">
            {{ ucfirst($request->status) }}
        </span>
    </td>

    <td>
        <div class="d-flex gap-2 justify-content-center">
            <button class="btn btn-sm btn-primary open-status d-flex align-items-center gap-1"
                data-id="{{ $request->id }}"
                data-status="{{ $request->status }}">
                <i class="bi bi-pencil-square"></i> Status
            </button>

            <button class="btn btn-sm btn-danger delete-btn d-flex align-items-center gap-1"
                data-id="{{ $request->id }}">
                <i class="bi bi-trash"></i> Delete
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




