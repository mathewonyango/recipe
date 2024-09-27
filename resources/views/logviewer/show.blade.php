<!DOCTYPE html>
<html>
<head>
    <title>Log File - {{ $fileName }}</title>
</head>
<body>
    <h1>{{ $fileName }}</h1>
    <pre>{{ $fileContents }}</pre>
    <a href="{{ url('/console') }}">Back to log list</a>
</body>
</html>
