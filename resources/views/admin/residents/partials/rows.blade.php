@forelse($residents as $resident)

<tr>
    <td>
    {{ trim(
        $resident->first_name . ' ' .
        ($resident->middle_name ? $resident->middle_name . ' ' : '') .
        $resident->last_name .
        ($resident->suffix ? ' ' . $resident->suffix : '')
    ) }}
    </td>   
    <td>{{ $resident->purok }}</td>

    <td>{{ $resident->household_name }}</td>

    <td>{{ $resident->sex }}</td>

    <td>{{ $resident->birth_date }}</td>

    <td>{{ $resident->age ?? \Carbon\Carbon::parse($resident->birth_date)->age }}</td>

    <td>{{ $resident->civil_status }}</td>

    <td>
        @if($resident->is_voter)
            <span class="badge bg-success">Yes</span>
        @else
            <span class="badge bg-secondary">No</span>
        @endif
    </td>

    <td>
        @if($resident->is_pwd)
            <span class="badge bg-warning text-dark">Yes</span>
        @else
            <span class="badge bg-secondary">No</span>
        @endif
    </td>

    <td>
        <span class="badge 
            @if($resident->resident_status == 'Active') bg-success
            @elseif($resident->resident_status == 'Moved Out') bg-warning
            @else bg-danger @endif">
            {{ $resident->resident_status }}
        </span>
    </td>
</tr>

@empty

<tr>
    <td colspan="12" class="text-center text-muted py-3">
        No records found
    </td>
</tr>

@endforelse




