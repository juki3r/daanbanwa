@forelse($requests as $request)

<tr>
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
        <!-- ✅ OPEN SINGLE MODAL -->
        <button class="btn btn-sm btn-primary open-status"
            data-id="{{ $request->id }}"
            data-status="{{ $request->status }}">
            <i class="bi bi-pencil-square"></i> Status
        </button>

        <button class="btn btn-sm btn-danger delete-btn"
            data-id="{{ $request->id }}">
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




