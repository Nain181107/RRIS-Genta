<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RRIS Web</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .sidebar {
            width: 280px;
            transition: all 0.3s ease-in-out;
        }

        .sidebar.collapsed {
            width: 70px !important;
        }

        .sidebar.collapsed .sidebar-text,
        .sidebar.collapsed .menu-text,
        .sidebar.collapsed .sidebar-logo,
        .sidebar.collapsed .submenu {
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s, visibility 0.2s;
        }

        .sidebar.collapsed .menu-btn,
        .sidebar.collapsed .submenu-btn,
        .sidebar.collapsed .parent-btn {
            justify-content: center !important;
            padding: 0.75rem !important;
        }

        .sidebar.collapsed .sidebar-header {
            justify-content: center !important;
        }

        .sidebar.collapsed .sidebar-header h3 {
            display: none;
        }

        .sidebar.collapsed .sidebar-header button {
            margin: 0 auto;
        }

        .sidebar.collapsed .profile-info {
            opacity: 0;
            visibility: hidden;
            width: 0;
            transition: opacity 0.2s, visibility 0.2s, width 0.2s;
        }

        .sidebar.collapsed .profile-button {
            justify-content: center !important;
            padding: 0.5rem !important;
        }

        .sidebar.collapsed #profileMenu {
            left: 70px !important;
            right: auto !important;
            bottom: 0 !important;
        }

        .sidebar.collapsed #profileMenu {
            left: 70px !important;
            right: auto !important;
            bottom: 0 !important;
            min-width: 200px !important;
        }

        /* ===== RESPONSIVE SIDEBAR (MOBILE) ===== */
        @media (max-width: 768px) {

            body,
            html {
                overflow-x: hidden;
                max-width: 100vw;
            }

            .sidebar {
                position: fixed;
                left: -100%;
                top: 0;
                z-index: 1000;
                width: 280px !important;
                max-width: 85vw;
                height: 100vh;
                transition: left 0.3s ease-in-out;
            }

            .sidebar.mobile-open {
                left: 0 !important;
            }

            /* Override collapsed state on mobile - FORCE show everything when on mobile */
            .sidebar.collapsed {
                width: 280px !important;
            }

            .sidebar.collapsed .sidebar-text,
            .sidebar.collapsed .menu-text,
            .sidebar.collapsed .sidebar-logo,
            .sidebar.collapsed .submenu,
            .sidebar.collapsed .profile-info {
                opacity: 1 !important;
                visibility: visible !important;
                display: block !important;
                width: auto !important;
            }

            .sidebar.collapsed .menu-btn,
            .sidebar.collapsed .submenu-btn,
            .sidebar.collapsed .parent-btn {
                justify-content: flex-start !important;
                padding: 0.5rem 1rem !important;
            }

            .sidebar.collapsed .profile-button {
                justify-content: flex-start !important;
                padding: 0.5rem !important;
            }

            .sidebar.collapsed .sidebar-header {
                justify-content: space-between !important;
            }

            .sidebar.collapsed .sidebar-header h3 {
                display: block !important;
            }

            #sidebarOverlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 999;
                opacity: 0;
                transition: opacity 0.3s ease-in-out;
            }

            #sidebarOverlay.active {
                display: block;
                opacity: 1;
            }

            .mobile-menu-toggle {
                display: flex !important;
            }

            main {
                padding: 0.5rem !important;
                min-width: 0 !important;
                margin-left: 0 !important;
            }
        }

        @media (min-width: 769px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                height: 100vh;
                z-index: 100;
            }

            main {
                margin-left: 280px;
                width: calc(100% - 280px);
                transition: margin-left 0.3s ease-in-out, width 0.3s ease-in-out;
            }

            .sidebar.collapsed~main,
            body:has(.sidebar.collapsed) main {
                margin-left: 70px;
                width: calc(100% - 70px);
            }
        }

        @media (max-width: 640px) {
            .h-13 {
                height: auto !important;
                gap: 0.5rem;
            }

            .h-13>div:first-child {
                gap: 0.5rem !important;
                flex-wrap: wrap;
            }

            #tanggalrealtime,
            #shiftactual {
                font-size: 0.75rem;
                white-space: nowrap;
            }
        }

        @media (max-width: 480px) {
            .h-13 {
                flex-wrap: nowrap;
                padding: 0.25rem !important;
                justify-content: space-between;
            }

            .h-13>div:first-child {
                flex: 1;
                justify-content: center !important;
                gap: 0.5rem !important;
            }

            #tanggalrealtime,
            #shiftactual {
                font-size: 0.65rem;
            }
        }

        .sidebar-text,
        .menu-text,
        .sidebar-logo,
        .profile-info {
            transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out, opacity 0.3s ease-in-out;
        }

        .submenu.open {
            max-height: 500px;
            opacity: 1;
            visibility: visible;
        }
    </style>
