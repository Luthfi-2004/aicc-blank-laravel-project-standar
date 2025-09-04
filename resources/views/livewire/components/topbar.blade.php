<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{ route('dashboard') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img class="img-fluid" src="{{ asset('assets/images/logo-sm.png') }}" alt="Logo Small">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-light.png') }}" alt="Logo" height="45">
                    </span>
                </a>
            </div>

            <button type="button"
                    class="btn btn-sm px-3 font-size-24 header-item waves-effect"
                    id="vertical-menu-btn">
                <i class="ri-menu-2-line align-middle"></i>
            </button>
        </div>

        <div class="d-flex">

            {{-- Search (mobile) --}}
            <div class="dropdown d-inline-block d-lg-none ml-2">
                <button type="button"
                        class="btn header-item noti-icon waves-effect"
                        id="page-header-search-dropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="ri-search-line"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0"
                     aria-labelledby="page-header-search-dropdown">
                    <form class="p-3" onsubmit="return false;">
                        <div class="form-group m-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search ...">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button">
                                        <i class="ri-search-line"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Fullscreen --}}
            <div class="dropdown d-none d-lg-inline-block ml-1">
                <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                    <i class="ri-fullscreen-line"></i>
                </button>
            </div>

            {{-- User menu --}}
            <div class="dropdown d-inline-block user-dropdown">
                <button type="button"
                        class="btn header-item waves-effect"
                        id="page-header-user-dropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user"
                         src="{{ asset('assets/images/users/avatar-2.jpg') }}"
                         alt="Header Avatar">
                    @php
                        $u = auth()->user();
                        $displayName = $u->name ?? $u->username ?? 'User';
                    @endphp
                    <span class="d-xl-inline-block ml-1">{{ $displayName }}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-right">
                    {{-- Change Password (aktifkan jika punya rute-nya) --}}
                     <a class="dropdown-item" href="{{ route('password.change') }}">
                        <i class="ri-lock-unlock-line align-middle mr-1"></i> Change Password
                    </a>

                    {{-- Logout (POST + CSRF) --}}
                    <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="ri-shut-down-line align-middle mr-1 text-danger"></i> Logout
                        </button>
                    </form>

                    {{-- Alternatif: jika mau tampilan link tapi tetap POST --}}
                    {{-- <a href="#" class="dropdown-item text-danger"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="ri-shut-down-line align-middle mr-1 text-danger"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form> --}}
                </div>
            </div>

            {{-- Right sidebar toggle --}}
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                    <i class="ri-settings-2-line"></i>
                </button>
            </div>

        </div>
    </div>
</header>
