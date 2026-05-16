@forelse($emergency as $item)

<tr>
    <td>
        <strong class="text-capitalize">{{ $item->name }}</strong>
    </td>

    <!-- TITLE -->
    <td>
        <strong class="text-capitalize">{{ $item->phone }}</strong>
    </td>

    <!-- DESCRIPTION -->
    <td>
        <strong class="text-capitalize">
            {{ $item->is_active?  "Yes" :  "No" }}
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

    <td colspan="4"
        class="text-center text-muted py-4">

        No Contacts found

    </td>

</tr>

@endforelse