</head>

<body class="overflow-hidden">

    <div id="sidebarOverlay" onclick="toggleMobileSidebar()"></div>

    <div class="h-screen flex flex-row overflow-hidden">

        <!-- Sidebar -->
        <aside id="sidebar"
            class="sidebar shrink-0 bg-white p-2 border-2 border-gray-300
           flex flex-col h-screen">

            <div class="shrink-0">
                <div class="flex items-start justify-between mb-2 sidebar-header">
                    <h3 class="sidebar-text text-sm font-semibold leading-tight text-gray-500 px-2 pt-2">
                        Powered by :<br>
                        <span class="text-xs font-normal">
                            PT Digital Transformasi Industri
                        </span>
                    </h3>

                    <button onclick="toggleSidebar()" class="p-2 rounded hover:bg-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="var(--blue)" class="size-8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5" />
                        </svg>
                    </button>
                </div>

                <div class="sidebar-logo flex justify-center my-6">
                    <img src="{{ asset('images/logoRRIS.png') }}" class="w-48">
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto overflow-x-hidden space-y-1 mt-4 pr-1 pb-2">

                {{-- Dashboard --}}
                <a href="/dashboard" class="menu-btn {{ request()->is('/') ? 'bg-gray-800 text-white' : '' }}">
                    <img src="{{ asset('images/dashboard.png') }}" class="w-7 h-7">
                    <span class="menu-text text-sm font-sans">Dashboard</span>
                </a>

                {{-- Entry Data --}}
                @php
                    $entryActive = request()->is('penerimaan', 'perbaikan', 'pengiriman', 'buttman');
                @endphp
                <button onclick="toggleSubmenu('entryMenu')" data-parent="entryMenu"
                    class="menu-btn parent-btn {{ $entryActive ? 'bg-gray-500 text-white' : '' }}">
                    <img src="{{ asset('images/logoinputdata.png') }}" class="w-7 h-7">
                    <span class="menu-text text-sm">Form Entry Data</span>
                    <span class="ml-auto menu-text">▾</span>
                </button>
                <div id="entryMenu"
                    class="submenu mt-1 space-y-1 bg-(--whitesmoke) rounded-b-lg {{ $entryActive ? 'open' : '' }}">

                    <a href="/penerimaan"
                        class="submenu-btn {{ request()->is('penerimaan') ? 'bg-(--blue) text-white' : '' }}">
                        <img src="{{ asset('images/penerimaan.png') }}" class="w-7 h-7">
                        <span class="menu-text text-sm font-sans">Penerimaan</span>
                    </a>

                    <a href="/perbaikan"
                        class="submenu-btn {{ request()->is('perbaikan') ? 'bg-(--blue) text-white' : '' }}">
                        <img src="{{ asset('images/perbaikan.png') }}" class="w-7 h-7">
                        <span class="menu-text text-sm font-sans">Perbaikan</span>
                    </a>

                    <a href="/pengiriman"
                        class="submenu-btn {{ request()->is('pengiriman') ? 'bg-(--blue) text-white' : '' }}">
                        <img src="{{ asset('images/pengiriman.png') }}" class="w-7 h-7">
                        <span class="menu-text text-sm font-sans">Pengiriman</span>
                    </a>

                    <a href="/buttman"
                        class="submenu-btn {{ request()->is('buttman') ? 'bg-(--blue) text-white' : '' }}">
                        <img src="{{ asset('images/buttman.png') }}" class="w-7 h-7">
                        <span class="menu-text text-sm font-sans">Butt Ratio & Man Power</span>
                    </a>
                </div>

                {{-- Edit Data --}}
                @php
                    $editActive = request()->is('editpenerimaan', 'editperbaikan');
                @endphp
                <button onclick="toggleSubmenu('editMenu')" data-parent="editMenu"
                    class="menu-btn parent-btn {{ $editActive ? 'bg-gray-500 text-white' : '' }}">
                    <img src="{{ asset('images/logoeditdata.png') }}" class="w-7 h-7">
                    <span class="menu-text text-sm">Form Edit Data</span>
                    <span class="ml-auto menu-text">▾</span>
                </button>
                <div id="editMenu"
                    class="submenu mt-1 space-y-1 bg-(--whitesmoke) rounded-b-lg {{ $editActive ? 'open' : '' }}">

                    <a href="/editpenerimaan"
                        class="submenu-btn {{ request()->is('editpenerimaan') ? 'bg-(--blue) text-white' : '' }}">
                        <img src="{{ asset('images/penerimaan.png') }}" class="w-7 h-7">
                        <span class="menu-text text-sm font-sans">Penerimaan</span>
                    </a>

                    <a href="/editperbaikan"
                        class="submenu-btn {{ request()->is('editperbaikan') ? 'bg-(--blue) text-white' : '' }}">
                        <img src="{{ asset('images/perbaikan.png') }}" class="w-7 h-7">
                        <span class="menu-text text-sm font-sans">Perbaikan</span>
                    </a>
                </div>

                {{-- Laporan --}}
                <a href="/laporan" class="menu-btn {{ request()->is('laporan') ? 'bg-(--blue) text-white' : '' }}">
                    <img src="{{ asset('images/logolaporan.png') }}" class="w-7 h-7">
                    <span class="menu-text text-sm font-sans">Laporan</span>
                </a>

                {{-- History Data --}}
                @php
                    $historyActive = request()->is('historypenerimaan', 'historyperbaikan', 'historypengiriman');
                @endphp
                <button onclick="toggleSubmenu('historyMenu')" data-parent="historyMenu"
                    class="menu-btn parent-btn {{ $historyActive ? 'bg-gray-500 text-white' : '' }}">
                    <img src="{{ asset('images/logohistory.png') }}" class="w-7 h-7">
                    <span class="menu-text text-sm">Riwayat Data</span>
                    <span class="ml-auto menu-text">▾</span>
                </button>
                <div id="historyMenu"
                    class="submenu mt-1 space-y-1 bg-(--whitesmoke) rounded-b-lg {{ $historyActive ? 'open' : '' }}">

                    <a href="/historypenerimaan"
                        class="submenu-btn {{ request()->is('historypenerimaan') ? 'bg-(--blue) text-white' : '' }}">
                        <img src="{{ asset('images/penerimaan.png') }}" class="w-7 h-7">
                        <span class="menu-text text-sm font-sans">Penerimaan</span>
                    </a>

                    <a href="/historyperbaikan"
                        class="submenu-btn {{ request()->is('historyperbaikan') ? 'bg-(--blue) text-white' : '' }}">
                        <img src="{{ asset('images/perbaikan.png') }}" class="w-7 h-7">
                        <span class="menu-text text-sm font-sans">Perbaikan</span>
                    </a>

                    <a href="/historypengiriman"
                        class="submenu-btn {{ request()->is('historypengiriman') ? 'bg-(--blue) text-white' : '' }}">
                        <img src="{{ asset('images/pengiriman.png') }}" class="w-7 h-7">
                        <span class="menu-text text-sm font-sans">Pengiriman</span>
                    </a>
                </div>
            </nav>

            {{-- PROFILE SECTION - MOVED TO BOTTOM SIDEBAR --}}
            <div class="shrink-0 border-t-2 border-gray-300 pt-3 mt-2 mb-3">
                <div class="relative">
                    <button onclick="toggleProfileMenu()"
                        class="profile-button w-full flex items-center gap-3 focus:outline-none hover:bg-gray-300 hover:cursor-pointer p-2 rounded-lg">

                        @if (session('foto'))
                            <div class="w-10 h-10 rounded-full overflow-hidden shrink-0 border border-gray-300">
                                <img src="{{ asset('storage/' . session('foto')) }}"
                                    class="w-full h-full object-cover block" alt="Foto Profil">
                            </div>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="var(--blue)" class="w-10 h-10 shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        @endif

                        <div class="profile-info text-left leading-tight flex-1">
                            <h3 class="sidebar-text text-sm font-semibold text-(--blue)">
                                {{ session('nama_karyawan') }}
                            </h3>
                            <span class="text-xs font-normal text-gray-500">
                                {{ ucfirst(session('jabatan')) }}
                            </span>
                        </div>
                    </button>

                    <div id="profileMenu"
                        class="hidden absolute bottom-full mb-2 left-0 right-0 bg-gray-100 border border-gray-400 rounded-xl shadow-xl z-50 p-2">

                        <a href="/profil" class="block px-4 py-3 hover:bg-gray-300 rounded hover:cursor-pointer">
                            Lihat Profil
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-3 hover:bg-gray-300 rounded hover:cursor-pointer">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Tampilan Form -->
        <main class="flex-1 bg-(--whitesmoke) p-3 flex flex-col desk:min-w-0">

            <!-- HEADER -->
            <div class="h-13 mb-2 flex items-center p-1 justify-between font-semibold text-sm">

                <button onclick="toggleMobileSidebar()"
                    class="mobile-menu-toggle hidden p-2 rounded hover:bg-gray-300 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="var(--blue)" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5" />
                    </svg>
                </button>

                <div class="flex items-center gap-2 sm:gap-4 md:gap-8 flex-1 justify-center sm:justify-start">
                    <div id="tanggalrealtime" class="text-xs sm:text-sm">-</div>
                    <div id="shiftactual" class="text-xs sm:text-sm">-</div>
                </div>

                <a href="/notifikasi" class="shrink-0">
                    <div class="hover:bg-gray-300 p-2 sm:p-3 rounded-lg">
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="var(--blue)" class="w-6 h-6 sm:size-8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5" />
                            </svg>

                            <span id="notifCount"
                                class="absolute -top-1 -right-3
                   bg-red-500 text-white text-[10px] font-bold
                   min-w-5 h-5 px-1
                   flex items-center justify-center
                   rounded-full leading-none">
                                99+
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- KONTEN -->
            <div class="flex-1 min-h-0 flex flex-col overflow-y-auto overflow-x-hidden">

                @yield('konten')

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                @stack('scripts')

            </div>

        </main>
    </div>
