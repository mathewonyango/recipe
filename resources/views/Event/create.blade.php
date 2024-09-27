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
        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}" required>
        </div>

        <div class="form-group">
            <label for="event_time">Event Time</label>
            <input type="time" class="form-control" id="event_time" name="event_time" value="{{ old('event_time') }}" required>
        </div>

        <div class="form-group">
            <label for="topic">Topic</label>
            <select class="form-control" id="topic" name="topic" required>
                <option value="" disabled selected>Select a Topic</option>
                @foreach ($topics as $topic)
                    <option value="{{ $topic->id }}">{{ $topic->name }} <span class="badge badge-{{ $topic->status == 'closed' ? 'danger' : 'success' }}">{{ ucfirst($topic->status) }}</span></option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="event_date">Event Date</label>
            <input type="date" class="form-control" id="event_date" name="event_date" value="{{ old('event_date') }}" required>
        </div>

        <div class="form-group">
            <label for="charges">Charges</label>
            <input type="number" class="form-control" id="charges" name="charges" value="{{ old('charges') }}" required>
        </div>

        <div class="form-group">
            <label for="contact_number">Contact Number</label>
            <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{ old('contact_number') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Create Event</button>
    </form>

</div>
@endsection
