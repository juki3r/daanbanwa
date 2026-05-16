{{-- Desktop / Tablet Sidebar --}}
<div class="d-none d-md-flex flex-column h-100 border-end p-3" style="background-color: navy">

    {{-- Logo --}}
    <div class="text-center border-bottom pb-3 mb-3">
        <a href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" width="80">
        </a>
        <div class="mt-2 d-flex flex-column align-items-center">
            <strong class="d-block">Daan Banwa</strong>
            <small style="font-size: 12px">Estancia, Iloilo</small>
        </div>
    </div>

    {{-- Menu --}}
    <nav class="nav flex-column gap-2">

        <a href="{{ route('admin.dashboard') }}"
        class="nav-link d-flex align-items-center gap-2 small py-1 px-2 {{ request()->routeIs('admin.dashboard') ? 'active-nav' : 'text-dark' }}">
            <i class="bi bi-grid-fill"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('residents.index') }}"
        class="nav-link d-flex align-items-center gap-2 small py-1 px-2  {{ request()->routeIs('residents.*') ? 'active-nav' : 'text-dark' }}">
            <i class="bi bi-people-fill"></i>
            <span>Residents</span>
        </a>

        <a href="{{ route('requests.index') }}"
            class="nav-link d-flex align-items-center gap-2 small py-1 px-2  {{ request()->routeIs('requests.index') ? 'active-nav' : 'text-dark' }}">

                <span class="nav-icon-wrapper">
                    <i class="bi bi-file-earmark-text-fill"></i>

                    @if($certCount > 0)
                        <span id="certCountBadge" class="nav-badge">
                            {{ $certCount }}
                        </span>
                    @endif
                </span>

                <span>Certificates</span>
            </a>

        <a href="{{ route('concerns.index') }}"
        class="nav-link d-flex align-items-center gap-2 small py-1 px-2  {{ request()->routeIs('concerns.*') ? 'active-nav' : 'text-dark' }}">
            <span class="nav-icon-wrapper">
                <i class="bi bi-chat-dots-fill"></i>
                @if($concernCount > 0)
                    <span id="concernCountBadge" class="nav-badge">
                        {{ $concernCount }}
                    </span>
                @endif
            </span>
            <span>Concerns</span>
        </a>

        <a href="{{ route('blotters.index') }}"
        class="nav-link d-flex align-items-center gap-2 small py-1 px-2  {{ request()->routeIs('blotters.*') ? 'active-nav' : 'text-dark' }}">
            <span class="nav-icon-wrapper">
                <i class="bi bi-journal-text"></i>
                @if($blotterCount > 0)
                    <span id="blotterCountBadge" class="nav-badge">
                        {{ $blotterCount }}
                    </span>
                @endif
            </span>
            <span>Blotters</span>
        </a>

        <a href="{{ route('users.index') }}"
        class="nav-link d-flex align-items-center gap-2 small py-1 px-2  {{ request()->routeIs('users.*') ? 'active-nav' : 'text-dark' }}">
            <i class="bi bi-phone-fill"></i>
            <span>App Users</span>
        </a>

        <a href="{{ route('news.index') }}"
        class="nav-link d-flex align-items-center gap-2 small py-1 px-2  {{ request()->routeIs('news.*') ? 'active-nav' : 'text-dark' }}">
            <i class="bi bi-newspaper"></i>
            <span>News</span>
        </a>

        <a href="{{ route('ordinances.index') }}"
        class="nav-link d-flex align-items-center gap-2 small py-1 px-2  {{ request()->routeIs('ordinances.*') ? 'active-nav' : 'text-dark' }}">
            <i class="bi bi-newspaper"></i>
            <span>Ordinances</span>
        </a>

        <a href="{{ route('officials.index') }}"
        class="nav-link d-flex align-items-center gap-2 small py-1 px-2  {{ request()->routeIs('officials.*') ? 'active-nav' : 'text-dark' }}">
            <i class="bi bi-people"></i>
            <span>Officials</span>
        </a>

        <a href="{{ route('emergency.index') }}"
        class="nav-link d-flex align-items-center gap-2 small py-1 px-2  {{ request()->routeIs('emergency.*') ? 'active-nav' : 'text-dark' }}">
            <i class="bi bi-telephone-fill"></i>
            <span>Emergency</span>
        </a>

        <a href="{{ route('calendar.index') }}"
        class="nav-link d-flex align-items-center gap-2 small py-1 px-2  {{ request()->routeIs('calendar.*') ? 'active-nav' : 'text-dark' }}">
            <i class="bi bi-calendar-fill"></i>
            <span>Calendar</span>
        </a>

    </nav>

    {{-- Logout --}}
    <div class="mt-auto pt-3 border-top">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="{{ route('logout') }}"
            class="nav-link d-flex align-items-center gap-2 text-danger small py-1 px-2 "
            onclick="event.preventDefault(); this.closest('form').submit();">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>
        </form>
    </div>
