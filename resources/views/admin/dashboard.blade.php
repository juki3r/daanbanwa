<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 fw-semibold text-dark">Dashboard Overview</h2>
        </div>
    </x-slot>

    <div class="container-fluid py-4 bg-light min-vh-100">

        {{-- ================= TOP CARDS ================= --}}
        <div class="row g-3 mb-4">

            <div class="col-xl-3 col-md-6">
                <div class="glass-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Total Residents</div>
                            <div class="fs-3 fw-bold">{{ $totalResidents }}</div>
                        </div>
                        <i class="bi bi-people-fill fs-1 text-primary opacity-75"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="glass-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Male</div>
                            <div class="fs-3 fw-bold">{{ $male }}</div>
                        </div>
                        <i class="bi bi-gender-male fs-1 text-primary opacity-75"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="glass-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Female</div>
                            <div class="fs-3 fw-bold">{{ $female }}</div>
                        </div>
                        <i class="bi bi-gender-female fs-1 text-danger opacity-75"></i>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="glass-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Households</div>
                            <div class="fs-3 fw-bold">{{ $households }}</div>
                        </div>
                        <i class="bi bi-house-door-fill fs-1 text-success opacity-75"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="glass-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Voters</div>
                            <div class="fs-3 fw-bold">{{ $voters }}</div>
                        </div>
                        <i class="bi bi-check2-square fs-1 text-warning opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= CHART SECTION ================= --}}
        <div class="row g-3 mb-4">

            <div class="col-lg-6">
                <div class="chart-card">
                    <div class="card-title">Age Distribution</div>
                    <div class="chart-box">
                        <canvas id="ageChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="chart-card">
                    <div class="card-title">Civil Status</div>
                    <div class="chart-box">
                        <canvas id="civilChart"></canvas>
                    </div>
                </div>
            </div>

        </div>

        {{-- ================= TABLE ================= --}}
        <div class="table-card">
            <div class="card-title mb-3">Recent Residents</div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr class="text-muted small">
                            <th>Name</th>
                            <th>Purok</th>
                            <th>Sex</th>
                            <th>Date Added</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentResidents as $r)
                            <tr>
                                <td class="fw-semibold">
                                    {{ $r->last_name }}, {{ $r->first_name }}
                                </td>
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

    {{-- ================= STYLE ================= --}}
    <style>
        .glass-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 18px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: 0.2s ease;
        }

        .glass-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        .chart-card, .table-card {
            background: #fff;
            border-radius: 14px;
            padding: 18px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .card-title {
            font-weight: 600;
            margin-bottom: 10px;
        }

        .chart-box {
            height: 260px;
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
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        new Chart(document.getElementById('civilChart'), {
            type: 'doughnut',
            data: {
                labels: ['Single', 'Married', 'Widow', 'Separated'],
                datasets: [{
                    data: @json($civilData),
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>

</x-app-layout>