<!-- resources/views/log_view.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Log View</title>
    <style>
        pre {
            white-space: pre-wrap; /* To preserve whitespace and line breaks */
        }
    </style>
</head>
<body>
    <h1>Log File</h1>
    <pre>{{ $logContent }}</pre>
</body>
</html>
