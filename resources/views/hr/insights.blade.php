<!-- resources/views/hr/insights.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>HR Insights</h1>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Top Performers</h5>
                    <ul class="list-group">
                        @foreach($topPerformers as $performer)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $performer->name }}
                                <span class="badge bg-primary rounded-pill">{{ $performer->assigned_tasks_count }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Customer Growth</h5>
                    <canvas id="customerGrowthChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Task Completion Times</h5>
                    <p>Average: {{ number_format($avgCompletionTime, 2) }} hours</p>
                    <p>Median: {{ number_format($medianCompletionTime, 2) }} hours</p>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Customer Growth Chart
    new Chart(document.getElementById('customerGrowthChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($customerGrowth->pluck('month')) !!},
            datasets: [{
                label: 'New Customers',
                data: {!! json_encode($customerGrowth->pluck('count')) !!},
                borderColor: '#36A2EB',
                fill: false
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
</div>
@endsection


