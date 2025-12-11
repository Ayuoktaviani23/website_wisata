  document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.querySelector('.toggle-btn');
            const mobileToggle = document.querySelector('.mobile-toggle');
            const hideSidebarBtn = document.getElementById('hide-sidebar');
            
            // Toggle sidebar collapse/expand on desktop
            toggleBtn.addEventListener('click', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.toggle('collapsed');
                }
            });
            
            // Toggle sidebar show/hide on mobile
            mobileToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });
            
            // Hide sidebar completely
            hideSidebarBtn.addEventListener('click', function() {
                sidebar.classList.add('hidden');
            });
            
            // Show sidebar when clicking the main mobile toggle
            document.querySelector('.mobile-toggle').addEventListener('click', function() {
                if (sidebar.classList.contains('hidden')) {
                    sidebar.classList.remove('hidden');
                }
            });
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 768 && 
                    !sidebar.contains(event.target) && 
                    !mobileToggle.contains(event.target) && 
                    sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                }
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('show');
                } else {
                    sidebar.classList.remove('collapsed');
                }
            });
            
            // Add active class to clicked menu items
            const menuItems = document.querySelectorAll('.side-menu li');
            menuItems.forEach(item => {
                item.addEventListener('click', function() {
                    menuItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });