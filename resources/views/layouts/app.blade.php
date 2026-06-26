<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Invoice & Budget System')</title>
    
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS for Premium Design -->
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #e0e7ff;
            --bg-color: #f8fafc;
            --sidebar-width: 260px;
            --card-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.05);
            --card-shadow-hover: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            color: #1e293b;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: linear-gradient(180deg, #1e1b4b 0%, #0f172a 100%);
            color: #e2e8f0;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-brand {
            padding: 1.5rem;
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            color: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-menu {
            padding: 1.5rem 0;
            list-style: none;
            margin: 0;
        }

        .sidebar-menu-item {
            margin: 0.25rem 1rem;
        }

        .sidebar-menu-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.75rem 1rem;
            color: #94a3b8;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .sidebar-menu-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.05);
        }

        .sidebar-menu-link.active {
            color: #fff;
            background-color: var(--primary-color);
        }

        /* Main Content Styling */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        .top-navbar {
            background-color: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);
        }

        .content-body {
            padding: 2rem;
            flex: 1;
        }

        /* Card and Elements Enhancement */
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            background-color: #fff;
        }

        .card:hover {
            box-shadow: var(--card-shadow-hover);
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid #e2e8f0;
            padding: 1.25rem 1.5rem;
            font-weight: 600;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.5rem 1.25rem;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: #fff;
        }

        /* Table Styling */
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .table td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
            border-bottom: 1px solid #e2e8f0;
        }

        /* Badges */
        .badge-paid {
            background-color: #dcfce7;
            color: #15803d;
            font-weight: 500;
            padding: 0.35em 0.65em;
            border-radius: 6px;
        }

        .badge-pending {
            background-color: #fef9c3;
            color: #a16207;
            font-weight: 500;
            padding: 0.35em 0.65em;
            border-radius: 6px;
        }

        .badge-overdue {
            background-color: #fee2e2;
            color: #b91c1c;
            font-weight: 500;
            padding: 0.35em 0.65em;
            border-radius: 6px;
        }

        /* Responsive collapse menu for Mobile */
        @media (max-width: 991.98px) {
            .sidebar {
                left: -260px;
            }
            .sidebar.show {
                left: 0;
            }
            .main-wrapper {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="fa-solid fa-file-invoice-dollar fs-4 text-warning"></i>
            <span>FinTrack Admin</span>
        </div>
        <ul class="sidebar-menu">
            <li class="sidebar-menu-item">
                <a href="{{ route('dashboard') }}" class="sidebar-menu-link {{ Route::is('dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="{{ route('sales.index') }}" class="sidebar-menu-link {{ Route::is('sales.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-invoice"></i>
                    <span>Sales Invoices</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="{{ route('purchase.index') }}" class="sidebar-menu-link {{ Route::is('purchase.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-receipt"></i>
                    <span>Purchase Invoices</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="{{ route('budgets.index') }}" class="sidebar-menu-link {{ Route::is('budgets.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-wallet"></i>
                    <span>Budget Management</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="{{ route('expenses.index') }}" class="sidebar-menu-link {{ Route::is('expenses.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-money-bill-trend-up"></i>
                    <span>Expense Tracking</span>
                </a>
            </li>
            @if(auth()->user() && auth()->user()->isAdmin())
            <li class="sidebar-menu-item">
                <a href="{{ route('customers.index') }}" class="sidebar-menu-link {{ Route::is('customers.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i>
                    <span>Customers</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="{{ route('suppliers.index') }}" class="sidebar-menu-link {{ Route::is('suppliers.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-truck-field"></i>
                    <span>Suppliers</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="{{ route('products.index') }}" class="sidebar-menu-link {{ Route::is('products.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-box-archive"></i>
                    <span>Products & Services</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a href="{{ route('users.index') }}" class="sidebar-menu-link {{ Route::is('users.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-shield"></i>
                    <span>User Management</span>
                </a>
            </li>
            @endif
            <li class="sidebar-menu-item">
                <a href="{{ route('reports.index') }}" class="sidebar-menu-link {{ Route::is('reports.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>Reports & Analytics</span>
                </a>
            </li>
            <li class="sidebar-menu-item mt-4 border-top border-secondary pt-3">
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="sidebar-menu-link bg-transparent border-0 text-start w-100" style="color: #fca5a5;">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Top Navbar -->
        <header class="top-navbar">
            <button class="btn btn-outline-secondary d-lg-none" id="sidebarToggle">
                <i class="fa-solid fa-bars"></i>
            </button>
            <h5 class="m-0 font-weight-bold">
                @yield('page_title', 'Dashboard')
            </h5>
            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-md-block">
                    <span class="d-block font-weight-bold text-dark">{{ Auth::user()->name ?? 'Administrator' }}</span>
                    <small class="text-muted text-uppercase" style="font-size: 10px; font-weight: bold;">
                        {{ Auth::user()->email ?? 'admin@example.com' }}
                        <span class="badge bg-secondary ms-1">{{ Auth::user() ? ucfirst(Auth::user()->role) : 'Admin' }}</span>
                    </small>
                </div>
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-weight: bold;">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
            </div>
        </header>

        <!-- Content Body -->
        <main class="content-body">
            <!-- Session Status Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="fa-solid fa-circle-xmark me-2"></i>
                    <strong>Please correct the following errors:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Sidebar Toggle Script -->
    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>
    @yield('scripts')
</body>
</html>
