<div class="vertical-menu">
    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                {{-- Dashboard --}}
                <li>
                    <a href="{{ route('dashboard') }}" class="waves-effect {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="ri-dashboard-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- Green Sand Check --}}
                <li>
                    <a href="{{ route('greensand.index') }}" class="waves-effect {{ request()->routeIs('greensand.index') ? 'active' : '' }}">
                        <i class="ri-flask-line"></i>
                        <span>Green Sand Check</span>
                    </a>
                </li>

                {{-- JSH GFN --}}
                <li>
                    <a href="{{ route('jsh-green-sand.index') }}" class="waves-effect {{ request()->routeIs('jsh-green-sand.index') ? 'active' : '' }}">
                        <i class="ri-settings-line"></i>
                        <span>JSH GFN</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>