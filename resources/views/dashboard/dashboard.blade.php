@extends('layout.default')

@section('content')
    <!-- Custom CSS for styling -->
    <div class="container-fluid">



        <div class="dashboard-header">
            <h1>Dashboard</h1>
            <input type="text" placeholder="Search here...">
            <div class="user-info">
                <span>Eng (US)</span>
                <i class="fas fa-bell ml-3"></i>
                <span class="ml-3">{{ Auth::user()->name }}</span>
                <img src="{{ URL::asset('assets/images/man.png') }}" alt="User Avatar" class="avatar-md rounded-circle mb-2"/>
            </div>
        </div>

        <div class="container">
            <!-- Sales Summary -->
            <div class="sales-summary">
                <div class="sales-card" style="background-color: #fee2e2;">
                    <div class="icon-box"><i class="fas fa-users"></i></div>
                    <h3>Total Chefs</h3>
                    <h2>1k</h2>
                    <br>
                    <div style="font-size: 12px; color: #555; margin-top: 10px;">
                        <div><strong>Approved:</strong> 3</div> <!-- Example number of Admins -->
                        <div><strong>Pending:</strong> 2</div> <!-- Example number of Super Admins -->
                    </div>

                </div>
                <div class="sales-card" style="background-color: #e9effe;">
                    <div class="icon-box"><i class="fas fa-utensils"></i></div>
                    <h3>Total Recipes</h3>
                    <h2>300</h2>
                    <span>+15% from yesterday</span>
                </div>
                <div class="sales-card" style="background-color: #d1f8de;">
                    <div class="icon-box"><i class="fas fa-comments"></i></div>
                    <h3>Total Topics</h3>
                    <h2>5</h2>
                    <span>+12% from yesterday</span>
                </div>
                <div class="sales-card" style="background-color: #fff9db;">
                    <div class="icon-box"><i class="fas fa-thumbs-up"></i></div>
                    <h3>Total Votes</h3>
                    <h2>8</h2>
                    <span>+0.5% from yesterday</span>
                </div>

                <div class="sales-card" style="background-color: #d1f8de;">
                    <div class="icon-box"><i class="fas fa-clock"></i></div> <!-- Change the icon to represent 'In Progress' -->
                    <h3>App Users</h3>
                    <h2>100</h2> <!-- Example number for registered voters -->
                    <span>Conversion Rate: 8% </span><!-- You can adjust this percentage based on actual data -->
                </div>
                <div class="sales-card" style="background-color: #e0f7fa;"> <!-- New background color -->
                    <div class="icon-box"><i class="fas fa-user-shield"></i></div> <!-- Change the icon to represent users -->
                    <h3>System Users</h3>
                    <h2>5</h2> <!-- Example number for registered users -->

                    <!-- Breakdown of User Roles -->
                    <br>
                    <div style="font-size: 12px; color: #555; margin-top: 10px;">
                        <div><strong>Admin:</strong> 3</div> <!-- Example number of Admins -->
                        <div><strong>Super Admin:</strong> 2</div> <!-- Example number of Super Admins -->
                    </div>
                </div>


            </div>

            <!-- Charts -->
            <div class="row">
                <div class="col-md-6 chart-card">
                    <h3>Recipe Submissions Over Time</h3>
                    <canvas id="recipeChart" class="chart-placeholder"></canvas>
                </div>
                <div class="col-md-6 chart-card">
                    <h3>User Votes Growth</h3>
                    <canvas id="votesChart" class="chart-placeholder"></canvas>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 chart-card">
                    <h3>Voters  Ratings Per Recipe</h3>
                    <canvas id="satisfactionChart" class="chart-placeholder"></canvas>
                </div>
                <div class="col-md-6 chart-card">
                    <h3>Topics Engagement Over Month</h3>
                    <canvas id="topicsChart" class="chart-placeholder"></canvas>
                </div>
            </div>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Chart.js Data and Initialization -->
        <script>
            // Recipe Submissions (Bar Chart)
            var recipeCtx = document.getElementById('recipeChart').getContext('2d');
            var recipeChart = new Chart(recipeCtx, {
                type: 'bar',
                data: {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5'],
                    datasets: [{
                        label: 'Recipes Submitted',
                        data: [10, 20, 30, 40, 50],
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                }
            });

            // User Votes Growth (Line Chart)
            var votesCtx = document.getElementById('votesChart').getContext('2d');
            var votesChart = new Chart(votesCtx, {
                type: 'line',
                data: {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5'],
                    datasets: [{
                        label: 'Votes',
                        data: [5, 10, 15, 20, 30],
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                }
            });

            // Customer Satisfaction (Line Chart)
            var satisfactionCtx = document.getElementById('satisfactionChart').getContext('2d');
            var satisfactionChart = new Chart(satisfactionCtx, {
                type: 'line',
                data: {
                    labels: ['Recipe 01', 'Recipe 02', 'Recipe 03', 'Recipe 04', 'Recipe 05', 'Recipe 06'],
                    datasets: [{
                        label: 'Satisfaction',
                        data: [3.5, 4.0, 4.5, 4.2, 4.8, 4.9],
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                }
            });

            // Topics Engagement (Bar Chart)
            var topicsCtx = document.getElementById('topicsChart').getContext('2d');
            var topicsChart = new Chart(topicsCtx, {
                type: 'bar',
                data: {
                    labels: ['Topic 1', 'Topic 2', 'Topic 3', 'Topic 4', 'Topic 5'],
                    datasets: [{
                        label: 'Engagement',
                        data: [40, 60, 80, 50, 90],
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                }
            });
        </script>
    </div>
@endsection
