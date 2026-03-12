<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'CRMS') }}</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="fa-solid fa-shield-halved"></i> CRMS
        </div>
        <div class="sidebar-nav">
            @php $role = auth()->user()->role; @endphp
            
            <a href="{{ route($role . '.dashboard') }}" class="nav-link {{ request()->routeIs('*.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high"></i> &nbsp; Dashboard
            </a>

            @if($role === 'admin')
            <a href="{{ route('admin.officers.index') }}" class="nav-link {{ request()->routeIs('admin.officers.*') ? 'active' : '' }}">
                <i class="fa-solid fa-users-gear"></i> &nbsp; Officers
            </a>
            @endif

            <a href="{{ route($role . '.criminals.index') }}" class="nav-link {{ request()->routeIs('*.criminals.*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-secret"></i> &nbsp; Criminals
            </a>

            <a href="{{ route($role . '.cases.index') }}" class="nav-link {{ request()->routeIs('*.cases.*') ? 'active' : '' }}">
                <i class="fa-solid fa-folder-open"></i> &nbsp; Case Records
            </a>

            <a href="{{ route($role . '.complaints.index') }}" class="nav-link {{ request()->routeIs('*.complaints.*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-invoice"></i> &nbsp; Complaints
            </a>

            @if($role === 'admin')
            <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line"></i> &nbsp; Reports
            </a>
            
            <a href="{{ route('admin.audit-logs.index') }}" class="nav-link {{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}">
                <i class="fa-solid fa-list-check"></i> &nbsp; Audit Logs
            </a>
            @endif
        </div>
    </div>

    <!-- Topbar -->
    <div class="topbar">
        <div class="page-title">
            <strong>Law Enforcement Portal</strong>
        </div>
        <div class="user-menu" style="display: flex; align-items: center; gap: 1rem;">
            <span><i class="fa-regular fa-user"></i> {{ auth()->user()->name }} ({{ ucfirst($role) }})</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger" style="padding: 0.3rem 0.8rem;">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @if(session('success'))
            <div class="card" style="border-left: 5px solid var(--success); color: #166534; background: #f0fdf4;">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="card" style="border-left: 5px solid var(--danger); color: #991b1b; background: #fef2f2;">
                <ul style="margin: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
