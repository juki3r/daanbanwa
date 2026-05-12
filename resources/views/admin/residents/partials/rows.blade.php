@forelse($residents as $resident)

<tr class="resident-row" data-id="{{ $resident->id }}" style="cursor:pointer;">
    {{-- NAME --}}
    <td>{{ $resident->last_name }}</td>
    <td>{{ $resident->first_name }}</td>
    <td>{{ $resident->middle_name ?? '-' }}</td>
    <td>{{ $resident->suffix ?? '-' }}</td>

    <td>{{ $resident->purok }}</td>

    <td>{{ $resident->household_name }}</td>

    <td>{{ $resident->sex }}</td>

    {{-- DATE FORMAT FIX --}}
    <td>{{ \Carbon\Carbon::parse($resident->birth_date)->format('M d, Y') }}</td>

    {{-- AGE --}}
    <td>{{ $resident->age ?? \Carbon\Carbon::parse($resident->birth_date)->age }}</td>

    <td>{{ $resident->civil_status }}</td>

    {{-- VOTER --}}
    <td>
        <span class="badge {{ $resident->is_voter ? 'bg-success' : 'bg-secondary' }}">
            {{ $resident->is_voter ? 'Yes' : 'No' }}
        </span>
    </td>

    {{-- PWD --}}
    <td>
        <span class="badge {{ $resident->is_pwd ? 'bg-warning text-dark' : 'bg-secondary' }}">
            {{ $resident->is_pwd ? 'Yes' : 'No' }}
        </span>
    </td>
    
    {{-- STATUS --}}
    <td>
        <span class="badge
            @if($resident->resident_status == 'Active') bg-success
            @elseif($resident->resident_status == 'Moved Out') bg-warning
            @else bg-danger @endif">
            {{ $resident->resident_status }}
        </span>
    </td>
    {{-- ACTIONS --}}
    <td>
        <div class="d-flex gap-1 justify-content-center">

            <button type="button" class="btn btn-sm btn-primary edit-btn d-flex align-items-center"
                data-id="{{ $resident->id }}">
                <i class="bi bi-pencil-square me-1"></i>
                Edit
            </button>

            <button type="button" class="btn btn-sm btn-danger delete-btn d-flex align-items-center"
                data-id="{{ $resident->id }}">
                <i class="bi bi-trash-fill me-1"></i>
                Delete
            </button>

        </div>
    </td>

</tr>

@empty

<tr>
    <td colspan="14" class="text-center text-muted py-3">
        No records found
    </td>
</tr>

@endforelse