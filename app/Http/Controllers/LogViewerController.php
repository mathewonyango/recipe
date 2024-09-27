<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LogViewerController extends Controller
{
    //


    public function index()
    {
        // Path to log files
        $logPath = storage_path('logs/laravel.log');

        // Read the log file content
        $logContent = File::get($logPath);

        // Split content by lines and reverse it for latest logs first
        $logs = array_reverse(explode("\n", $logContent));

        // Filter only the error lines
        $errorLogs = array_filter($logs, function ($line) {
            return strpos($line, 'ERROR') !== false;
        });

        // Get the last three error logs
        $lastThreeErrors = array_slice($errorLogs, 0, 3);

        // Format logs (optional)
        $formattedLogs = $this->formatLogs($lastThreeErrors);

        return view('logviewer.index', ['logs' => $formattedLogs]);
    }

    // protected function formatLogs(array $logs)
    // {
    //     // Implement your formatting logic here
    //     // This function can format or manipulate the logs as needed for display
    //     return $logs;
    // }



    private function formatLogs($logs)
    {
        $formatted = [];
        foreach ($logs as $log) {
            if (!empty(trim($log))) {
                $parts = preg_split('/\]\s+/', $log, 2);
                if (count($parts) === 2) {
                    $date = trim($parts[0], '[]');
                    $message = $parts[1];
                    $formatted[] = ['date' => $date, 'message' => $message];
                }
            }
        }
        return $formatted;
    }

    public function show($fileName)
    {
        $filePath = storage_path('logs/' . $fileName);

        if (!File::exists($filePath)) {
            abort(404);
        }

        $fileContents = File::get($filePath);

        return view('logviewer.show', compact('fileContents', 'fileName'));
    }

    public function showLog()
    {
        $logFile = storage_path('app/public/git_pull.log');

        if (File::exists($logFile)) {
            $logContent = File::get($logFile);
        } else {
            $logContent = 'Log file not found.';
        }
        return view('logviewer.deployment-logs',['logContent' => $logContent]);

    }

}
