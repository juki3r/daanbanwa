<form method="POST" action="{{ route('residents.update', $resident->id) }}">
    @csrf
    @method('PUT')

    <div class="row">

        <div class="col-md-4">
            <label>First Name</label>
            <input type="text" name="first_name" value="{{ $resident->first_name }}" class="form-control">
        </div>

        <div class="col-md-4">
            <label>Middle Name</label>
            <input type="text" name="middle_name" value="{{ $resident->middle_name }}" class="form-control">
        </div>

        <div class="col-md-4">
            <label>Last Name</label>
            <input type="text" name="last_name" value="{{ $resident->last_name }}" class="form-control">
        </div>

        <div class="col-md-3 mt-2">
            <label>Sex</label>
            <select name="sex" class="form-control">
                <option {{ $resident->sex == 'Male' ? 'selected' : '' }}>Male</option>
                <option {{ $resident->sex == 'Female' ? 'selected' : '' }}>Female</option>
            </select>
        </div>

        <div class="col-md-3 mt-2">
            <label>Birth Date</label>
            <input type="date" name="birth_date" value="{{ $resident->birth_date }}" class="form-control">
        </div>

        <div class="col-md-3 mt-2">
            <label>Civil Status</label>
            <select name="civil_status" class="form-control">
                <option {{ $resident->civil_status == 'Single' ? 'selected' : '' }}>Single</option>
                <option {{ $resident->civil_status == 'Married' ? 'selected' : '' }}>Married</option>
                <option {{ $resident->civil_status == 'Widow' ? 'selected' : '' }}>Widow</option>
                <option {{ $resident->civil_status == 'Separated' ? 'selected' : '' }}>Separated</option>
            </select>
        </div>

        <div class="col-md-3 mt-2">
            <label>Purok</label>
            <input type="text" name="purok" value="{{ $resident->purok }}" class="form-control">
        </div>

        <div class="col-md-4 mt-2">
            <label>House Number</label>
            <input type="text" name="house_number" value="{{ $resident->house_number }}" class="form-control">
        </div>

        <div class="col-md-4 mt-2">
            <label>Street</label>
            <input type="text" name="street" value="{{ $resident->street }}" class="form-control">
        </div>

        <div class="col-md-6 mt-2">
            <label>Household Name</label>
            <input type="text" name="household_name" value="{{ $resident->household_name }}" class="form-control">
        </div>

        <div class="col-md-6 mt-2">
            <label>Relationship</label>
            <input type="text" name="relationship_to_head" value="{{ $resident->relationship_to_head }}" class="form-control">
        </div>

    </div>

    <div class="mt-3 text-end">
        <button class="btn btn-primary">Update Resident</button>
    </div>
</form>