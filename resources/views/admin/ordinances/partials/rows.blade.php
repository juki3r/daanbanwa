@forelse($ordinances as $item)

<tr>

    <!-- ORDINANCE NO -->
    <td>
        <strong class="text-capitalize">{{ $item->ordinance_no }}</strong>
    </td>

    <!-- TITLE -->
    <td>
        <strong class="text-capitalize">{{ $item->title }}</strong>
    </td>

    <!-- DESCRIPTION -->
    <td class="text-wrap">
        {{ $item->description }}
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