<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Viewer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .log-entry {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .log-entry.error {
            background-color: #f8d7da;
        }
        .log-entry.warning {
            background-color: #fff3cd;
        }
        .log-entry.info {
            background-color: #d1ecf1;
        }
        .log-entry .date {
            font-weight: bold;
            display: block;
        }
    </style>
</head>
<body>
    <h1>Log Entries</h1>
    @foreach ($logs as $log)
        <div class="log-entry">
            <span class="date">{{ $log['date'] }}</span>
            <span class="message">{{ $log['message'] }}</span>
        </div>
    @endforeach
</body>
</html>
