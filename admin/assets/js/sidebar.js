// Sidebar functionality
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.querySelector('.toggle-sidebar');
    const mainContent = document.getElementById('main-content');
    
    // Initialize sidebar state
    function initSidebar() {
        const sidebarState = localStorage.getItem('sidebarState');
        
        if (window.innerWidth <= 768) {
            // Mobile view - sidebar hidden by default
            sidebar.classList.remove('hide');
            sidebar.classList.remove('show');
        } else {
            // Desktop view
            if (sidebarState === 'collapsed') {
                sidebar.classList.add('hide');
            } else {
                sidebar.classList.remove('hide');
            }
        }
    }
    
    // Toggle sidebar
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                // Mobile behavior
                sidebar.classList.toggle('show');
            } else {
                // Desktop behavior
                sidebar.classList.toggle('hide');
                
                // Save sidebar state
                if (sidebar.classList.contains('hide')) {
                    localStorage.setItem('sidebarState', 'collapsed');
                } else {
                    localStorage.setItem('sidebarState', 'expanded');
                }
            }
        });
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768 && 
            sidebar && 
            !sidebar.contains(e.target) && 
            !e.target.classList.contains('toggle-sidebar') &&
            !e.target.closest('.toggle-sidebar')) {
            sidebar.classList.remove('show');
        }
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        initSidebar();
    });
    
    // Initialize sidebar on load
    initSidebar();
    
    // Add active class to current page
    function setActivePage() {
        const currentPage = window.location.pathname.split('/').pop() || 'index.php';
        const menuItems = document.querySelectorAll('.side-menu a');
        
        menuItems.forEach(item => {
            const href = item.getAttribute('href');
            if (href && (currentPage.includes(href) || href.includes(currentPage))) {
                item.parentElement.classList.add('active');
            } else {
                item.parentElement.classList.remove('active');
            }
        });
    }
    
    setActivePage();
});