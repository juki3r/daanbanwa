@forelse($residents as $resident)

<tr>
    {{-- NAME --}}
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
</tr>

@empty

<tr>
    <td colspan="10" class="text-center text-muted py-3">
        No records found
    </td>
</tr>

@endforelse