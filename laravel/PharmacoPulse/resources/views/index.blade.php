<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
	<!-- My CSS -->
	<link rel="stylesheet" href="style.css">
	<title>MediAI</title>
</head>
<body>

	<!-- SIDEBAR -->
	<!-- SIDEBAR -->
<section id="sidebar">
<a href="{{ url('/redirects') }}" class="brand" style="display: flex; align-items: center; justify-content: flex-start;">
    <img src="img/logo.png" alt="Logo" style="height: 60px; width: auto; display: block;">
    <span class="text" style="
        font-size: 24px; 
        font-weight: 700; 
        color: var(--blue); 
        font-family: var(--poppins); 
        text-transform: uppercase; 
        letter-spacing: 1px; 
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        margin-left: 10px;
    ">MediAI</span>
</a>


    </a>
    <ul class="side-menu top">
        <li class="active">
            <a href="{{ url('/redirects') }}">
                <i class='bx bxs-dashboard'></i>
                <span class="text">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{ route('patients.index') }}">
                <i class='bx bxs-user'></i>
                <span class="text">Patients</span>
            </a>
        </li>
        <li>
            <a href="{{ route('drugs.index') }}">
                <i class='bx bxs-capsule'></i>
                <span class="text">Drugs</span>
            </a>
        </li>
        <li>
            <a href="{{ route('prescriptions.index') }}">
                <i class='bx bx-qr-scan'></i>
                <span class="text">Prescriptions</span>
            </a>
        </li>
        <li>
            <a href="{{ route('clinic.index') }}">
                <i class='bx bxs-group'></i>
                <span class="text">Clinic</span>
            </a>
        </li>
        <li>
            <a href="{{ url('/medicine-usage-chart') }}">
                <i class='bx bx-line-chart'></i>
                <span class="text">Analytics</span>
            </a>
        </li>

        <!-- Only display "Users" link if the logged-in user is an admin -->
        @if(auth()->check() && auth()->user()->user_type == 'Admin')
        <li>
            <!-- Instead of going to the users list, redirect to the register page -->
            <a href="{{ route('users.index') }}">
                <i class='bx bxs-group'></i>
                <span class="text">Users</span>
            </a>
        </li>
        @endif
    </ul>
    <ul class="side-menu">
        <li>
            <a href="{{ url('/setting') }}">
                <i class='bx bxs-cog'></i>
                <span class="text">Settings</span>
            </a>
        </li>
        <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-dropdown-link :href="route('logout')"
                    onclick="event.preventDefault();
                             this.closest('form').submit();">
                    <i class='bx bx-log-out' style='color: red;'></i>
                    <span style='color: red;'>{{ __('Log Out') }}</span>
                </x-dropdown-link>
            </form>
        </li>
    </ul>
</section>
<!-- SIDEBAR -->

	<!-- SIDEBAR -->

	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu'></i>
			<a href="#" class="nav-link"></a>
			<form action="#">
				<div class="form-input">
					<input type="search" placeholder="Search...">
					<button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
				</div>
			</form>
			<input type="checkbox" id="switch-mode" hidden>
			<label for="switch-mode" class="switch-mode"></label>
			<a href="#" class="profile"></a>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Dashboard</h1>
				</div>
			</div>
			<ul class="box-info">
				<li>
					<i class='bx bxs-capsule'></i>
					<span class="text">
						<h3>{{ $drugCount }}</h3>
						<p>Drugs</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-group'></i>
					<span class="text">
						<h3>{{ $patientCount }}</h3>
						<p>Patients</p>
					</span>
				</li>
				@if(auth()->check() && auth()->user()->user_type == 'Admin')
				<li>
					<i class='bx bxs-group'></i>
					<span class="text">
						<h3>{{ $userCount }}</h3>
						<p>Users</p>
					</span>
				</li>
				@endif
			</ul>
			<br>
			<br>
			<br>

			<!-- Medicine Usage Chart -->
			<div class="chart-container">
    <h2 style="text-align:center; margin: 2%;">Monthly Patient Count per Drug Usage</h2>
    <canvas id="medicineUsageChart"></canvas>
</div>


		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->

	<!-- Chart.js -->
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

	<!-- Medicine Usage Chart Script -->
	<script>
		// Debugging: Check if chartData is correctly passed
		var chartData = @json($chartData);
		console.log('Chart Data:', chartData);

		var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
		var labels = monthNames;

		var datasets = [];
		var colors = [
			'rgba(255, 99, 132, 0.2)',  // Red
    'rgba(54, 162, 235, 0.2)',  // Blue
    'rgba(255, 206, 86, 0.2)',  // Yellow
    'rgba(75, 192, 192, 0.2)',  // Green
    'rgba(153, 102, 255, 0.2)', // Purple
    'rgba(255, 159, 64, 0.2)',  // Orange
    'rgba(199, 199, 199, 0.2)', // Grey
    'rgba(255, 99, 71, 0.2)',   // Tomato
    'rgba(148, 0, 211, 0.2)',   // DarkViolet
    'rgba(124, 252, 0, 0.2)',   // LawnGreen
    'rgba(0, 191, 255, 0.2)',   // DeepSkyBlue
    'rgba(255, 20, 147, 0.2)'   // DeepPink
		];

		// Create a unique list of drug names
		var drugNames = new Set();
		Object.values(chartData).forEach(monthData => {
			Object.keys(monthData).forEach(drugName => {
				drugNames.add(drugName);
			});
		});

		// Create dataset for each drug
		Array.from(drugNames).forEach((drugName, index) => {
			var data = labels.map(month => (chartData[month] && chartData[month][drugName]) || 0);

			datasets.push({
				label: drugName,
				data: data,
				backgroundColor: colors[index % colors.length],
				borderColor: colors[index % colors.length].replace('0.2', '1'),
				borderWidth: 1
			});
		});

		var ctx = document.getElementById('medicineUsageChart').getContext('2d');
		if (ctx) {
			var myChart = new Chart(ctx, {
				type: 'bar',
				data: {
					labels: labels,
					datasets: datasets
				},
				options: {
					scales: {
						y: {
							beginAtZero: true
						}
					}
				}
			});
		} else {
			console.error('Canvas context not found');
		}
	</script>

	<script src="script.js"></script>

</body>
</html>
