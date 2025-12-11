// Header functionality
document.addEventListener('DOMContentLoaded', function() {
    // Dark mode toggle
    const switchMode = document.getElementById('switch-mode');
    if (switchMode) {
        switchMode.addEventListener('change', function() {
            if (this.checked) {
                document.body.classList.add('dark');
                localStorage.setItem('darkMode', 'enabled');
            } else {
                document.body.classList.remove('dark');
                localStorage.setItem('darkMode', 'disabled');
            }
        });
    }

    // Load dark mode preference
    if (localStorage.getItem('darkMode') === 'enabled') {
        document.body.classList.add('dark');
        document.getElementById('switch-mode').checked = true;
    }

    // Search form toggle for mobile
    const searchBtn = document.querySelector('.search-btn');
    const searchForm = document.querySelector('.search-form');
    
    if (searchBtn && searchForm) {
        searchBtn.addEventListener('click', function(e) {
            if (window.innerWidth <= 576) {
                e.preventDefault();
                searchForm.classList.toggle('show');
            }
        });
    }
});