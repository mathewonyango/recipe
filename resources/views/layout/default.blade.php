<!DOCTYPE html>
<html lang="en">

@include('partials.head')

<body>
    <style>
        .highlight-text {
            font-weight: bold;
            color: blue;
            padding: 2px 5px;
            border-radius: 3px;
        }
    </style>

    <style>
        .pagination-wrapper {
            position: relative;
            width: 100%;
            padding: 10px 0;
        }

        .pagination-simple {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .pagination-simple a,
        .pagination-simple span {
            padding: 8px 16px;
            border: none;
            border-radius: 20px;
            color: #ffffff;
            background-color: #3498db;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .pagination-simple a:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
        }

        .pagination-simple .disabled {
            background-color: #bdc3c7;
            color: #7f8c8d;
            pointer-events: none;
        }

        .pagination-simple .prev {
            margin-right: auto;
        }

        .pagination-simple .next {
            margin-left: auto;
        }
        .blink {
    animation: blinker 1s linear infinite;
}

@keyframes blinker {
    50% {
        opacity: 0;
    }
}

    </style>


    <style>
        /* ... (keep the existing styles) ... */

        /* Add these new styles for the loader */
        .loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(110, 142, 251, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loader {
            text-align: center;
        }

        .loader-emoji {
            font-size: 80px;
            animation: pulse 1s infinite alternate;
        }

        .loader-text {
            color: white;
            font-size: 24px;
            margin-top: 20px;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            100% {
                transform: scale(1.2);
            }
        }
    </style>
     <style>
        body {
            background-color: #f7f8fc;
            font-family: 'Arial', sans-serif;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: white;
            border-bottom: 1px solid #eee;
        }

        .dashboard-header h1 {
            font-size: 24px;
            margin: 0;
            color: #3a4b6b;
        }

        .dashboard-header input {
            border-radius: 20px;
            padding: 10px 20px;
            width: 300px;
            border: 1px solid #ddd;
        }

        .dashboard-header .user-info {
            display: flex;
            align-items: center;
        }

        .user-info img {
            border-radius: 50%;
            margin-left: 15px;
        }

        .sales-summary {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }

        .sales-card {
            background-color: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            text-align: center;
            flex: 1;
            margin-right: 20px;
            position: relative;
        }

        .sales-card h3 {
            font-size: 16px;
            color: #777;
        }

        .sales-card h2 {
            font-size: 28px;
            margin: 10px 0;
            color: #3a4b6b;
        }

        .sales-card span {
            color: green;
        }

        .sales-card:last-child {
            margin-right: 0;
        }

        .icon-box {
            position: absolute;
            top: 10px;
            left: 20px;
            font-size: 24px;
            color: rgba(0, 0, 0, 0.1);
        }

        .chart-card {
            background-color: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .chart-card h3 {
            font-size: 18px;
            color: #555;
        }

        .chart-placeholder {
            height: 250px;
        }


    </style>
    <style>
        .dashboard-header {
    position: fixed; /* Use sticky instead of fixed to stay within the content area */
    top: 0;
    left: 250px;
    right: 0;
    background-color: white; /* Adjust based on your design */
    z-index: 1000; /* Keep it above other content */
    margin-bottom: 0; /* Remove any margin below the header */

    padding: 10px; /* Reduced padding */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.container-fluid {
    padding-top: 10px; /* Reduced top padding */
}

/* .table-wrapper {
    max-height: 500px; /* Set a max height for the scrolling area */
    /* overflow-y: auto; */
     /* Enable vertical scrolling */
/* }  */
.container-fluid {
    margin-top: 5px; /* Adjust this value as needed to reduce space */
}

.dashboard-header {
    margin-bottom: 0; /* Ensure no extra space below the header */
}


        </style>


    <header>
        <h1>
            @if (isset($title))
                {{ $title }}&nbsp;-&nbsp;
            @endif
            <img src="{{ URL::asset('assets/images/man.png') }}" alt="Housing Management System" height="24px">
            {{ config('app.name') }}
        </h1>
    </header>

    <div id="wrapper">
        @include('partials.topnav')
        @include('partials.sidebar')

        <div class="content-page">
            <div class="content">
                <div class="dashboard-header">
                    <h1>Dashboard</h1>
                    <input type="text" id="searchInput" placeholder="Search here..." onkeyup="filterTable()" style="margin-bottom: 10px;">
                    <div class="user-info">
                        <span>Eng (US)</span>
                        <i class="fas fa-bell ml-3"></i>
                        <span class="ml-3">{{ Auth::user()->name }}</span>
                        <img src="{{ URL::asset('assets/images/man.png') }}" alt="User Avatar" class="avatar-md rounded-circle mb-2"/>
                    </div>
                </div>
                <script>
                    function filterTable() {
    const input = document.getElementById("searchInput");
    const filter = input.value.toLowerCase();
    const table = document.getElementById("table");
    const rows = table.getElementsByTagName("tr");

    for (let i = 1; i < rows.length; i++) { // Start at 1 to skip the header
        const cells = rows[i].getElementsByTagName("td");
        let rowContainsSearchTerm = false;

        for (let j = 0; j < cells.length; j++) {
            const cell = cells[j];
            if (cell) {
                const textValue = cell.textContent || cell.innerText;
                if (textValue.toLowerCase().indexOf(filter) > -1) {
                    rowContainsSearchTerm = true;
                    break; // Stop searching this row if we found a match
                }
            }
        }

        rows[i].style.display = rowContainsSearchTerm ? "" : "none"; // Show or hide the row
    }
}

                    </script>

                <div class="loader-overlay" id="loader">
                    <div class="loader d-flex flex-column justify-content-center align-items-center">
                        <img src="{{ asset('assets/images/flink_logo.png') }}" alt="Flink Network Logo"
                            class="img-fluid rounded-circle mb-3 blink" style="width: 80px;">
                        {{-- <div class="loader-emoji display-4 mb-2 blink">🌐</div> --}}
                        <div class="loader-text h4">Loading ...</div>
                    </div>
                </div>


                <!-- Start Content-->
                <div class="container-fluid">
                    @include('partials.alerts')

                    @yield('breadcrumb')
                    @yield('content')

                </div>
            </div>

            {{-- @include('partials.footer') --}}
        </div>
    </div>

    @include('partials.footer-script')
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace();
    </script>
</body>

</html>
