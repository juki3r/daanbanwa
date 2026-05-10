@forelse($residents as $resident)

<tr>
    <td>{{ $resident->first_name }} {{ $resident->last_name }}</td>
</tr>

@empty

<tr>
    <td colspan="9" class="text-center text-muted py-3">
        No records found
    </td>
</tr>

@endforelse




