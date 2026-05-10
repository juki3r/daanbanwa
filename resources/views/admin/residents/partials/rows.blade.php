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
</tr>

@empty

<tr>
    <td colspan="9" class="text-center text-muted py-3">
        No records found
    </td>
</tr>

@endforelse




