<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deployment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        {{-- <h1 class="text-4xl font-bold mb-4 text-center text-blue-600">🚀 Lets Deploy on Friday or even Weekends! 🚀</h1> --}}
        <div class="text-2xl font-bold mb-8 text-center text-purple-600" id="deploymentMessage">
            {{ $deploymentMessage }}
        </div>
        <div class="flex space-x-4 mb-4">
            <button id="deployButton" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Start Deployment
            </button>
            <button id="revertButton" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded hidden">
                Revert to Previous Commit
            </button>
        </div>
        <div id="progressContainer" class="hidden">
            <div id="progressBar" class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
            </div>
            <div id="progressPercentage" class="text-right mt-1">0%</div>
        </div>
        <div id="completionMessage" class="mt-4 p-4 bg-green-100 rounded hidden">
            Deployment completed successfully!
        </div>
        <div id="results" class="mt-4"></div>

        <!-- Pending Commits -->
        {{-- <div class="mt-8">
            <h2 class="text-2xl font-bold mb-4">Pending Commits</h2>
            <div id="pendingCommits">
                @foreach($pendingCommits as $commit)
                    <div class="bg-white p-4 rounded shadow mb-4">
                        <h3 class="font-bold">{{ $commit['message'] }}</h3>
                        <p>Author: {{ $commit['author'] }}</p>
                        <p>Hash: {{ $commit['hash'] }}</p>
                        <h4 class="font-bold mt-2">Affected Files:</h4>
                        <ul>
                            @foreach($commit['files'] as $file)
                                <li>{{ $file }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div> --}}

        <!-- System Health -->
        <div class="mt-8">
            <h2 class="text-2xl font-bold mb-4">System Health</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($systemHealth as $metric => $value)
                    <div class="bg-white p-4 rounded shadow">
                        <h3 class="font-bold mb-2">{{ ucwords(str_replace('_', ' ', $metric)) }}</h3>
                        <div class="text-2xl">{{ $value }}%</div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $value }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>



        <!-- Git Commit History -->
        <div class="mt-8">
            <h2 class="text-2xl font-bold mb-4">Recent Git Commits</h2>
            <ul class="bg-white rounded shadow p-4">
                @foreach($gitHistory as $commit)
                    <li class="mb-2">{{ $commit }}</li>
                @endforeach
            </ul>
        </div>

        <!-- Deployment Duration Graph -->
        {{-- <div class="mt-8">
            <h2 class="text-2xl font-bold mb-4">Deployment Durations</h2>
            <canvas id="deploymentDurationChart"></canvas>
        </div> --}}
    </div>

    <script>
        //

        const deployButton = document.getElementById('deployButton');
        const revertButton = document.getElementById('revertButton');
        const progressContainer = document.getElementById('progressContainer');
        const progressBar = document.querySelector('#progressBar div');
        const progressPercentage = document.getElementById('progressPercentage');
        const completionMessage = document.getElementById('completionMessage');
        const resultsContainer = document.getElementById('results');

        function updateProgress(current, total) {
            const progress = (current / total) * 100;
            progressBar.style.width = `${progress}%`;
            progressPercentage.textContent = `${Math.round(progress)}%`;
        }

        function deploy() {
            deployButton.disabled = true;
            revertButton.classList.add('hidden');
            progressContainer.classList.remove('hidden');
            completionMessage.classList.add('hidden');
            resultsContainer.innerHTML = '';

            fetch('/deploy', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ resolvedConflicts: {} }) // Add this line to send resolved conflicts
            })
            .then(response => response.json())
            .then(data => {
                data.results.forEach((result, index) => {
                    setTimeout(() => {
                        updateProgress(index + 1, data.steps.length);

                        const resultElement = document.createElement('div');
                        resultElement.className = `p-4 mb-2 ${result.status === 'success' ? 'bg-green-100' : 'bg-red-100'}`;
                        resultElement.innerHTML = `
                            <h3 class="font-bold">${data.steps[index]}</h3>
                            <p>${result.message}</p>
${result.details ? `<pre class="mt-2 bg-gray-100 p-2 rounded">${result.details}</pre>` : ''}
                        `;
                        resultsContainer.appendChild(resultElement);

                        if (index === data.results.length - 1) {
                            deployButton.disabled = false;
                            deployButton.textContent = 'Redeploy';
                            revertButton.classList.remove('hidden');
                            completionMessage.classList.remove('hidden');
                        }
                    }, index * 1000);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                resultsContainer.innerHTML = '<div class="bg-red-100 p-4">An error occurred during deployment.</div>';
                deployButton.disabled = false;
            });
        }

        function revert() {
            deployButton.disabled = true;
            revertButton.disabled = true;
            progressContainer.classList.remove('hidden');
            completionMessage.classList.add('hidden');
            resultsContainer.innerHTML = '';

            fetch('/revert', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                data.results.forEach((result, index) => {
                    setTimeout(() => {
                        updateProgress(index + 1, data.steps.length);

                        const resultElement = document.createElement('div');
                        resultElement.className = `p-4 mb-2 ${result.status === 'success' ? 'bg-green-100' : 'bg-red-100'}`;
                        resultElement.innerHTML = `
                            <h3 class="font-bold">${data.steps[index]}</h3>
                            <p>${result.message}</p>
                            ${result.details ? `<pre class="mt-2 bg-gray-100 p-2 rounded">${result.details}</pre>` : ''}
                        `;
                        resultsContainer.appendChild(resultElement);

                        if (index === data.results.length - 1) {
                            deployButton.disabled = false;
                            revertButton.disabled = false;
                            completionMessage.textContent = 'Revert completed successfully!';
                            completionMessage.classList.remove('hidden');
                        }
                    }, index * 1000);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                resultsContainer.innerHTML = '<div class="bg-red-100 p-4">An error occurred during revert.</div>';
                deployButton.disabled = false;
                revertButton.disabled = false;
            });
        }

        deployButton.addEventListener('click', deploy);
        revertButton.addEventListener('click', revert);

        // Conflict Resolution
        function showConflicts(conflicts) {
            const conflictContainer = document.createElement('div');
            conflictContainer.className = 'mt-4 p-4 bg-yellow-100 rounded';
            conflictContainer.innerHTML = '<h3 class="font-bold mb-2">Conflicts Detected</h3>';

            for (const [file, conflictContent] of Object.entries(conflicts)) {
                const fileConflict = document.createElement('div');
                fileConflict.className = 'mb-4';
                fileConflict.innerHTML = `
                    <h4 class="font-bold">${file}</h4>
                    <pre class="bg-white p-2 rounded mt-2">${conflictContent}</pre>
                    <div class="mt-2">
                        <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded mr-2" onclick="resolveConflict('${file}', 'ours')">Use Ours</button>
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded mr-2" onclick="resolveConflict('${file}', 'theirs')">Use Theirs</button>
                        <button class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-1 px-2 rounded" onclick="resolveConflict('${file}', 'merge')">Merge</button>
                    </div>
                `;
                conflictContainer.appendChild(fileConflict);
            }

            resultsContainer.appendChild(conflictContainer);
        }

        function resolveConflict(file, resolution) {
            // In a real application, you'd send this resolution to the server
            console.log(`Resolving conflict in ${file} with resolution: ${resolution}`);
            // For now, we'll just remove the conflict UI
            document.querySelector(`[onclick="resolveConflict('${file}', 'ours')"]`).closest('.mb-4').remove();
        }

        // Fun deployment messages
        function updateDeploymentMessage() {
            const messages = [
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
            const messageElement = document.getElementById('deploymentMessage');
            messageElement.textContent = messages[Math.floor(Math.random() * messages.length)];
        }

        // Update deployment message every 10 seconds
        setInterval(updateDeploymentMessage, 10000);

        // Alert System
        function showAlert(message, type = 'info') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `fixed top-4 right-4 p-4 rounded shadow ${type === 'error' ? 'bg-red-500' : 'bg-blue-500'} text-white`;
            alertDiv.textContent = message;
            document.body.appendChild(alertDiv);
            setTimeout(() => alertDiv.remove(), 5000);
        }

        // Example usage of alert system
        deployButton.addEventListener('click', () => {
            showAlert('Deployment started');
        });

        revertButton.addEventListener('click', () => {
            showAlert('Revert process initiated', 'error');
        });
    </script>
</body>
</html>
