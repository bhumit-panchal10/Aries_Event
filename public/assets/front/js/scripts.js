// Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
        
        // Header scroll behavior - fixed after 200px
        const mainHeader = document.getElementById('mainHeader');
        const headerPlaceholder = document.getElementById('headerPlaceholder');
        let lastScrollY = window.scrollY;
        let headerHeight = 0;
        
        // Get header height after page loads
        window.addEventListener('load', function() {
            headerHeight = mainHeader.offsetHeight;
        });
        
        window.addEventListener('scroll', function() {
            const currentScrollY = window.scrollY;
            
            // Add/remove fixed class based on scroll position
            if (currentScrollY > 200) {
                mainHeader.classList.add('header-fixed');
                headerPlaceholder.classList.add('active');
                 headerPlaceholder.style.height = '0px';
            } else {
                mainHeader.classList.remove('header-fixed');
                headerPlaceholder.classList.remove('active');
                headerPlaceholder.style.height = '0px';
            }
            
            lastScrollY = currentScrollY;
        });
        
        // Mobile menu toggle
        const mobileToggle = document.getElementById('mobileToggle');
        const navMenu = document.getElementById('navMenu');
        const menuOverlay = document.getElementById('menuOverlay');
        
        mobileToggle.addEventListener('click', function() {
            this.classList.toggle('active');
            navMenu.classList.toggle('active');
            menuOverlay.classList.toggle('active');
            document.body.style.overflow = navMenu.classList.contains('active') ? 'hidden' : '';
        });
        
        // Close mobile menu when clicking on overlay
        menuOverlay.addEventListener('click', function() {
            mobileToggle.classList.remove('active');
            navMenu.classList.remove('active');
            this.classList.remove('active');
            document.body.style.overflow = '';
        });
        
        // Close mobile menu when clicking on a link
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                mobileToggle.classList.remove('active');
                navMenu.classList.remove('active');
                menuOverlay.classList.remove('active');
                document.body.style.overflow = '';
            });
        });
        
        // Newsletter form submission
        document.querySelector('.newsletter-form-2025').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            
            // Create success message
            const successMsg = document.createElement('div');
            successMsg.className = 'alert alert-success mt-3';
            successMsg.textContent = `Thank you for subscribing with email: ${email}`;
            this.appendChild(successMsg);
            
            // Reset form
            this.reset();
            
            // Remove message after 3 seconds
            setTimeout(() => {
                successMsg.remove();
            }, 3000);
        });

        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
        
        // Header scroll behavior (same as index.html)
        // Copy the header scroll script from index.html
        
        // Mobile menu toggle (same as index.html)
        // Copy the mobile menu script from index.html
        
        // Filter functionality
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });


        
      
        