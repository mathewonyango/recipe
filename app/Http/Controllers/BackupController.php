<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class BackupController extends Controller
{
    // Function to trigger the backup
    public function triggerBackup()
    {
        Artisan::call('backup:run --only-db');
        return redirect()->route('view-backups')->with('success', 'Database backup triggered successfully!');
    }

    // Function to list all backups
    public function viewBackups()
    {
        // Define the path where backups are stored
        $backupPath = storage_path('app/recipe');

        // Check if the directory exists
        if (!File::exists($backupPath)) {
            return view('backups.index', ['files' => collect()]); // Return an empty collection if directory doesn't exist
        }


        // Get the files and map them with necessary information
        $files = collect(File::files($backupPath))->map(function ($file) {
            return [
                'filename' => $file->getFilename(),
                'path' => url('storage/recipe/' . $file->getFilename()),
                'created_at' => $file->getCTime(),
            ];
        })->sortByDesc('created_at');

        // Pass the files to the view
        return view('backups.index', ['files' => $files]);
    }

}

