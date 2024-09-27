<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DeploymentController extends Controller
{
    private $deploymentPath = 'C:\web\recipe';

    public function index()
    {
        $deploymentHistory = $this->getDeploymentHistory();
        $systemHealth = $this->getSystemHealth();
        $gitHistory = $this->getGitHistory();
        $deploymentDurations = $this->getDeploymentDurations();
        $pendingCommits = $this->getPendingCommits();
        $deploymentMessage = $this->getDeploymentMessage();

        return view('deployment.deploy', compact('deploymentHistory', 'systemHealth', 'gitHistory', 'deploymentDurations', 'pendingCommits', 'deploymentMessage'));
    }

    public function deploy(Request $request)
    {
        $steps = [
            'Navigating to project directory',
            'Pulling latest changes from Git',
            'Resolving conflicts (if any)',
            'Clearing application cache',
            'Clearing configuration cache',
            'Clearing view cache',
            'Restarting queue worker'
        ];

        $results = [];

        try {
            chdir($this->deploymentPath);
            $results[] = ['status' => 'success', 'message' => 'Successfully navigated to project directory'];

            $gitOutput = $this->runCommand('git pull');
            $results[] = ['status' => 'success', 'message' => 'Git pull successful', 'details' => $gitOutput];

            // Check for conflicts
            $conflicts = $this->checkForConflicts();
            if (!empty($conflicts)) {
                $resolvedConflicts = $request->input('resolvedConflicts', []);
                $this->resolveConflicts($conflicts, $resolvedConflicts);
                $results[] = ['status' => 'success', 'message' => 'Conflicts resolved', 'details' => 'Resolved conflicts in: ' . implode(', ', array_keys($resolvedConflicts))];
            } else {
                $results[] = ['status' => 'success', 'message' => 'No conflicts detected'];
            }

            Artisan::call('cache:clear');
            $results[] = ['status' => 'success', 'message' => 'Application cache cleared'];

            Artisan::call('config:clear');
            $results[] = ['status' => 'success', 'message' => 'Configuration cache cleared'];

            Artisan::call('view:clear');
            $results[] = ['status' => 'success', 'message' => 'View cache cleared'];

            Artisan::call('queue:restart');
            $results[] = ['status' => 'success', 'message' => 'Queue worker restarted'];

        } catch (\Exception $e) {
            $results[] = ['status' => 'error', 'message' => 'Deployment failed: ' . $e->getMessage()];
        }

        return response()->json(['steps' => $steps, 'results' => $results]);
    }

    public function revert()
    {
        $steps = [
            'Navigating to project directory',
            'Reverting to previous commit',
            'Clearing application cache',
            'Clearing configuration cache',
            'Clearing view cache',
            'Restarting queue worker'
        ];

        $results = [];

        try {
            chdir($this->deploymentPath);
            $results[] = ['status' => 'success', 'message' => 'Successfully navigated to project directory'];

            $revertOutput = $this->runCommand('git reset --hard HEAD~1');
            $results[] = ['status' => 'success', 'message' => 'Reverted to previous commit', 'details' => $revertOutput];

            Artisan::call('cache:clear');
            $results[] = ['status' => 'success', 'message' => 'Application cache cleared'];

            Artisan::call('config:clear');
            $results[] = ['status' => 'success', 'message' => 'Configuration cache cleared'];

            Artisan::call('view:clear');
            $results[] = ['status' => 'success', 'message' => 'View cache cleared'];

            Artisan::call('queue:restart');
            $results[] = ['status' => 'success', 'message' => 'Queue worker restarted'];

        } catch (\Exception $e) {
            $results[] = ['status' => 'error', 'message' => 'Revert failed: ' . $e->getMessage()];
        }

        return response()->json(['steps' => $steps, 'results' => $results]);
    }

    private function runCommand($command)
    {
        $process = Process::fromShellCommandline($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process->getOutput();
    }

    private function getDeploymentHistory()
    {
        // In a real application, you'd fetch this from a database
        return [
            ['date' => '2024-08-28', 'type' => 'deploy', 'status' => 'success'],
            ['date' => '2024-08-27', 'type' => 'revert', 'status' => 'success'],
            ['date' => '2024-08-26', 'type' => 'deploy', 'status' => 'failed'],
        ];
    }

    private function getSystemHealth()
    {
        // In a real application, you'd fetch this data from your monitoring system
        return [
            'cpu_usage' => random_int(10, 90),
            'memory_usage' => random_int(20, 80),
            'disk_usage' => random_int(30, 70),
            'app_errors' => random_int(0, 10),
        ];
    }

    private function getGitHistory()
    {
        chdir($this->deploymentPath);
        $output = $this->runCommand('git log --pretty=format:"%h - %s" -n 5');
        return array_filter(explode("\n", $output));
    }

    private function getDeploymentDurations()
    {
        // In a real application, you'd fetch this from a database
        return [
            ['date' => '2024-08-24', 'duration' => 45],
            ['date' => '2024-08-25', 'duration' => 60],
            ['date' => '2024-08-26', 'duration' => 30],
            ['date' => '2024-08-27', 'duration' => 55],
            ['date' => '2024-08-28', 'duration' => 40],
        ];
    }

    private function getPendingCommits()
    {
        chdir($this->deploymentPath);
        $output = $this->runCommand('git log origin/main..HEAD --pretty=format:"%h|%an|%s"');
        $commits = array_filter(explode("\n", $output));

        $pendingCommits = [];
        foreach ($commits as $commit) {
            list($hash, $author, $message) = explode('|', $commit, 3);
            $files = $this->getAffectedFiles($hash);
            $pendingCommits[] = [
                'hash' => $hash,
                'author' => $author,
                'message' => $message,
                'files' => $files
            ];
        }

        return $pendingCommits;
    }

    private function getAffectedFiles($commitHash)
    {
        $output = $this->runCommand("git show --name-only --pretty=format:'' $commitHash");
        return array_filter(explode("\n", $output));
    }

    private function checkForConflicts()
    {

        $output = $this->runCommand('git diff --name-only --diff-filter=U');
        $conflictingFiles = array_filter(explode("\n", $output));

        $conflicts = [];
        foreach ($conflictingFiles as $file) {
            $content = file_get_contents($file);
            preg_match_all('/<<<<<<< HEAD.*?=======.*?>>>>>>>/s', $content, $matches);
            $conflicts[$file] = $matches[0];
        }

        return $conflicts;
    }

    private function resolveConflicts($conflicts, $resolvedConflicts)
    {
        foreach ($resolvedConflicts as $file => $resolution) {
            $content = file_get_contents($file);
            $content = preg_replace('/<<<<<<< HEAD.*?=======\n(.*?)>>>>>>>/s', '$1', $content);
            file_put_contents($file, $content);
            $this->runCommand("git add $file");
        }
        $this->runCommand('git commit -m "Resolved merge conflicts"');
    }

    private function getDeploymentMessage()
    {
        $messages = [
            "Let's deploy on Friday without fear! YOLO (You Obviously Love Optimization)!",
            "Time to ship some code and break the internet... in a good way!",
            "Buckle up, buttercup! We're about to make the servers dance!",
            "Warning: Deployment in progress. Expect awesomeness in 3... 2... 1...",
            "Initiating deployment sequence. May the code be with you!",
            "Ready to turn coffee into code? Let's deploy this bad boy!",
            "Deploying faster than a speeding bullet! Superman's got nothing on us!",
            "Prepare for trouble, make it double! Team Rocket's deploying at the speed of light!",
            "Hold onto your bits! We're about to go plaid with this deployment!",
            "Roses are red, violets are blue, let's deploy this code, and pray it goes through!"
        ];
        return $messages[array_rand($messages)];
    }
}
