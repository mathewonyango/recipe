@extends('layout.default')

@section('content')
<h1>Database Backups</h1>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<a href="{{ route('trigger-backup') }}" class="btn btn-primary">Trigger Backup</a>

@if ($files->isEmpty())
    <p>No backups found.</p>
@else
    <table class="table">
        <thead>
            <tr>
                <th>Filename</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($files as $file)
                <tr>
                    <td>{{ $file['filename'] }}</td>
                    <td>{{ date('Y-m-d H:i:s', $file['created_at']) }}</td>
                    <td>
                        <a href="{{ $file['path'] }}" class="btn btn-success">Download</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
@endsection