</div>

{{-- Mobile Sidebar (Offcanvas) --}}
<div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="mobileSidebar">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Daan Banwa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column">
        <div class="text-center border-bottom pb-3 mb-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" width="80">
            <div class="mt-2 d-flex flex-column align-items-center">
                <strong class="d-block">Daan Banwa</strong>
                <small style="font-size: 12px">Estancia, Iloilo</small>
            </div>
        </div>

        <nav class="nav flex-column gap-1">

        <a href="{{ route('admin.dashboard') }}"
        class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('admin.dashboard') ? 'active-nav' : 'text-dark' }}">
            <i class="bi bi-grid-fill"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('residents.index') }}"
        class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('residents.*') ? 'active-nav' : 'text-dark' }}">
            <i class="bi bi-people-fill"></i>
            <span>Residents</span>
        </a>

        <a href="{{ route('requests.index') }}"
        class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('requests.index') ? 'active-nav' : 'text-dark' }}">
            <i class="bi bi-file-earmark-text-fill"></i>
            <span>Certificates</span>
        </a>

        <a href="{{ route('concerns.index') }}"
        class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('concerns.*') ? 'active-nav' : 'text-dark' }}">
            <i class="bi bi-chat-dots-fill"></i>
            <span>Concerns</span>
        </a>

        <a href="{{ route('blotters.index') }}"
        class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('blotters.*') ? 'active-nav' : 'text-dark' }}">
            <i class="bi bi-journal-text"></i>
            <span>Blotters</span>
        </a>

        <a href="{{ route('users.index') }}"
        class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('users.*') ? 'active-nav' : 'text-dark' }}">
            <i class="bi bi-phone-fill"></i>
            <span>App Users</span>
        </a>

        <a href="{{ route('news.index') }}"
        class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('news.*') ? 'active-nav' : 'text-dark' }}">
            <i class="bi bi-newspaper"></i>
            <span>News</span>
        </a>

        <a href="{{ route('ordinances.index') }}"
        class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('ordinances.*') ? 'active-nav' : 'text-dark' }}">
            <i class="bi bi-file-earmark-text-fill"></i>
            <span>Ordinances</span>
        </a>

        <a href="{{ route('officials.index') }}"
        class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('officials.*') ? 'active-nav' : 'text-dark' }}">
            <i class="bi bi-people-fill"></i>
            <span>Officials</span>
        </a>

        <a href="{{ route('calendar.index') }}"
        class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('calendar.*') ? 'active-nav' : 'text-dark' }}">
            <i class="bi bi-calendar-fill"></i>
            <span>Calendar</span>
        </a>

    </nav>

        {{-- Logout --}}
        <div class="mt-auto pt-3 border-top">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}"
                class="nav-link d-flex align-items-center gap-2 text-danger"
                onclick="event.preventDefault(); this.closest('form').submit();">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
            </form>
        </div>
    </div>
</div>