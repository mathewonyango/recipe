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
        <h1 class="h3">Recipes Overview</h1>
        <div>
            <button class="btn btn-outline-secondary btn-sm me-2">Filter</button>
            <button class="btn btn-outline-secondary btn-sm">Sort</button>
            {{-- <button class="btn btn-primary btn-sm ms-2">+ Add Recipe</button> --}}
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive" id="table">
                @if ($recipes->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Votes</th>
                            <th>Recipe Title</th>
                            <th>Servings</th>
                            <th>Prep Time</th>
                            <th>Cook Time</th>
                            <th>Total Time</th>
                            <th>Topic Name</th>
                            <th>Chef Name</th>
                            <th>Chef Email</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recipes as $recipe)
                        <tr>
                            <td>
                                <span class="badge bg-light text-dark rounded-pill">{{ $recipe->votes->count() }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://via.placeholder.com/30/{{ substr(md5($recipe->title), 0, 6) }}/ffffff?text={{ substr($recipe->title, 0, 2) }}" alt="{{ $recipe->title }}" class="me-2 rounded" style="width: 30px; height: 30px;">
                                    <span>{{ $recipe->title }}</span>
                                </div>
                            </td>
                            <td>{{ $recipe->servings }}</td>
                            <td>{{ $recipe->prep_time }} mins</td>
                            <td>{{ $recipe->cook_time }} mins</td>
                            <td>{{ $recipe->total_time }} mins</td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $recipe->topic->name }}</span>
                            </td>
                            <td>{{ $recipe->chef->name }}</td>
                            <td>{{ $recipe->chef->email }}</td>
                            <td>
                                @if ($recipe->status == 'draft')
                                    <span class="badge bg-warning text-dark">Draft</span>
                                @elseif ($recipe->status == 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif ($recipe->status == 'revoked')
                                    <span class="badge bg-danger">Revoked</span>
                                @endif
                            </td>
                            <td>
                                @if ($recipe->status == 'draft')
                                    <a href="{{ route('recipe.approve', $recipe->id) }}" class="btn btn-sm btn-outline-success">Approve</a>
                                @elseif ($recipe->status == 'approved')
                                    <form action="{{ route('recipe.toggleStatus', $recipe->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Revoke</button>
                                    </form>
                                @elseif ($recipe->status == 'revoked')
                                    <form action="{{ route('recipe.toggleStatus', $recipe->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-sm btn-outline-success">Approve</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="alert alert-warning">
                    No recipes found
                </div>
                @endif
            </div>
            <!-- Pagination Links -->
            <div class="d-flex justify-content-center mt-3">
                {{ $recipes->links() }}
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
