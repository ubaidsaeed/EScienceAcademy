@extends('layouts.admin.app')
@can('show dashboard')
@push('style')
    <link href="{{asset('build/assets/admin/css/dashboard.css')}}" rel="stylesheet" />
@endpush
@section('content')
<div class="app-content main-content">
   <div class="container-fluid">
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <h4 class="page-title">Dashboard</h4>
        </div>
    </div>
    
    {{-- reporting start  --}}
    <section class="report-section">
        <div class="section-header">
            <h2>Institution Summary</h2>
        </div>
        <div class="stats-grid">
            <div class="stat-card total">
                <div class="stat-header">
                    <span class="stat-title">Total Students</span>
                    <div class="stat-icon total">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($totalStudent) }}</div>
            </div>
            <div class="stat-card male">
                <div class="stat-header">
                    <span class="stat-title">Total Income</span>
                    <div class="stat-icon male">
                        <i class="fas fa-rupee-sign"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($totalIncome, 2) }}</div>
            </div>
            <div class="stat-card female">
                <div class="stat-header">
                    <span class="stat-title">Total Active Students</span>
                    <div class="stat-icon female">
                         <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($totalActiveStudents) }}</div>
            </div>
        </div>
    </section>

    <section class="report-section">
        <div class="section-header">
            <h2>Monthly Summary</h2>
        </div>
        <div class="stats-grid">
            <div class="stat-card income">
                <div class="stat-header">
                    <span class="stat-title">Monthly Students</span>
                    <div class="stat-icon income">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($currentMonthTotalStudent) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Monthly Income</span>
                    <div class="stat-icon" style="background-color: var(--success);">
                        <i class="fas fa-rupee-sign"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($currentMonthTotalIncome, 2) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Custom Package</span>
                    <div class="stat-icon" style="background-color: var(--info);">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($customQuery) }}</div>
            </div>
        </div>
    </section>

    <div class="col-lg-12">
        <section class="report-section">
            <div class="section-header">
                <h2>Student Demographics</h2>
            </div>
            <div class="card">
                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.dashboard') }}">
                        @csrf
                        <div class="row align-items-end">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">From</label>
                                <input type="month" name="startOfMonth" value="{{ $startOfMonth }}" 
                                    class="form-control" required />
                                @error('startOfMonth')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">To</label>
                                <input type="month" name="endOfMonth" value="{{ $endOfMonth }}" 
                                    class="form-control" required />
                                @error('endOfMonth')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <button type="submit" class="btn btn-primary w-100" id="searchBtn">
                                    <span class="spinner-border spinner-border-sm" id="searchSpinner" style="display: none;" aria-hidden="true"></span>
                                    <span id="searchText">Search</span>
                                </button>
                            </div>
                        </div>
                    </form>

                    <div style="min-height: 400px;">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
</div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    
      <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let chartInstance = null;
        const ctx = document.getElementById('myChart').getContext('2d');
        const progressData = @json($progress);

        function renderChart(progress) {
            console.log('Rendering chart with data:', progress);

            if (!progress || !Array.isArray(progress)) {
                console.error('Invalid progress data:', progress);
                return;
            }

            if (chartInstance) {
                chartInstance.destroy();
            }

            chartInstance = new Chart(ctx, {
                type: "line",
                data: {
                    labels: progress.map(item => item.date),
                    datasets: [{
                        label: "Daily Subscribers",
                        data: progress.map(item => item.total),
                        fill: false,
                        borderColor: "blue",
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Initial render
        if (progressData && progressData.length > 0) {
            renderChart(progressData);
        } else {
            renderChart([]);
        }

        // Add form submission loading indicator
        document.querySelector('form').addEventListener('submit', function() {
            const btn = document.getElementById('searchBtn');
            const spinner = document.getElementById('searchSpinner');
            const text = document.getElementById('searchText');
            
            btn.disabled = true;
            spinner.style.display = 'inline-block';
            text.style.display = 'none';
        });
    });
    </script>
@endpush
@endcan