<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InfusedWater - Management System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100">
    {{ $slot }}
    @livewireScripts

    <!-- Mobile Sidebar Script -->
    <script>
        function initializeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('sidebar-toggle');

            // Toggle sidebar on button click
            if (toggle && sidebar) {
                // Remove existing listener jika ada
                toggle.replaceWith(toggle.cloneNode(true));
                const newToggle = document.getElementById('sidebar-toggle');

                newToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    sidebar.classList.toggle('hidden');
                    sidebar.classList.toggle('fixed');
                    sidebar.classList.toggle('inset-0');
                    sidebar.classList.toggle('z-40');
                    sidebar.classList.toggle('bg-white');
                });
            }

            // Close sidebar when clicking outside (only on mobile)
            document.addEventListener('click', function(event) {
                if (sidebar && window.innerWidth < 1024) {
                    const isClickInsideSidebar = sidebar.contains(event.target);
                    const isClickOnToggle = document.getElementById('sidebar-toggle')?.contains(event.target);

                    if (!isClickInsideSidebar && !isClickOnToggle && !sidebar.classList.contains('hidden')) {
                        sidebar.classList.add('hidden');
                        sidebar.classList.remove('fixed', 'inset-0', 'z-40', 'bg-white');
                    }
                }
            });

            // Prevent closing when clicking inside sidebar
            if (sidebar) {
                sidebar.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', initializeSidebar);

        // Re-initialize after Livewire navigation
        document.addEventListener('livewire:navigated', initializeSidebar);

        // Fallback untuk Livewire versi lama
        document.addEventListener('livewire:load', initializeSidebar);
    </script>
</body>
</html>
