<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internet Speed Test</title>
</head>
<body>
    <h2>Internet Speed Test</h2>
    <p>Download Speed: <span id="downloadSpeed">Calculating...</span> Mbps</p>
    <p>Upload Speed: <span id="uploadSpeed">Calculating...</span> Mbps</p>

    <script>
        function measureDownloadSpeed() {
            let startTime, endTime;
            let imageUrl = "https://speed.hetzner.de/100MB.bin"; // Large file for accurate speed test
            let downloadSize = 100 * 1024 * 1024; // 100MB in bytes

            startTime = new Date().getTime();
            let download = new Image();
            download.onload = function () {
                endTime = new Date().getTime();
                let duration = (endTime - startTime) / 1000; // Convert to seconds
                let bitsLoaded = downloadSize * 8;
                let speedMbps = (bitsLoaded / duration) / (1024 * 1024);
                document.getElementById("downloadSpeed").innerText = speedMbps.toFixed(2);
            };
            download.src = imageUrl + "?t=" + startTime; // Prevent caching
        }

        function measureUploadSpeed() {
            let startTime, endTime;
            let data = new Blob(["a".repeat(2 * 1024 * 1024)]); // 2MB blob
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "/upload-speed-test", true);
            startTime = new Date().getTime();
            xhr.onload = function () {
                endTime = new Date().getTime();
                let duration = (endTime - startTime) / 1000; // Convert to seconds
                let bitsUploaded = data.size * 8;
                let speedMbps = (bitsUploaded / duration) / (1024 * 1024);
                document.getElementById("uploadSpeed").innerText = speedMbps.toFixed(2);
            };
            xhr.send(data);
        }

        // Initial measurements
        measureDownloadSpeed();
        measureUploadSpeed();
    </script>
</body>
</html>
