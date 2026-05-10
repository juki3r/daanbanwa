<div class="container-fluid">

    <h4 class="mb-3">{{ $resident->last_name }}, {{ $resident->first_name }}</h4>

    <hr>

    <div class="row">

        <div class="col-md-6">
            <p><strong>Full Name:</strong> {{ $resident->first_name }} {{ $resident->middle_name }} {{ $resident->last_name }} {{ $resident->suffix }}</p>
            <p><strong>Sex:</strong> {{ $resident->sex }}</p>
            <p><strong>Birth Date:</strong> {{ \Carbon\Carbon::parse($resident->birth_date)->format('M d, Y') }}</p>
            <p><strong>Age:</strong> {{ $resident->age }}</p>
            <p><strong>Civil Status:</strong> {{ $resident->civil_status }}</p>
        </div>

        <div class="col-md-6">
            <p><strong>Purok:</strong> {{ $resident->purok }}</p>
            <p><strong>Household:</strong> {{ $resident->household_name }}</p>
            <p><strong>Relationship:</strong> {{ $resident->relationship_to_head }}</p>
            <p><strong>Occupation:</strong> {{ $resident->occupation ?? '-' }}</p>
            <p><strong>Contact:</strong> {{ $resident->contact_number ?? '-' }}</p>
        </div>

    </div>

    <hr>

    <div>
        <p><strong>Voter:</strong> {{ $resident->is_voter ? 'Yes' : 'No' }}</p>
        <p><strong>PWD:</strong> {{ $resident->is_pwd ? 'Yes' : 'No' }}</p>
        <p><strong>Status:</strong> {{ $resident->resident_status }}</p>
    </div>

</div>