

  <!-- Scripts -->
  <!-- AOS Animation Library -->
  <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
  <!-- Swiper JS -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
  
  <script>
    // Initialize AOS
    AOS.init({
      duration: 800,
      offset: 100,
      once: true
    });
    
    // Dark Mode Toggle
    const themeToggle = document.getElementById('theme-toggle');
    const mobileThemeToggle = document.getElementById('mobile-theme-toggle');
    
    function toggleDarkMode() {
      document.documentElement.classList.toggle('dark');
      localStorage.setItem('darkMode', document.documentElement.classList.contains('dark') ? 'enabled' : 'disabled');
    }
    
    // Check user preference
    if (localStorage.getItem('darkMode') === 'enabled' || 
        (localStorage.getItem('darkMode') === null && 
         window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.documentElement.classList.add('dark');
    }
    
    themeToggle.addEventListener('click', toggleDarkMode);
    mobileThemeToggle.addEventListener('click', toggleDarkMode);
    
    // Mobile Menu Toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    mobileMenuButton.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
      mobileMenuButton.innerHTML = mobileMenu.classList.contains('hidden') ? 
        '<i class="fas fa-bars text-xl"></i>' : 
        '<i class="fas fa-times text-xl"></i>';
    });
    
    // Mobile Dropdown Toggles
    const mobileDropdownToggles = document.querySelectorAll('.mobile-dropdown-toggle');
    
    mobileDropdownToggles.forEach(toggle => {
      toggle.addEventListener('click', () => {
        const dropdown = toggle.nextElementSibling;
        dropdown.classList.toggle('hidden');
        
        const icon = toggle.querySelector('i');
        if (dropdown.classList.contains('hidden')) {
          icon.className = 'fas fa-chevron-down text-xs';
        } else {
          icon.className = 'fas fa-chevron-up text-xs';
        }
      });
    });
    
    // Navbar Scroll Effect
    function handleScroll() {
      const navbar = document.querySelector('.navbar');
      if (window.scrollY > 50) {
        navbar.classList.add('navbar-scrolled');
        navbar.classList.remove('navbar-transparent');
      } else {
        navbar.classList.remove('navbar-scrolled');
        navbar.classList.add('navbar-transparent');
      }
      
      // Back to Top Button
      const backToTop = document.getElementById('back-to-top');
      if (window.scrollY > 300) {
        backToTop.classList.remove('opacity-0', 'invisible');
        backToTop.classList.add('opacity-100', 'visible');
      } else {
        backToTop.classList.add('opacity-0', 'invisible');
        backToTop.classList.remove('opacity-100', 'visible');
      }
    }
    
    window.addEventListener('scroll', handleScroll);
    handleScroll(); // Initialize on page load
    
    // Initialize Hero Swiper
    const heroSwiper = new Swiper('.heroSwiper', {
      loop: true,
      effect: 'fade',
      speed: 1000,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
    });
    
    // Initialize Testimonial Swiper
    const testimonialSwiper = new Swiper('.testimonialSwiper', {
      slidesPerView: 1,
      spaceBetween: 30,
      loop: true,
      autoplay: {
        delay: 4000,
        disableOnInteraction: false,
      },
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      breakpoints: {
        768: {
          slidesPerView: 2,
        },
        1024: {
          slidesPerView: 3,
        },
      },
    });
    
    function openModal(modalId) {
  const modal = document.getElementById(modalId);
  modal.classList.add('active');
  modal.classList.remove('hidden'); // Show it
  document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  modal.classList.remove('active');
  modal.classList.add('hidden'); // Hide it again
  document.body.style.overflow = 'auto';
}

    
    // Close modal when clicking outside the content
    window.addEventListener('click', (e) => {
      document.querySelectorAll('.modal').forEach(modal => {
        if (e.target === modal) {
          closeModal(modal.id);
        }
      });
    });
  </script>

<script>
    document.getElementById('logoutBtn').addEventListener('click', function(e) {
        e.preventDefault(); 

        Swal.fire({
            title: 'Are you sure?',
            text: 'You are about to log out!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            cancelButtonText: 'Cancel',
            confirmButtonText: 'Logout',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'functions/logout.php';
            }
        });
    });
    </script>

    
<?php if (isset($_SESSION['status']) && $_SESSION['status'] != ''): ?>
<script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: "<?php echo $_SESSION['status_icon']; ?>",
        title: "<?php echo $_SESSION['status']; ?>",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
</script>
<?php
unset($_SESSION['status']);
unset($_SESSION['status_icon']);
?>
<?php endif; ?>

   