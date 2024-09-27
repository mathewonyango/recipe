@extends('layout.default')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Create New Event</h1>

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

    <form action="{{ route('events.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Event Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required maxlength="255">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="topic_id">Select Topic</label>
                    <select class="form-control" id="topic_id" name="topic_id" required>
                        <option value="" disabled selected>Select a Topic</option>
                        @foreach ($topics as $topic)
                            <option value="{{ $topic->id }}">
                                {{ $topic->name }}
                                <span class="badge bg-{{ $topic->status == 'open' ? 'success' : 'danger' }}">
                                    {{ ucfirst($topic->status) }}
                                </span>
                            </option>
                        @endforeach
                    </select>
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
        </div>

        <div class="mt-4 text-right">
            <button type="submit" class="btn btn-primary">Create Event</button>
        </div>
    </form>
</div>
@endsection
