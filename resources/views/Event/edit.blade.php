@extends('layout.default')

@section('content')

<div class="container-fluid">
    <h1 class="mb-4">Edit Event</h1>

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

    <form action="{{ route('events.update', $event->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Use PUT method for updating -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Event Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $event->name) }}" required maxlength="255">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" required>{{ old('description', $event->description) }}</textarea>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date', $event->start_date) }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date', $event->end_date) }}" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" class="form-control" id="location" name="location" value="{{ old('location', $event->location) }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="" disabled>Select Status</option>
                        <option value="upcoming" {{ (old('status', $event->status) == 'upcoming') ? 'selected' : '' }}>Upcoming</option>
                        <option value="completed" {{ (old('status', $event->status) == 'completed') ? 'selected' : '' }}>Completed</option>
                        <option value="canceled" {{ (old('status', $event->status) == 'canceled') ? 'selected' : '' }}>Canceled</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="mt-4 text-right">
            <button type="submit" class="btn btn-primary">Update Event</button>
        </div>
    </form>
</div>
@endsection
