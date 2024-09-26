@extends('layout.default')

@section('content')
<div class="container-fluid mt-4">
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Topics</h1>
        <a href="{{ route('topics.create') }}" class="btn btn-primary btn-sm">
            <i data-feather="plus" class="icon-dual icon-xs me-2"></i>
            Add New Topic
        </a>
    </div>

    <!-- Chart Card -->
    {{-- <div class="card mb-4">
        <div class="card-body">
            <h4 class="card-title h5 mb-3">Topics Status Overview</h4>
            <canvas id="topicsStatusChart" height="100"></canvas>
        </div>
    </div> --}}

    <!-- Topics Table Card -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                @if ($topics->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Submissions</th>
                            <th>Recipes</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topics as $topic)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://via.placeholder.com/30/{{ substr(md5($topic->name), 0, 6) }}/ffffff?text={{ substr($topic->name, 0, 2) }}" alt="{{ $topic->name }}" class="me-2 rounded" style="width: 30px; height: 30px;">
                                    <span>{{ $topic->name }}</span>
                                </div>
                            </td>
                            <td>{{ Str::limit($topic->description, 50) }}</td>
                            <td>{{ $topic->created_at->format('d-m-Y H:i') }}</td>
                            <td>{{ $topic->updated_at->format('d-m-Y H:i') }}</td>
                            <td>{{ $topic->start_date ? $topic->start_date : 'N/A' }}</td>
                            <td>{{ $topic->end_date ? $topic->end_date : 'N/A' }}</td>
                            <td>
                                <span class="badge bg-info rounded-pill">{{ $topic->recipes->count() }}</span>
                            </td>
                            <td>
                                @if($topic->recipes->isEmpty())
                                    <span class="text-muted">No recipes</span>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#recipeList{{ $topic->id }}">
                                        View Recipes
                                    </button>
                                    <div class="collapse mt-2" id="recipeList{{ $topic->id }}">
                                        <ul class="list-unstyled">
                                            @foreach($topic->recipes as $recipe)
                                                <li>{{ $recipe->title }} ({{ $recipe->prep_time }} mins)</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $topic->status == 'open' ? 'success' : 'danger' }}">
                                    {{ ucfirst($topic->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="alert alert-warning">
                    No topics found
                </div>
                @endif
            </div>
        </div>
    </div>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }
        .table {
            font-size: 0.9rem;
        }
        .table th {
            font-weight: 600;
            color: #6c757d;
            border-top: none;
        }
        .table td {
            vertical-align: middle;
        }
        .badge {
            font-weight: normal;
            padding: 0.35em 0.65em;
            font-size: 0.75em;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusCounts = @json($statusCounts);
            const labels = Object.keys(statusCounts);
            const data = Object.values(statusCounts);

            const ctx = document.getElementById('topicsStatusChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Number of Topics',
                        data: data,
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(255, 99, 132, 0.6)',
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 99, 132, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            min: 0,
                            max: 50,
                            ticks: {
                                stepSize: 5
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>
</div>


@endsection
