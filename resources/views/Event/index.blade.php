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
        <h1 class="h3">Events</h1>
        <a href="{{ route('events.create') }}" class="btn btn-primary btn-sm">
            <i data-feather="plus" class="icon-dual icon-xs me-2"></i>
            Add New Event
        </a>
    </div>

    <!-- Events Table Card -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                @if ($events->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Location</th>
                            <th>Topic</th>
                            <th>Event Date</th>
                            <th>Event Time</th>
                            <th>Charges</th>
                            <th>Contact Number</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($events as $event)
                        <tr>
                            <td>{{ $event->location }}</td>
                            <td>{{ $event->topic }}</td>
                            <td>{{ $event->event_date->format('d-m-Y') }}</td>
                            <td>{{ $event->time }}</td>
                            <td>{{ $event->charges }} KES</td>
                            <td>{{ $event->contact_number }}</td>
                            <td>
                                <span class="badge bg-{{ $event->status == 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="alert alert-warning">
                    No events found
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
</div>
@endsection
