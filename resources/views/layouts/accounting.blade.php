<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MultiBranch-Accountant') }} - @yield('title')</title>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Inter:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #4338ca;
            --primary-light: #818cf8;
            --secondary-color: #0ea5e9;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
            --sidebar-active: #4f46e5;
            --body-bg: #f1f5f9;
            --card-bg: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --border-color: #e2e8f0;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--body-bg);
            color: var(--text-primary);
        }

        /* Top Navbar */
        .top-navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 0.75rem 1.5rem;
        }

        .top-navbar .navbar-brand {
            font-weight: 700;
            font-size: 1.25rem;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .top-navbar .navbar-brand i {
            font-size: 1.5rem;
        }

        .top-navbar .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
        }

        .top-navbar .nav-link:hover {
            color: white !important;
        }

        .top-navbar .dropdown-menu {
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            padding: 0.5rem;
        }

        .top-navbar .dropdown-item {
            border-radius: 8px;
            padding: 0.6rem 1rem;
        }

        .top-navbar .dropdown-item:hover {
            background-color: var(--body-bg);
        }

        /* Sidebar */
        .sidebar {
            background: var(--sidebar-bg);
            padding: 1.5rem 0;
            position: fixed;
            top: 60px; /* approx navbar height */
            width: 250px;
            height: calc(100vh - 60px); /* full viewport minus navbar */
            overflow-y: auto;
        }

        .sidebar .nav-link {
            color: #cbd5e1;
            padding: 0.75rem 1.25rem;
            margin: 0.15rem 0.75rem;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s ease;
        }

        .sidebar .nav-link i {
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
        }

        .sidebar .nav-link:hover {
            background: var(--sidebar-hover);
            color: white;
            transform: translateX(3px);
        }

        .sidebar .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
        }

        .sidebar-heading {
            color: var(--text-muted);
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 1.25rem 1.25rem 0.5rem;
            margin-top: 0.5rem;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 2rem;
            min-height: calc(100vh - 60px);
        }

        /* Cards */
        .card {
            background: var(--card-bg);
            border: none;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.2s ease;
        }

        .card:hover {
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Tables */
        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: var(--body-bg);
            border: none;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-secondary);
            padding: 1rem 1.25rem;
        }

        .table tbody td {
            padding: 1rem 1.25rem;
            vertical-align: middle;
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(79, 70, 229, 0.04);
        }

        /* Buttons */
        .btn {
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.35);
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
            border: none;
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
            border: none;
            color: white;
        }

        .btn-warning:hover {
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
            border: none;
        }

        .btn-info {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #0284c7 100%);
            border: none;
            color: white;
        }

        .btn-info:hover {
            color: white;
        }

        .btn-sm {
            padding: 0.4rem 0.75rem;
            font-size: 0.8rem;
            border-radius: 8px;
        }

        /* Badges */
        .badge {
            font-weight: 500;
            padding: 0.4em 0.8em;
            border-radius: 6px;
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.25rem;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.1) 100%);
            color: #059669;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
            color: #dc2626;
        }

        /* Page Title */
        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .page-subtitle {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        /* Form Controls */
        .form-control, .form-select {
            border: 2px solid var(--border-color);
            border-radius: 10px;
            padding: 0.6rem 1rem;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        /* Breadcrumb */
        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 1.5rem;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: var(--text-secondary);
        }

        /* User Avatar */
        .user-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-color) 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* Logo Badge */
        .logo-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.5rem;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                width: 100%;
                position: relative;
                top: 0;
                height: auto;
                max-height: 60vh;
                overflow-y: auto;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div id="app">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-md sticky-top top-navbar">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <span class="logo-badge">
                        <i class="fa-solid fa-calculator"></i>
                    </span>
                    MultiBranch Accountant
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto align-items-center">
                        @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                                    <span class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="{{ route('users.show', Auth::user()) }}">
                                        <i class="fa-regular fa-user me-2"></i> My Profile
                                    </a>
                                    <hr class="dropdown-divider">
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <div class="d-flex">
            <!-- Sidebar -->
            <nav class="sidebar d-none d-lg-block">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fa-solid fa-gauge"></i> Dashboard
                        </a>
                    </li>

                    <li><h6 class="sidebar-heading">Setup</h6></li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('branches.*') ? 'active' : '' }}" href="{{ route('branches.index') }}">
                            <i class="fa-solid fa-building"></i> Branches
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('account-groups.*') ? 'active' : '' }}" href="{{ route('account-groups.index') }}">
                            <i class="fa-regular fa-folder-open"></i> Account Groups
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('accounts.*') ? 'active' : '' }}" href="{{ route('accounts.index') }}">
                            <i class="fa-solid fa-list"></i> Chart of Accounts
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('opening-balances.*') ? 'active' : '' }}" href="{{ route('opening-balances.index') }}">
                            <i class="fa-solid fa-wallet"></i> Opening Balances
                        </a>
                    </li>

                    <li><h6 class="sidebar-heading">Transactions</h6></li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('journal-entries.*') ? 'active' : '' }}" href="{{ route('journal-entries.index') }}">
                            <i class="fa-solid fa-book"></i> Journal Entries
                        </a>
                    </li>

                    <li><h6 class="sidebar-heading">Reports</h6></li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reports.index') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                            <i class="fa-solid fa-chart-bar"></i> Account Balances
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reports.trial-balance') ? 'active' : '' }}" href="{{ route('reports.trial-balance') }}">
                            <i class="fa-solid fa-scale-balanced"></i> Trial Balance
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reports.income-statement') ? 'active' : '' }}" href="{{ route('reports.income-statement') }}">
                            <i class="fa-solid fa-file-invoice-dollar"></i> Income Statement
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reports.balance-sheet') ? 'active' : '' }}" href="{{ route('reports.balance-sheet') }}">
                            <i class="fa-solid fa-building-columns"></i> Balance Sheet
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reports.ledger') ? 'active' : '' }}" href="{{ route('reports.ledger') }}">
                            <i class="fa-solid fa-book-open"></i> Account Ledger
                        </a>
                    </li>

                    <li><h6 class="sidebar-heading">Administration</h6></li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                            <i class="fa-solid fa-users"></i> User Management
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Main Content -->
            <main class="main-content flex-grow-1">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa-regular fa-circle-check me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa-regular fa-circle-xmark me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
