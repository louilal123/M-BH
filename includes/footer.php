
    <!-- Decorative Elements -->
    <div class="absolute top-0 left-0 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 right-0 w-80 h-80 bg-white/5 rounded-full blur-3xl"></div>
  </section>

  <!-- Footer -->
  <footer class="bg-slate-900 text-slate-300">
    <div class="container mx-auto px-4 py-16">
      <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-12">
        <div>
          <a href="#" class="flex items-center gap-2 mb-6">
            <span class="text-white text-2xl font-bold font-heading">MECMEC</span>
            <span class="text-sm bg-accent text-white px-2 py-1 rounded">BH</span>
          </a>
          <p class="mb-6 text-slate-400">
            Providing quality accommodation for students and young professionals since 2015.
          </p>
          <div class="flex space-x-4">
            <a href="#" class="text-slate-400 hover:text-primary transition">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="text-slate-400 hover:text-primary transition">
              <i class="fab fa-instagram"></i>
            </a>
            <a href="#" class="text-slate-400 hover:text-primary transition">
              <i class="fab fa-twitter"></i>
            </a>
            <a href="#" class="text-slate-400 hover:text-primary transition">
              <i class="fab fa-youtube"></i>
            </a>
          </div>
        </div>
        
        <div>
          <h3 class="text-white text-lg font-bold mb-6">Quick Links</h3>
          <ul class="space-y-3">
            <li><a href="#home" class="text-slate-400 hover:text-primary transition">Home</a></li>
            <li><a href="#rooms" class="text-slate-400 hover:text-primary transition">Rooms</a></li>
            <li><a href="#amenities" class="text-slate-400 hover:text-primary transition">Amenities</a></li>
            <li><a href="#gallery" class="text-slate-400 hover:text-primary transition">Gallery</a></li>
            <li><a href="#testimonials" class="text-slate-400 hover:text-primary transition">Testimonials</a></li>
            <li><a href="#contact" class="text-slate-400 hover:text-primary transition">Contact</a></li>
          </ul>
        </div>
        
        <div>
          <h3 class="text-white text-lg font-bold mb-6">Contact Info</h3>
          <ul class="space-y-4">
            <li class="flex items-start">
              <i class="fas fa-map-marker-alt mt-1 mr-3 text-primary"></i>
              <span>123 University Avenue<br>Metro City, 12345</span>
            </li>
            <li class="flex items-center">
              <i class="fas fa-phone mr-3 text-primary"></i>
              <span>+123 456 7890</span>
            </li>
            <li class="flex items-center">
              <i class="fas fa-envelope mr-3 text-primary"></i>
              <span>info@mecmecbh.com</span>
            </li>
          </ul>
        </div>
        
        <div>
          <h3 class="text-white text-lg font-bold mb-6">Newsletter</h3>
          <p class="mb-4 text-slate-400">
            Subscribe to our newsletter to get updates on our latest offers.
          </p>
          <form class="flex flex-col space-y-3">
            <input type="email" placeholder="Your email" class="px-4 py-2 bg-slate-800 border border-slate-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-white">
            <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-medium py-2 rounded-lg transition">
              Subscribe
            </button>
          </form>
        </div>
      </div>
      
      <div class="border-t border-slate-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
        <p class="text-slate-400 mb-4 md:mb-0">
          &copy; 2025 MECMEC Boarding House. All rights reserved.
        </p>
        <div class="flex space-x-6">
          <a href="#" class="text-slate-400 hover:text-primary transition">Terms of Service</a>
          <a href="#" class="text-slate-400 hover:text-primary transition">Privacy Policy</a>
          <a href="#" class="text-slate-400 hover:text-primary transition">Cookie Policy</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- Back to Top Button -->
  <a href="#home" id="back-to-top" class="fixed bottom-8 right-8 bg-primary text-white w-12 h-12 rounded-full flex items-center justify-center shadow-lg transition-all duration-300 opacity-0 invisible">
    <i class="fas fa-arrow-up"></i>
  </a>

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
    
    // Modal Functions
    function openModal(modalId) {
      const modal = document.getElementById(modalId);
      modal.classList.add('active');
      document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }
    
    function closeModal(modalId) {
      const modal = document.getElementById(modalId);
      modal.classList.remove('active');
      document.body.style.overflow = 'auto'; // Restore scrolling
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

    
 <!-- SweetAlert display script -->
 <?php if (isset($_SESSION['status']) && $_SESSION['status'] != ''): ?>
        <script>
            Swal.fire({
                icon: "<?php echo $_SESSION['status_icon']; ?>",
                title: "<?php echo $_SESSION['status']; ?>",
                confirmButtonText: "Ok"
            });
        </script>
        <?php
        unset($_SESSION['status']);
        unset($_SESSION['status_icon']);
        ?>
    <?php endif; ?>

   