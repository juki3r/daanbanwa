<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">Dashboard</h2>
        </div>
    </x-slot>

    <div class="container-fluid py-4">

        {{-- ================= CARDS ================= --}}
        <div class="row g-3 mb-4">

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Total Residents</h6>
                        <h3 class="fw-bold">{{ $totalResidents }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Male</h6>
                        <h3 class="fw-bold text-primary">{{ $male }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Female</h6>
                        <h3 class="fw-bold text-danger">{{ $female }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted">Households</h6>
                        <h3 class="fw-bold text-success">{{ $households }}</h3>
                    </div>
                </div>
            </div>

        </div>

        {{-- ================= CHARTS ================= --}}
        <div class="row g-3 mb-4">

            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        Age Distribution
                    </div>
                    <div class="card-body">
                        <canvas id="ageChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        Civil Status
                    </div>
                    <div class="card-body">
                        <canvas id="civilChart"></canvas>
                    </div>
                </div>
            </div>

        </div>

        {{-- ================= RECENT ================= --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                Recent Residents
            </div>

            <div class="card-body p-0">
                <table class="table mb-0">
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
                                <td>{{ $r->last_name }}, {{ $r->first_name }}</td>
                                <td>{{ $r->purok }}</td>
                                <td>{{ $r->sex }}</td>
                                <td>{{ $r->created_at->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

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
                }]
            }
        });

        new Chart(document.getElementById('civilChart'), {
            type: 'pie',
            data: {
                labels: ['Single', 'Married', 'Widow', 'Separated'],
                datasets: [{
                    data: @json($civilData),
                }]
            }
        });
    </script>

</x-app-layout>