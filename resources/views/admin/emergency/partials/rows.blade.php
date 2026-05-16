@forelse($officials as $item)

<tr>
    <td>
        <strong class="text-capitalize">{{ $item->name }}</strong>
    </td>

    <!-- TITLE -->
    <td>
        <strong class="text-capitalize">{{ $item->position }}</strong>
    </td>

    <!-- DESCRIPTION -->
    <td>
        <strong class="text-capitalize">
            {{ $item->phone_number ?? '-' }}
        </strong>
    </td>

    <td>
        <strong class="text-capitalize">
            {{ $item->assignment ?? '-' }}
        </strong>
    </td>

    <!-- ACTIONS -->
    <td>
        <div class="d-flex gap-2">

            <!-- EDIT -->
            <button type="button"
                    class="btn btn-sm btn-primary d-flex align-items-center justify-content-center gap-1"
                    data-bs-toggle="modal"
                    data-bs-target="#editOfficialModal{{ $item->id }}">
                <i class="bi bi-pencil-square"></i>
                Edit
            </button>

            <!-- DELETE -->
            <button type="button"
                    class="btn btn-sm btn-danger delete-btn d-flex align-items-center justify-content-center gap-1"
                    data-id="{{ $item->id }}">
                <i class="bi bi-trash"></i>
                Delete
            </button>

        </div>
    </td>

</tr>

@empty

<tr>

    <td colspan="5"
        class="text-center text-muted py-4">

        No news found

    </td>

</tr>

@endforelse