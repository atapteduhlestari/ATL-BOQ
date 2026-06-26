<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ATL - BOQ MATERIAL')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Mobile menu toggle button */
        .menu-toggle {
            display: none;
            position: fixed;
            top: 12px;
            left: 12px;
            z-index: 50;
            padding: 8px 10px;
            background: #1a1a2e;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        
        /* Mobile overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 40;
        }
        
        .sidebar-overlay.active {
            display: block;
        }
        
        /* Mobile styles */
        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }
            
            .sidebar-wrapper {
                position: fixed;
                top: 0;
                left: 0;
                height: 100%;
                z-index: 45;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                width: 280px;
            }
            
            .sidebar-wrapper.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0 !important;
                padding-top: 56px;
            }
            
            main {
                padding: 12px !important;
            }
        }
        
        @media (min-width: 769px) {
            .sidebar-wrapper {
                position: fixed;
                top: 0;
                left: 0;
                height: 100%;
                z-index: 10;
                width: 256px;
            }
            
            .main-content {
                margin-left: 256px;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Mobile Menu Toggle Button -->
    <button class="menu-toggle" id="menuToggle" aria-label="Toggle sidebar">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="flex">
        <!-- Sidebar - dengan wrapper untuk mobile -->
        <div class="sidebar-wrapper" id="sidebarWrapper">
            <x-sidebar />
        </div>

        <!-- Main Content -->
        <div class="flex-1 main-content" id="mainContent">
            <!-- Navbar -->
            <x-navbar />

            <!-- Page Content -->
            <main class="p-6">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('menuToggle');
            const sidebar = document.getElementById('sidebarWrapper');
            const overlay = document.getElementById('sidebarOverlay');
            
            function openSidebar() {
                sidebar.classList.add('open');
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
            
            function closeSidebar() {
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            }
            
            if (toggleBtn) {
                toggleBtn.addEventListener('click', openSidebar);
            }
            
            if (overlay) {
                overlay.addEventListener('click', closeSidebar);
            }
            
            // Close sidebar with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeSidebar();
                }
            });
            
            // Close sidebar when clicking a link inside (optional)
            sidebar.querySelectorAll('a').forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        closeSidebar();
                    }
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>