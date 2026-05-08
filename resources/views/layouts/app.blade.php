<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="shortcut icon" href="{{asset('/images/logo.png')}}" type="image/x-icon">

        <link rel="stylesheet" href="{{asset('/images/bootstrap.css')}}">
        {{-- <script src="{{asset('/bootstrap/bootstrap.js')}}"></script> --}}

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <!-- Scripts -->
        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    </head>
    <body class="font-sans antialiased">

        <div class="container-fluid px-0 min-vh-100">
            <div class="d-flex min-vh-100">

                {{-- Sidebar --}}
                {{-- <div class="sidebar-wrapper d-none d-md-block">
                    @include('layouts.navigation')
                </div> --}}
                <div class="sidebar-wrapper d-none d-md-block position-sticky top-0 vh-100 flex-shrink-0">
                    @include('layouts.navigation')
                </div>

                {{-- Main Content --}}
                <div class="flex-grow-1 d-flex flex-column bg-body-tertiary overflow-hidden" style="min-height: 0;">

                    {{-- Header --}}
                    {{-- <header class="admin-header px-3 px-md-4 py-3"> --}}
                    <header class="admin-header px-3 px-md-4 py-3 position-sticky top-0 bg-body-tertiary border-bottom" style="z-index: 1030;">

                        {{-- TOAST CONTAINER (below header, right side) --}}
                    
                                
                                @if(session('success'))
                                    <div id="successToast" class="toast align-items-center text-bg-success border-0 show position-fixed top-0 end-0 m-3"
                                        role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 2000;">

                                        <div class="d-flex">
                                            <div class="toast-body">
                                                <i class="bi bi-check-circle me-2"></i>
                                                {{ session('success') }}
                                            </div>

                                            <button type="button" class="btn-close btn-close-white me-2 m-auto"
                                                data-bs-dismiss="toast"></button>
                                        </div>
                                    </div>
                                @endif

                        
                        <div class="d-flex align-items-center justify-content-between gap-3">

                            {{-- Left --}}
                            <div class="d-flex align-items-center gap-3 flex-grow-1">

                                {{-- Mobile Menu --}}
                                <button class="btn btn-outline-secondary d-md-none rounded-3"
                                        type="button"
                                        data-bs-toggle="offcanvas"
                                        data-bs-target="#mobileSidebar">
                                    <i class="bi bi-list fs-5"></i>
                                </button>

                                {{-- Page Title --}}
                                <div>
                                    <h5 class="mb-0 fw-semibold">{{ $header }}</h5>
                                    <small class="text-muted">Welcome back, Admin</small>
                                </div>
                            </div>

                            {{-- Right --}}
                            <div class="d-flex align-items-center gap-3">

                                {{-- Search --}}
                                {{-- <div class="d-none d-md-flex align-items-center admin-search px-3">
                                    <i class="bi bi-search text-muted me-2"></i>
                                    <input type="text" class="form-control border-0 shadow-none p-0"
                                        placeholder="Search..." id="searchInput">
                                </div> --}}
                                <div class="d-none d-md-flex align-items-center admin-search px-3 position-relative">
                                    <i class="bi bi-search text-muted me-2"></i>

                                    <input type="text"
                                        class="form-control border-0 shadow-none p-0 pe-4"
                                        placeholder="Search..."
                                        id="searchInput">

                                    <!-- CLEAR BUTTON -->
                                    <button type="button"
                                        id="clearSearch"
                                        class="btn btn-sm btn-link text-muted position-absolute end-0 me-2 d-none"
                                        style="text-decoration: none;">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </button>
                                </div>

                                {{-- Notification --}}
                                <button class="btn btn-light position-relative rounded-3 border">
                                    <i class="bi bi-bell fs-5"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        3
                                    </span>
                                </button>

                                {{-- Profile --}}
                                <div class="d-flex align-items-center gap-2 admin-profile">
                                    <img src="{{ asset('images/logo.png') }}" alt="Admin" class="admin-avatar">
                                    <div class="d-none d-md-block lh-sm">
                                        <div class="fw-semibold small">Administrator</div>
                                        <small class="text-muted">Barangay Admin</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </header>

                    


                    {{-- Page Content --}}
                    {{-- <main class="flex-grow-1 p-3 p-md-4 overflow-auto" style="min-height: 0;"> --}}
                    <main class="flex-grow-1 p-3 p-md-4 overflow-auto" style="min-height: 0; height: 0;">
                        <div class="admin-content-card">
                            {{ $slot }}
                        </div>
                    </main>

                    {{-- FOOTER --}}
                    <footer class="admin-footer text-center py-2  flex-shrink-0">
                        <small class="text-muted">
                            © {{ date('Y') }} PONG-MTA Information Technology Services
                        </small>
                    </footer>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const toastEl = document.getElementById('successToast');

                if (toastEl) {
                    setTimeout(() => {
                        toastEl.classList.remove('show');
                        toastEl.classList.add('hide');
                    }, 3000);
                }
            });

            function showToast(message, type = 'success') {
                const successToast = 'dynamicToast';

                let existing = document.getElementById(successToast);
                if (existing) existing.remove();

                const toastHtml = `
                <div id="${successToast}" class="toast align-items-center text-bg-${type} border-0 show position-fixed top-0 end-0 m-3"
                    style="z-index: 2000;">
                    <div class="d-flex">
                        <div class="toast-body">
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto"
                            onclick="this.closest('.toast').remove()"></button>
                    </div>
                </div>`;

                document.body.insertAdjacentHTML('beforeend', toastHtml);

                setTimeout(() => {
                    let el = document.getElementById(successToast);
                    if (el) el.remove();
                }, 3000);
            }

            const searchInput = document.getElementById('searchInput');
            const clearBtn = document.getElementById('clearSearch');

            // show/hide X button
            if (searchInput) {

                searchInput.addEventListener('input', function () {

                    if (this.value.length > 0) {
                        clearBtn.classList.remove('d-none');
                    } else {
                        clearBtn.classList.add('d-none');
                    }

                });

            }

            if (clearBtn) {

                    clearBtn.addEventListener('click', function () {

                        searchInput.value = '';
                        clearBtn.classList.add('d-none');

                        if (typeof fetchData === 'function') {
                            fetchData(1, '');
                        }

                    });

                }
        </script>
    </body>
</html>
