@extends('layout.default')

@section('content')
<div class="dashboard-header">
    <h1>Dashboard</h1>
    <input type="text" placeholder="Search here...">
    <div class="user-info">
        <span>Eng (US)</span>
        <i class="fas fa-bell ml-3"></i>
        <span class="ml-3">{{ Auth::user()->name }}</span>
        <img src="{{ URL::asset('assets/images/man.png') }}" alt="User Avatar" class="avatar-md rounded-circle mb-2"/>
    </div>
</div>
<div class="container-fluid">
    <h1 class="mb-4">Create New Topic</h1>

    <!-- Display validation errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('topics.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Topic Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required maxlength="255">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" required>{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                </div>
            </div>

            {{-- <div class="col-md-4">
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="" disabled selected>Select Status</option>
                        <option value="open" {{ old('status') == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
            </div> --}}
        </div>

        <div class="mt-4 text-right">
            <button type="submit" class="btn btn-primary">Create Topic</button>
        </div>
    </form>
</div>
@endsection