</body>
<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    // Toggle sidebar - berbeda untuk desktop dan mobile
    function toggleSidebar() {
        const isMobile = window.innerWidth <= 768;

        if (isMobile) {
            toggleMobileSidebar();
        } else {
            sidebar.classList.toggle('collapsed');
        }
    }

    // Toggle mobile sidebar (slide in/out with overlay)
    function toggleMobileSidebar() {
        sidebar.classList.toggle('mobile-open');
        overlay.classList.toggle('active');

        if (sidebar.classList.contains('mobile-open')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    }

    // Close mobile sidebar when clicking menu items
    document.addEventListener('DOMContentLoaded', function() {
        const menuLinks = document.querySelectorAll('.menu-btn:not(.parent-btn), .submenu-btn');

        menuLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    setTimeout(() => {
                        if (sidebar.classList.contains('mobile-open')) {
                            toggleMobileSidebar();
                        }
                    }, 200);
                }
            });
        });
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        const isMobile = window.innerWidth <= 768;

        if (isMobile) {
            sidebar.classList.remove('collapsed');
        }

        if (window.innerWidth > 768) {
            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    });

    window.addEventListener('load', function() {
        if (window.innerWidth <= 768) {
            sidebar.classList.remove('collapsed');
        }
    });

    // Submenu toggle
    function toggleSubmenu(id) {
        const target = document.getElementById(id);
        const parentBtn = document.querySelector(`.parent-btn[data-parent="${id}"]`);
        const isOpen = target.classList.contains('open');

        if (window.innerWidth > 768 && sidebar.classList.contains('collapsed')) {
            return;
        }

        document.querySelectorAll('.submenu').forEach(menu => {
            if (menu !== target) {
                menu.classList.remove('open');
            }
        });

        document.querySelectorAll('.parent-btn').forEach(btn => {
            if (btn !== parentBtn && !btn.classList.contains('route-active')) {
                btn.classList.remove('bg-gray-500', 'text-white');
            }
        });

        if (!isOpen) {
            target.classList.add('open');
            if (!parentBtn.classList.contains('route-active')) {
                parentBtn.classList.add('bg-gray-500', 'text-white');
            }
        } else {
            target.classList.remove('open');
            if (!parentBtn.classList.contains('route-active')) {
                parentBtn.classList.remove('bg-gray-500', 'text-white');
            }
        }
    }

    // Mark route-active parent buttons on load
    window.addEventListener('load', () => {
        document.querySelectorAll('.parent-btn').forEach(btn => {
            if (btn.classList.contains('bg-gray-500')) {
                btn.classList.add('route-active');
            }
        });
    });

    // Profile menu toggle
    function toggleProfileMenu() {
        const menu = document.getElementById('profileMenu');
        menu.classList.toggle('hidden');
    }

    // Close profile menu when clicking outside
    document.addEventListener('click', function(e) {
        const menu = document.getElementById('profileMenu');
        const button = e.target.closest('button[onclick="toggleProfileMenu()"]');

        if (!menu || menu.classList.contains('hidden')) return;

        if (!menu.contains(e.target) && !button) {
            menu.classList.add('hidden');
        }
    });

    // DateTime and Shift functions
    let masterTime = new Date();

    function syncMasterTime() {
        fetch("{{ route('master.time.set') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                master_datetime: document.getElementById('master_datetime').value,
                master_shift: document.getElementById('master_shift').value
            })
        });
    }

    function getShift(date) {
        const jam = date.getHours();
        if (jam >= 0 && jam <= 7) return "1";
        if (jam >= 8 && jam <= 15) return "2";
        return "3";
    }

    function formatDateLocal(date) {
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        const h = String(date.getHours()).padStart(2, '0');
        const i = String(date.getMinutes()).padStart(2, '0');
        const s = String(date.getSeconds()).padStart(2, '0');
        return `${y}-${m}-${d} ${h}:${i}:${s}`;
    }

    function updateDateTime() {
        if (!window.manualMode) {
            masterTime = new Date();
        }

        const options = {
            weekday: 'long',
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        };

        const tanggalText = masterTime.toLocaleDateString('en-US', options);
        const jamText = masterTime.toLocaleTimeString('en-GB');

        document.getElementById('tanggalrealtime').textContent = `${tanggalText} [${jamText}]`;

        const shift = getShift(masterTime);
        document.getElementById('shiftactual').textContent = `Shift Actual: ${shift}`;

        // hidden input
        if (document.getElementById('master_datetime')) {
            document.getElementById('master_datetime').value = formatDateLocal(masterTime);
        }
        if (document.getElementById('master_shift')) {
            document.getElementById('master_shift').value = shift;
        }
    }

    updateDateTime();
    syncMasterTime();
    setInterval(updateDateTime, 1000);
    setInterval(syncMasterTime, 5000);
</script>

</html>
