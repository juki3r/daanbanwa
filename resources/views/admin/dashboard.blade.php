<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 fw-semibold">Dashboard</h2>
        </div>
    </x-slot>

    <div class="container-fluid py-3">

        {{-- ================= SUMMARY CARDS ================= --}}
        <div class="row g-3 mb-4">

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm dashboard-card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Total Residents</small>
                            <h3 class="fw-bold mb-0">{{ $totalResidents }}</h3>
                        </div>
                        <i class="bi bi-people-fill fs-1 text-primary"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm dashboard-card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Male</small>
                            <h3 class="fw-bold mb-0 text-primary">{{ $male }}</h3>
                        </div>
                        <i class="bi bi-gender-male fs-1 text-primary"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm dashboard-card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Female</small>
                            <h3 class="fw-bold mb-0 text-danger">{{ $female }}</h3>
                        </div>
                        <i class="bi bi-gender-female fs-1 text-danger"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm dashboard-card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Households</small>
                            <h3 class="fw-bold mb-0 text-success">{{ $households }}</h3>
                        </div>
                        <i class="bi bi-house-door-fill fs-1 text-success"></i>
                    </div>
                </div>
            </div>

        </div>

        {{-- ================= CHARTS ================= --}}
        <div class="row g-3 mb-4">

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 fw-semibold">
                        Age Distribution
                    </div>
                    <div class="card-body">
                        <canvas id="ageChart" height="80"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 fw-semibold">
                        Civil Status
                    </div>
                    <div class="card-body">
                        <canvas id="civilChart" height="80"></canvas>
                    </div>
                </div>
            </div>

        </div>

        {{-- ================= RECENT TABLE ================= --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 fw-semibold">
                Recent Residents
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Purok</th>
                            <th>Sex</th>
                            <th>Date Added</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentResidents as $r)
                            <tr>
                                <td class="fw-semibold">{{ $r->last_name }}, {{ $r->first_name }}</td>
                                <td>{{ $r->purok }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $r->sex }}</span>
                                </td>
                                <td class="text-muted">
                                    {{ $r->created_at->format('M d, Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    </div>

    {{-- ================= STYLES ================= --}}
    <style>
        .dashboard-card {
            transition: 0.2s ease-in-out;
            border-radius: 12px;
        }

        .dashboard-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }
    </style>

    {{-- ================= CHART JS ================= --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        new Chart(document.getElementById('ageChart'), {
            type: 'bar',
            data: {
                labels: ['0-17', '18-59', '60+'],
                datasets: [{
                    label: 'Population',
                    data: @json($ageData),
                    borderRadius: 6
                }]
            }
        });

        new Chart(document.getElementById('civilChart'), {
            type: 'doughnut',
            data: {
                labels: ['Single', 'Married', 'Widow', 'Separated'],
                datasets: [{
                    data: @json($civilData),
                }]
            }
        });
    </script>

</x-app-layout>