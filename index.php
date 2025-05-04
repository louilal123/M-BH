<?php
session_start();
require_once 'admin/functions/connection.php';
require_once 'functions/load_tenant_data.php';

$tenantData = null;

if (isset($_SESSION['tenant_id'])) {
    $tenantData = loadTenantData($conn, $_SESSION['tenant_id']);
}
?>


 <!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
 <?php include "includes/header.php"; ?>
</head>
<body class="bg-slate-50 text-slate-700 dark:bg-slate-900 dark:text-slate-200 transition-all duration-300">

<?php include "includes/topnav.php"; ?>

  <!-- Hero Section With Swiper Slideshow -->
  <section id="home" class="relative min-h-screen flex items-center pt-16">
    <!-- Swiper slideshow -->
    <div class="swiper heroSwiper absolute inset-0 z-0">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
          <div class="w-full h-full bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1540518614846-7eded433c457?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2039&q=80');"></div>
        </div>
        <div class="swiper-slide">
          <div class="w-full h-full bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1615571022219-eb45cf7faa9d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');"></div>
        </div>
        <div class="swiper-slide">
          <div class="w-full h-full bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1598928506311-c55ded91a20c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');"></div>
        </div>
      </div>
      <div class="swiper-pagination"></div>
    </div>
    <div class="absolute inset-0 bg-gradient-to-r from-slate-900/80 to-slate-700/50 z-10"></div>
    <div class="container mx-auto px-4 relative z-20">
      <div class="max-w-3xl" data-aos="fade-up" data-aos-duration="1000">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
          Your Home Away From Home
        </h1>
        <p class="text-xl text-slate-200 mb-8 max-w-2xl">
          Experience premium boarding house living with MECMEC. Modern rooms, excellent amenities, and a welcoming community in the heart of the city.
        </p>
        <div class="flex flex-col sm:flex-row gap-4">
          <a href="rooms" class="bg-orange-500 hover:bg-primary-dark text-white px-8 py-4 rounded-full text-lg font-medium shadow-lg transition inline-block text-center">
            Book Now 
          </a>
          <a href="#contact" class="bg-white/10 hover:bg-white/20 backdrop-blur-md text-white border border-white/30 px-8 py-4 rounded-full text-lg font-medium shadow-lg transition inline-block text-center">
            Contact Us
          </a>
        </div>
      </div>
    </div>
    
    <!-- Scroll Down Indicator -->
    <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 text-white animate-bounce z-20">
      <a href="#about" class="flex flex-col items-center opacity-70 hover:opacity-100 transition">
        <span class="text-sm mb-2">Discover</span>
        <i class="fas fa-chevron-down"></i>
      </a>
    </div>
  </section>

  <!-- About Section -->
  <section id="about" class="py-16 md:py-24 bg-white dark:bg-slate-900">
    <div class="container mx-auto px-4">
      <div class="grid md:grid-cols-2 gap-12 items-center">
        <div class="order-2 md:order-1" data-aos="fade-right" data-aos-duration="1000">
          <h2 class="text-3xl md:text-4xl font-bold mb-6 text-slate-800 dark:text-slate-100">
            Welcome to <span class="text-primary dark:text-primary-light">MECMEC</span> Boarding House
          </h2>
          <p class="text-lg text-slate-600 dark:text-slate-300 mb-6">
            Established in 2015, MECMEC Boarding House has been providing quality and affordable accommodation for students and young professionals alike. Our mission is to create a safe, comfortable, and conducive environment for our boarders.
          </p>
          <p class="text-lg text-slate-600 dark:text-slate-300 mb-8">
            Located just minutes away from major universities, business districts, and transportation hubs, MECMEC offers convenience without compromising comfort and affordability.
          </p>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="p-4 bg-slate-50 dark:bg-slate-800 rounded-lg text-center" data-aos="fade-up" data-aos-delay="100">
              <h3 class="text-2xl font-bold text-primary dark:text-primary-light">24+</h3>
              <p class="text-slate-600 dark:text-slate-400">Rooms</p>
            </div>
            <div class="p-4 bg-slate-50 dark:bg-slate-800 rounded-lg text-center" data-aos="fade-up" data-aos-delay="200">
              <h3 class="text-2xl font-bold text-primary dark:text-primary-light">50+</h3>
              <p class="text-slate-600 dark:text-slate-400">Residents</p>
            </div>
            <div class="p-4 bg-slate-50 dark:bg-slate-800 rounded-lg text-center" data-aos="fade-up" data-aos-delay="300">
              <h3 class="text-2xl font-bold text-primary dark:text-primary-light">8+</h3>
              <p class="text-slate-600 dark:text-slate-400">Years</p>
            </div>
            <div class="p-4 bg-slate-50 dark:bg-slate-800 rounded-lg text-center" data-aos="fade-up" data-aos-delay="400">
              <h3 class="text-2xl font-bold text-primary dark:text-primary-light">4.9</h3>
              <p class="text-slate-600 dark:text-slate-400">Rating</p>
            </div>
          </div>
          <a href="#rooms" class="inline-block bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-full font-medium transition">
            Explore Our Rooms
          </a>
        </div>
        <div class="order-1 md:order-2" data-aos="fade-left" data-aos-duration="1000">
          <div class="relative">
            <img src="https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80" alt="MECMEC Boarding House" class="w-full h-auto rounded-xl shadow-lg">
            <div class="absolute -bottom-6 -right-6 bg-white dark:bg-slate-800 p-4 rounded-lg shadow-lg">
              <div class="flex items-center">
                <i class="fas fa-star text-yellow-400 mr-2"></i>
                <span class="font-bold">Trusted by 500+ boarders since 2015</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Room Types Section with Parallax background -->
  <section id="rooms" class=" py-16 md:py-24 relative">
    <div class="absolute inset-0 parallax opacity-20 dark:opacity-10" style="background-image: url('https://images.unsplash.com/photo-1560448204-603b3fc33ddc?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');"></div>
    <div class="bg-slate-50/80 dark:bg-slate-800/80 backdrop-blur-sm absolute inset-0"></div>
    <div class="container mx-auto px-4 relative">
      <div class="text-center mb-16" data-aos="fade-up">
        <h2 class="text-3xl md:text-4xl font-bold mb-4 text-slate-800 dark:text-slate-100">
          Find Your Perfect Room
        </h2>
        <p class="text-lg text-slate-600 dark:text-slate-300 max-w-3xl mx-auto">
          We offer a variety of room types to suit different needs and budgets. All rooms come with basic amenities and access to common areas.
        </p>
      </div>
      
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Room Card 1 -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-lg overflow-hidden room-card" data-aos="fade-up" data-aos-delay="100">
          <div class="h-64 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=870&q=80" alt="Single Room" class="w-full h-full object-cover">
          </div>
          <div class="p-6">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100">Single Room</h3>
              <span class="text-accent font-bold">₱5,000/mo</span>
            </div>
            <p class="text-slate-600 dark:text-slate-300 mb-4">
              Perfect for students or professionals who value privacy and independence. Cozy yet functional space with all the essentials.
            </p>
            <div class="border-t border-slate-200 dark:border-slate-700 pt-4">
              <div class="flex flex-wrap gap-2">
                <span class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs px-2 py-1 rounded-full">Single Bed</span>
                <span class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs px-2 py-1 rounded-full">12 m²</span>
                <span class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs px-2 py-1 rounded-full">Private Bathroom</span>
                <span class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs px-2 py-1 rounded-full">Study Desk</span>
              </div>
              <a href="#" class="block mt-4 text-center bg-primary hover:bg-primary-dark text-white py-2 rounded-md transition">Book Now</a>
            </div>
          </div>
        </div>
        
        <!-- Room Card 2 -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-lg overflow-hidden room-card" data-aos="fade-up" data-aos-delay="200">
          <div class="h-64 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1560185007-5f0bb1866cab?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=870&q=80" alt="Double Room" class="w-full h-full object-cover">
          </div>
          <div class="p-6">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100">Double Room</h3>
              <span class="text-accent font-bold">₱3,500/mo per person</span>
            </div>
            <p class="text-slate-600 dark:text-slate-300 mb-4">
              Economical option for friends or classmates who want to share space. Spacious room with dedicated areas for each occupant.
            </p>
            <div class="border-t border-slate-200 dark:border-slate-700 pt-4">
              <div class="flex flex-wrap gap-2">
                <span class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs px-2 py-1 rounded-full">2 Single Beds</span>
                <span class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs px-2 py-1 rounded-full">18 m²</span>
                <span class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs px-2 py-1 rounded-full">Shared Bathroom</span>
                <span class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs px-2 py-1 rounded-full">2 Study Desks</span>
              </div>
              <a href="#" class="block mt-4 text-center bg-primary hover:bg-primary-dark text-white py-2 rounded-md transition">Book Now</a>
            </div>
          </div>
        </div>
        
        <!-- Room Card 3 -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-lg overflow-hidden room-card" data-aos="fade-up" data-aos-delay="300">
          <div class="h-64 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1587985064135-0366536eab42?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=870&q=80" alt="Premium Room" class="w-full h-full object-cover">
          </div>
          <div class="p-6">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100">Premium Room</h3>
              <span class="text-accent font-bold">₱8,000/mo</span>
            </div>
            <p class="text-slate-600 dark:text-slate-300 mb-4">
              Our best offering for those who want extra comfort and amenities. Larger space with premium furnishings and exclusive perks.
            </p>
            <div class="border-t border-slate-200 dark:border-slate-700 pt-4">
              <div class="flex flex-wrap gap-2">
                <span class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs px-2 py-1 rounded-full">Queen Bed</span>
                <span class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs px-2 py-1 rounded-full">24 m²</span>
                <span class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs px-2 py-1 rounded-full">Private Bathroom</span>
                <span class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs px-2 py-1 rounded-full">Mini Fridge</span>
              </div>
              <a href="#" class="block mt-4 text-center bg-primary hover:bg-primary-dark text-white py-2 rounded-md transition">Book Now</a>
            </div>
          </div>
        </div>
      </div>
      
      <div class="text-center mt-12">
        <a href="#" class="inline-block bg-white dark:bg-slate-800 border border-primary text-primary dark:text-primary-light hover:bg-primary hover:text-white dark:hover:bg-primary-light dark:hover:text-slate-900 px-8 py-3 rounded-full font-medium transition-all duration-300">
          View All Room Types
        </a>
      </div>
    </div>
  </section>

  <!-- Amenities Section -->
  <section id="amenities" class="py-16 md:py-24 bg-white dark:bg-slate-900">
    <div class="container mx-auto px-4">
      <div class="text-center mb-16" data-aos="fade-up">
        <h2 class="text-3xl md:text-4xl font-bold mb-4 text-slate-800 dark:text-slate-100">
          Excellent Amenities
        </h2>
        <p class="text-lg text-slate-600 dark:text-slate-300 max-w-3xl mx-auto">
          We provide a range of facilities to ensure your stay is comfortable, productive, and enjoyable.
        </p>
      </div>
      
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Amenity 1 -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-md" data-aos="fade-up" data-aos-delay="100">
          <div class="w-14 h-14 bg-primary/10 dark:bg-primary/20 rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-wifi text-2xl text-primary dark:text-primary-light"></i>
          </div>
          <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-3">High-Speed WiFi</h3>
          <p class="text-slate-600 dark:text-slate-300">
            Stay connected with our reliable high-speed fiber internet connection available throughout the building.
          </p>
        </div>
        
        <!-- Amenity 2 -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-md" data-aos="fade-up" data-aos-delay="200">
          <div class="w-14 h-14 bg-primary/10 dark:bg-primary/20 rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-kitchen-set text-2xl text-primary dark:text-primary-light"></i>
          </div>
          <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-3">Communal Kitchen</h3>
          <p class="text-slate-600 dark:text-slate-300">
            Fully-equipped kitchen with modern appliances for those who enjoy preparing their own meals.
          </p>
        </div>
        
        <!-- Amenity 3 -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-md" data-aos="fade-up" data-aos-delay="300">
          <div class="w-14 h-14 bg-primary/10 dark:bg-primary/20 rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-couch text-2xl text-primary dark:text-primary-light"></i>
          </div>
          <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-3">Comfortable Lounge</h3>
          <p class="text-slate-600 dark:text-slate-300">
            Spacious common area with comfortable seating, TV, and games for relaxation and socializing.
          </p>
        </div>
        
        <!-- Amenity 4 -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-md" data-aos="fade-up" data-aos-delay="400">
          <div class="w-14 h-14 bg-primary/10 dark:bg-primary/20 rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-book text-2xl text-primary dark:text-primary-light"></i>
          </div>
          <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-3">Study Room</h3>
          <p class="text-slate-600 dark:text-slate-300">
            Quiet and conducive space for studying or working, with desks and ergonomic chairs.
          </p>
        </div>
        
        <!-- Amenity 5 -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-md" data-aos="fade-up" data-aos-delay="500">
          <div class="w-14 h-14 bg-primary/10 dark:bg-primary/20 rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-washer text-2xl text-primary dark:text-primary-light"></i>
          </div>
          <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-3">Laundry Facilities</h3>
          <p class="text-slate-600 dark:text-slate-300">
            In-house laundry room with washers and dryers available for residents at affordable rates.
          </p>
        </div>
        
        <!-- Amenity 6 -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-md" data-aos="fade-up" data-aos-delay="600">
          <div class="w-14 h-14 bg-primary/10 dark:bg-primary/20 rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-shield-alt text-2xl text-primary dark:text-primary-light"></i>
          </div>
          <h3 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-3">24/7 Security</h3>
          <p class="text-slate-600 dark:text-slate-300">
            Round-the-clock security personnel and CCTV surveillance for your safety and peace of mind.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Testimonials Section with Swiper -->
  <section id="testimonials" class="py-16 md:py-24 bg-gradient-to-r from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-900">
    <div class="container mx-auto px-4">
      <div class="text-center mb-16" data-aos="fade-up">
        <h2 class="text-3xl md:text-4xl font-bold mb-4 text-slate-800 dark:text-slate-100">
          What Our Residents Say
        </h2>
        <p class="text-lg text-slate-600 dark:text-slate-300 max-w-3xl mx-auto">
          Hear from those who call MECMEC Boarding House their home away from home.
        </p>
      </div>
      
      <!-- Testimonial Slider -->
      <div class="swiper testimonialSwiper" data-aos="fade-up">
        <div class="swiper-wrapper pb-12">
          <!-- Testimonial 1 -->
          <div class="swiper-slide">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-8">
              <div class="flex items-center mb-4">
                <div class="text-amber-400 flex">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
                <span class="ml-2 text-slate-500 dark:text-slate-400">5.0</span>
              </div>
              <p class="text-slate-600 dark:text-slate-300 mb-6 italic">
                "Living at MECMEC has been an amazing experience. The facilities are well-maintained, the staff is friendly and responsive, and I've made lifelong friends here. Couldn't ask for a better place to stay during my college years!"
              </p>
              <div class="flex items-center">
                <img src="https://randomuser.me/api/portraits/women/12.jpg" alt="Sarah L." class="w-12 h-12 rounded-full mr-4">
                <div>
                  <h4 class="font-bold text-slate-800 dark:text-white">Sarah L.</h4>
                  <p class="text-sm text-slate-500 dark:text-slate-400">College Student • 2 years resident</p>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Testimonial 2 -->
          <div class="swiper-slide">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-8">
              <div class="flex items-center mb-4">
                <div class="text-amber-400 flex">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star-half-alt"></i>
                </div>
                <span class="ml-2 text-slate-500 dark:text-slate-400">4.5</span>
              </div>
              <p class="text-slate-600 dark:text-slate-300 mb-6 italic">
                "As a young professional, I needed a place that's convenient, clean, and conducive to work. MECMEC ticks all those boxes and more. The high-speed internet and study room are perfect for remote working days!"
              </p>
              <div class="flex items-center">
                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Mark T." class="w-12 h-12 rounded-full mr-4">
                <div>
                  <h4 class="font-bold text-slate-800 dark:text-white">Mark T.</h4>
                  <p class="text-sm text-slate-500 dark:text-slate-400">Software Engineer • 1 year resident</p>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Testimonial 3 -->
          <div class="swiper-slide">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-8">
              <div class="flex items-center mb-4">
                <div class="text-amber-400 flex">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                </div>
                <span class="ml-2 text-slate-500 dark:text-slate-400">5.0</span>
              </div>
              <p class="text-slate-600 dark:text-slate-300 mb-6 italic">
                "I chose MECMEC because of its proximity to my university. What I didn't expect was the sense of community I'd find here. The staff treats everyone like family, and the facilities are top-notch. Best decision I made!"
              </p>
              <div class="flex items-center">
                <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="Jessica K." class="w-12 h-12 rounded-full mr-4">
                <div>
                  <h4 class="font-bold text-slate-800 dark:text-white">Jessica K.</h4>
                  <p class="text-sm text-slate-500 dark:text-slate-400">Medical Student • 3 years resident</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="swiper-pagination"></div>
      </div>
    </div>
  </section>
  
  <!-- Gallery Section with Masonry Layout -->
  <section id="gallery" class="py-16 md:py-24 bg-white dark:bg-slate-900">
    <div class="container mx-auto px-4">
      <div class="text-center mb-16" data-aos="fade-up">
        <h2 class="text-3xl md:text-4xl font-bold mb-4 text-slate-800 dark:text-slate-100">
          Gallery
        </h2>
        <p class="text-lg text-slate-600 dark:text-slate-300 max-w-3xl mx-auto">
          Take a visual tour of our boarding house and facilities.
        </p>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div data-aos="fade-up" data-aos-delay="100" class="gallery-item overflow-hidden rounded-xl">
          <img src="https://images.unsplash.com/photo-1560448205-4d9b3e6bb6db?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=870&q=80" alt="Lobby" class="w-full h-full object-cover transition-transform hover:scale-105">
        </div>
        <div data-aos="fade-up" data-aos-delay="200" class="gallery-item overflow-hidden rounded-xl md:row-span-2">
          <img src="https://images.unsplash.com/photo-1505692952047-1a78307da8f2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=870&q=80" alt="Common Area" class="w-full h-full object-cover transition-transform hover:scale-105">
        </div>
        <div data-aos="fade-up" data-aos-delay="300" class="gallery-item overflow-hidden rounded-xl">
          <img src="https://images.unsplash.com/photo-1555854877-bab0e564b8d5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=869&q=80" alt="Kitchen" class="w-full h-full object-cover transition-transform hover:scale-105">
        </div>
        <div data-aos="fade-up" data-aos-delay="400" class="gallery-item overflow-hidden rounded-xl lg:col-span-2">
          <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0c2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=870&q=80" alt="Building Exterior" class="w-full h-full object-cover transition-transform hover:scale-105">
        </div>
        <div data-aos="fade-up" data-aos-delay="500" class="gallery-item overflow-hidden rounded-xl">
          <img src="https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=874&q=80" alt="Study Room" class="w-full h-full object-cover transition-transform hover:scale-105">
        </div>
      </div>
      
      <div class="text-center mt-12">
        <a href="#" class="inline-block bg-primary hover:bg-primary-dark text-white px-8 py-3 rounded-full font-medium transition">
          View Full Gallery
        </a>
      </div>
    </div>
  </section>
  
  <!-- Contact Section -->
  <section id="contact" class="py-16 md:py-24 bg-gradient-to-r from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-900">
    <div class="container mx-auto px-4">
      <div class="grid md:grid-cols-2 gap-12 items-center">
        <div data-aos="fade-right">
          <h2 class="text-3xl md:text-4xl font-bold mb-6 text-slate-800 dark:text-slate-100">
            Get in Touch
          </h2>
          <p class="text-lg text-slate-600 dark:text-slate-300 mb-8">
            Have questions about our rooms or services? Feel free to reach out and we'll get back to you as soon as possible.
          </p>
          
          <div class="space-y-6">
            <div class="flex items-start">
              <div class="w-12 h-12 bg-primary/10 dark:bg-primary/20 rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-map-marker-alt text-primary dark:text-primary-light"></i>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-1">Location</h3>
                <p class="text-slate-600 dark:text-slate-300">
                  123 University Avenue<br>
                  Metro City, 12345
                </p>
              </div>
            </div>
            
            <div class="flex items-start">
              <div class="w-12 h-12 bg-primary/10 dark:bg-primary/20 rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-phone text-primary dark:text-primary-light"></i>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-1">Phone</h3>
                <p class="text-slate-600 dark:text-slate-300">
                  +123 456 7890<br>
                  +123 456 7891
                </p>
              </div>
            </div>
            
            <div class="flex items-start">
              <div class="w-12 h-12 bg-primary/10 dark:bg-primary/20 rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-envelope text-primary dark:text-primary-light"></i>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-1">Email</h3>
                <p class="text-slate-600 dark:text-slate-300">
                  info@mecmecbh.com<br>
                  bookings@mecmecbh.com
                </p>
              </div>
            </div>
            
            <div class="flex items-start">
              <div class="w-12 h-12 bg-primary/10 dark:bg-primary/20 rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-clock text-primary dark:text-primary-light"></i>
              </div>
              <div>
                <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-1">Office Hours</h3>
                <p class="text-slate-600 dark:text-slate-300">
                  Monday - Friday: 9:00 AM - 6:00 PM<br>
                  Saturday: 10:00 AM - 2:00 PM
                </p>
              </div>
            </div>
          </div>
          
          <div class="mt-8">
            <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 mb-4">Follow Us</h3>
            <div class="flex space-x-4">
              <a href="#" class="w-10 h-10 bg-slate-200 dark:bg-slate-700 rounded-full flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-primary hover:text-white transition">
                <i class="fab fa-facebook-f"></i>
              </a>
              <a href="#" class="w-10 h-10 bg-slate-200 dark:bg-slate-700 rounded-full flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-primary hover:text-white transition">
                <i class="fab fa-instagram"></i>
              </a>
              <a href="#" class="w-10 h-10 bg-slate-200 dark:bg-slate-700 rounded-full flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-primary hover:text-white transition">
                <i class="fab fa-twitter"></i>
              </a>
              <a href="#" class="w-10 h-10 bg-slate-200 dark:bg-slate-700 rounded-full flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-primary hover:text-white transition">
                <i class="fab fa-youtube"></i>
              </a>
            </div>
          </div>
        </div>
        
        <div data-aos="fade-left">
          <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg p-8">
            <h3 class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-6">Send Us a Message</h3>
            <form class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label for="name" class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Name</label>
                  <input type="text" id="name" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-700 text-slate-800 dark:text-white" required>
                </div>
                <div>
                  <label for="email" class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Email</label>
                  <input type="email" id="email" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-700 text-slate-800 dark:text-white" required>
                </div>
              </div>
              <div>
                <label for="subject" class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Subject</label>
                <input type="text" id="subject" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-700 text-slate-800 dark:text-white" required>
              </div>
              <div>
                <label for="message" class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">Message</label>
                <textarea id="message" rows="5" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent bg-white dark:bg-slate-700 text-slate-800 dark:text-white" required></textarea>
              </div>
              <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-medium py-3 rounded-lg transition">
                Send Message
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <!-- Map Section -->
  <section class="h-96 relative">
  <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3912.9695977702677!2d123.72098687486427!3d11.263645088916297!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a8812c39e534e1%3A0xc4b9b1f22b82e5e7!2sAnaliza%20Boarding%20House!5e0!3m2!1sen!2sph!4v1746154692947!5m2!1sen!2sph"   width="100%" height="100%" style="border:0;" allowfullscreen="" 
    loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      width="100%" height="100%" style="border:0;" allowfullscreen="" 
      loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
  </section>
  
  <!-- CTA Section -->
  <section class="py-16 md:py-24 bg-primary text-white relative overflow-hidden">
    <div class="container mx-auto px-4 relative z-10">
      <div class="text-center max-w-3xl mx-auto" data-aos="fade-up">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">
          Ready to Move In?
        </h2>
        <p class="text-lg text-white/80 mb-8">
          Book your room now and become part of our vibrant community. Limited rooms available, secure yours today!
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
          <a href="#rooms" class="bg-white text-primary hover:bg-slate-100 px-8 py-4 rounded-full text-lg font-medium shadow-lg transition inline-block">
            View Rooms
          </a>
          <a href="#" class="bg-white/10 hover:bg-white/20 border border-white/30 px-8 py-4 rounded-full text-lg font-medium shadow-lg transition inline-block">
            Book a Tour
          </a>
        </div>
      </div>
    </div>
    
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
            <span class="text-orange-400 dark:text-orange-400 text-lg font-semibold mt-3">BH</span>
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
    <?php include "includes/footer.php" ?>
</body>
</html>