<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0 fw-bold">
                {{ $resident->last_name }}, {{ $resident->first_name }}
            </h4>
            <small class="text-muted">Resident Profile Details</small>
        </div>

        <span class="badge bg-success px-3 py-2">
            {{ $resident->resident_status }}
        </span>
    </div>

    <hr>

    <!-- INFO CARDS -->
    <div class="row g-3">

        <div class="col-md-6">
            <div class="p-3 border rounded bg-light h-100">

                <h6 class="fw-bold mb-3">Personal Information</h6>

                <p class="mb-1"><strong>Full Name:</strong>
                    {{ $resident->first_name }} {{ $resident->middle_name }} {{ $resident->last_name }} {{ $resident->suffix }}
                </p>

                <p class="mb-1"><strong>Sex:</strong> {{ $resident->sex }}</p>

                <p class="mb-1"><strong>Birth Date:</strong>
                    {{ \Carbon\Carbon::parse($resident->birth_date)->format('M d, Y') }}
                </p>

                <p class="mb-1"><strong>Age:</strong> {{ $resident->age }}</p>

                <p class="mb-0"><strong>Civil Status:</strong> {{ $resident->civil_status }}</p>

            </div>
        </div>

        <div class="col-md-6">
            <div class="p-3 border rounded bg-light h-100">

                <h6 class="fw-bold mb-3">Household Information</h6>

                <p class="mb-1"><strong>Purok:</strong> {{ $resident->purok }}</p>

                <p class="mb-1"><strong>Household:</strong> {{ $resident->household_name }}</p>

                <p class="mb-1"><strong>Relationship:</strong> {{ $resident->relationship_to_head }}</p>

                <p class="mb-1"><strong>Occupation:</strong> {{ $resident->occupation ?? '-' }}</p>

                <p class="mb-0"><strong>Contact:</strong> {{ $resident->contact_number ?? '-' }}</p>

            </div>
        </div>

    </div>

    <!-- STATUS SECTION -->
    <div class="mt-3 p-3 border rounded bg-light">

        <h6 class="fw-bold mb-3">Additional Info</h6>

        <div class="d-flex gap-3 flex-wrap">

            <span class="badge {{ $resident->is_voter ? 'bg-success' : 'bg-secondary' }}">
                Voter: {{ $resident->is_voter ? 'Yes' : 'No' }}
            </span>

            <span class="badge {{ $resident->is_pwd ? 'bg-warning text-dark' : 'bg-secondary' }}">
                PWD: {{ $resident->is_pwd ? 'Yes' : 'No' }}
            </span>

        </div>

    </div>

</div>