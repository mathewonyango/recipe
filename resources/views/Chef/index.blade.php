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
        <h1 class="h3">All Chefs</h1>
        <div>
            <button class="btn btn-outline-secondary btn-sm me-2">Filter</button>
            <button class="btn btn-outline-secondary btn-sm">Sort</button>
            {{-- Uncomment if you want to add a button to add chefs --}}
            {{-- <button class="btn btn-primary btn-sm ms-2">+ Add Chef</button> --}}
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive" id="table">
                @if($chefs->count() > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                {{-- <th>Role</th> --}}
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($chefs as $chef)
                            <tr>
                                <td>{{ $chef->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://via.placeholder.com/30/{{ substr(md5($chef->name), 0, 6) }}/ffffff?text={{ substr($chef->name, 0, 2) }}" alt="{{ $chef->name }}" class="me-2 rounded" style="width: 30px; height: 30px;">
                                        <span>{{ $chef->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $chef->email }}</td>
                                {{-- <td>
                                    <span class="badge bg-primary">{{ ucfirst($chef->role) }}</span>
                                </td> --}}
                                <td>
                                    <span class="badge bg-warning">{{ ucfirst($chef->approval_status) }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('chefs.approve', $chef->id) }}" class="btn btn-sm btn-outline-success">
                                        <i data-feather="check" class="icon-dual icon-xs mr-2"></i>
                                        Approve
                                    </a>
                                    <form action="{{ route('chefs.approve', $chef->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-sm {{ $chef->status == 'active' ? 'btn-danger' : 'btn-success' }}">
                                            <i data-feather="{{ $chef->status == 'active' ? 'x' : 'check' }}" class="icon-dual icon-xs mr-2"></i>
                                            {{ $chef->status == 'active' ? 'Reject' : 'Approve' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-warning">
                        No chefs found
                    </div>
                @endif
            </div>
            <!-- Pagination Links -->
            <div class="d-flex justify-content-center mt-3">
                {{ $chefs->links() }}
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


</div>
@endsection
