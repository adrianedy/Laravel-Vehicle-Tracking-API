<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <a href="{{ route('admin.home') }}">MobiGPS</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
        <a href="{{ route('admin.home') }}">
            {{-- <img src="{{ asset('images/logo-icon.png') }}" alt="Mahaputra" width="50"> --}}
        </a>
    </div>
    <ul class="sidebar-menu mb-5">
        {{-- <li class="menu-header">Dashboard</li> --}}
        <li class="{{ strpos(Route::currentRouteName(), 'admin.users.') === 0 ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.users.index') }}"><i class="fas fa-users"></i> <span>Users</span></a>
        </li>
        <li class="{{ Route::currentRouteName() == 'admin.devices.index' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.devices.index') }}"><i class="fas fa-barcode"></i> <span>Devices</span></a>
        </li>
    </ul>
</aside>
