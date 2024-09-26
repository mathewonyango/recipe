<!-- resources/views/hr/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>HR Dashboard</h1>
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Employees</h5>
                    <p class="card-text">{{ $totalEmployees }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Technicians</h5>
                    <p class="card-text">{{ $totalTechnicians }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Tasks</h5>
                    <p class="card-text">{{ $totalTasks }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Customers</h5>
                    <p class="card-text">{{ $totalCustomers }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tasks by Status</h5>
                    <div class="card-container" style="height:300px">
                    <canvas id="taskStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Employees by Role</h5>
                    <div class="card-container" style="height:300px">

                    <canvas id="employeeRoleChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Task Status Chart
    new Chart(document.getElementById('taskStatusChart'), {
        type: 'pie',
        data: {
            labels: {!! json_encode($tasksByStatus->keys()) !!},
            datasets: [{
                data: {!! json_encode($tasksByStatus->values()) !!},
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0']
            }]
        }
    });

    // Employee Role Chart
    new Chart(document.getElementById('employeeRoleChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($employeesByRole->keys()) !!},
            datasets: [{
                label: 'Number of Employees',
                data: {!! json_encode($employeesByRole->values()) !!},
                backgroundColor: '#36A2EB'
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
