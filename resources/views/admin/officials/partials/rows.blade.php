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
        <strong class="text-capitalize">{{ $item->phone_number }}</strong>
    </td>
    <td>
        <strong class="text-capitalize">{{ $item->assignment }}</strong>
    </td>
    <!-- ACTIONS -->
    <td>

        <!-- DELETE -->
        <button class="btn btn-sm btn-danger delete-btn d-flex align-items-center justify-content-center gap-1"
                data-id="{{ $item->id }}">

            <i class="bi bi-trash me-1"></i>
            Delete

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