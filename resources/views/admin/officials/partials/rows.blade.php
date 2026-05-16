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
        <strong>
            @if(!empty($item->phone_number) && strlen($item->phone_number) == 11)
                {{ substr($item->phone_number, 0, 4) }}-{{ substr($item->phone_number, 4, 3) }}-{{ substr($item->phone_number, 7, 4) }}
            @else
                {{ $item->phone_number ?? '-' }}
            @endif
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

        No Officials found

    </td>

</tr>

@endforelse