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
	<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        table {
            font-family: Arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 12px;
        }

        th {
            background-color: #f1f1f1;
            color: black;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .btn {
            display: inline-block;
            padding: 8px 12px;
            margin: 5px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .btn-info {
            background-color: #17a2b8;
            color: white;
        }

        .btn-info:hover {
            background-color: #138496;
            transform: scale(1.05);
        }

        .btn-warning {
            background-color: #ffc107;
            color: black;
        }

        .btn-warning:hover {
            background-color: #e0a800;
            transform: scale(1.05);
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
            transform: scale(1.05);
        }
    </style>
	<title>MediAI</title>
</head>
<body>

	
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
        <li>
            <a href="{{ url('/redirects') }}">
                <i class='bx bxs-dashboard'></i>
                <span class="text">Dashboard</span>
            </a>
        </li>
        <li class="active">
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
            
            <a href="#" class="profile">
                
            </a>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Patients</h1>
                    
                </div>
                
            </div>
            <br>
            <div class="container">
                
                
                <a href="{{ route('patients.create') }}" class="btn btn-primary">Add Patient</a>

                @if (count($patients) > 0)
                    <table class="table mt-3">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Clinic</th>
                                <th>Address</th>
                                <th>Phone Number</th>
                                <th>ID Number</th>
                                <th>Date of Birth</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($patients as $patient)
                                <tr>
                                    <td>{{ $patient->id }}</td>
                                    <td>{{ $patient->patient_name }}</td>
                                    <td>{{ $patient->clinic->clinic_name }}</td>
                                    <td>{{ $patient->address }}</td>
                                    <td>{{ $patient->phone_number }}</td>
                                    <td>{{ $patient->id_number }}</td>
                                    <td>{{ $patient->date_of_birth }}</td>
                                    <td>
                                        <a href="{{ route('patients.details', $patient->id) }}" class="btn btn-info">View</a>
                                        <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-warning">Edit</a>
                                        <a href="{{ route('patients.destroy', $patient->id) }}"
                                           onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this patient?')) document.getElementById('delete-form-{{ $patient->id }}').submit();"
                                           class="btn btn-danger" style="color: white; text-decoration: none;">
                                           Delete
                                        </a>

                                        <form id="delete-form-{{ $patient->id }}" action="{{ route('patients.destroy', $patient->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>No patients found.</p>
                @endif
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <script src="script.js"></script>

</body>
</html>
