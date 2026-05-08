@forelse($news as $item)

<tr>

    <!-- ID -->
    <td>
        {{ $loop->iteration + ($news->firstItem() - 1) }}
    </td>

    <!-- TITLE -->
    <td>
        <strong>{{ $item->title }}</strong>
    </td>

    <!-- CONTENT -->
    <td>
        {{ $item->content }}
    </td>

    <!-- ACTIONS -->
    <td>

        <!-- DELETE -->
        <button class="btn btn-sm btn-danger delete-btn"
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