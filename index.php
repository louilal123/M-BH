<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MECMEC BH | Modern Boarding House</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* Reset and Base Styles */
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --navy: #0A1435;
            --gold: #D4AF37;
            --light-gold: #E6C566;
            --white: #FFFFFF;
            --off-white: #F8F8F8;
            --light-gray: #EEEEEE;
            --medium-gray: #888888;
            --dark-gray: #333333;
            --transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        html {
            scroll-behavior: smooth;
            font-size: 16px;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            color: var(--dark-gray);
            background-color: var(--white);
            overflow-x: hidden;
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--navy);
            line-height: 1.3;
        }

        p {
            margin-bottom: 1.5rem;
        }

        a {
            text-decoration: none;
            color: inherit;
            transition: var(--transition);
        }

        img {
            max-width: 100%;
            height: auto;
            display: block;
        }

        .container {
            width: 90%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        section {
            padding: 6rem 0;
            position: relative;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 0.8rem 2rem;
            border-radius: 0;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .btn::after {
            content: '';
            position: absolute;
            width: 0;
            height: 100%;
            top: 0;
            left: 0;
            background-color: rgba(255, 255, 255, 0.2);
            transition: var(--transition);
            z-index: -1;
        }

        .btn:hover::after {
            width: 100%;
        }

        .btn-primary {
            background-color: var(--gold);
            color: var(--navy);
            border: 1px solid var(--gold);
        }

        .btn-primary:hover {
            background-color: var(--light-gold);
        }

        .btn-secondary {
            background-color: transparent;
            color: var(--gold);
            border: 1px solid var(--gold);
        }

        .btn-secondary:hover {
            background-color: var(--gold);
            color: var(--white);
        }

        /* Header & Navigation */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: rgba(10, 20, 53, 0.95);
            z-index: 1000;
            padding: 1rem 0;
            transition: var(--transition);
        }

        header.scrolled {
            padding: 0.7rem 0;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo a {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--white);
        }

        .logo span {
            color: var(--gold);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 2.5rem;
        }

        .nav-links a {
            color: var(--white);
            font-size: 0.9rem;
            font-weight: 400;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 0;
            background-color: var(--gold);
            transition: var(--transition);
        }

        .nav-links a:hover::after,
        .nav-links a.active::after {
            width: 100%;
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
            margin-left: 2rem;
        }

        .auth-buttons .btn {
            padding: 0.6rem 1.5rem;
            font-size: 0.8rem;
        }

        .mobile-toggle {
            display: none;
            flex-direction: column;
            gap: 6px;
            cursor: pointer;
        }

        .mobile-toggle span {
            width: 30px;
            height: 2px;
            background-color: var(--white);
            transition: var(--transition);
        }

        /* Hero Section */
        .hero {
            height: 100vh;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            background-color: var(--navy);
        }

        .hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.6;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 800px;
            color: var(--white);
            padding: 0 1rem;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeUp 1s forwards 0.5s;
        }

        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero h1 {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            color: var(--white);
            line-height: 1.2;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2.5rem;
            color: var(--white);
            font-weight: 300;
        }

        .hero .btn {
            padding: 1rem 2.5rem;
            font-size: 1rem;
        }

        .scroll-arrow {
            position: absolute;
            bottom: 50px;
            left: 50%;
            transform: translateX(-50%);
            animation: bounce 2s infinite;
            cursor: pointer;
            z-index: 3;
            color: var(--white);
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0) translateX(-50%);
            }
            40% {
                transform: translateY(-20px) translateX(-50%);
            }
            60% {
                transform: translateY(-10px) translateX(-50%);
            }
        }

        /* About Section */
        .about {
            background-color: var(--white);
        }

        .about-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5rem;
            align-items: center;
        }

        .about-image {
            position: relative;
            height: 500px;
            overflow: hidden;
        }

        .about-image img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: scale(1.05);
            transition: var(--transition);
        }

        .about-image::before {
            content: '';
            position: absolute;
            top: 30px;
            left: 30px;
            width: calc(100% - 30px);
            height: calc(100% - 30px);
            border: 2px solid var(--gold);
            z-index: 1;
            transition: var(--transition);
        }

        .about-content {
            padding-right: 2rem;
        }

        .section-heading {
            position: relative;
            margin-bottom: 3rem;
            padding-bottom: 1.5rem;
        }

        .section-heading::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 3px;
            background-color: var(--gold);
        }

        .section-heading h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .section-heading p {
            color: var(--gold);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-size: 0.8rem;
        }

        .about-features {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-top: 3rem;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: rgba(212, 175, 55, 0.1);
            color: var(--gold);
            font-size: 1.5rem;
        }

        .feature-text h4 {
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }

        .feature-text p {
            font-size: 0.9rem;
            color: var(--medium-gray);
            margin-bottom: 0;
        }

        /* Rooms Section */
        .rooms {
            background-color: var(--off-white);
            overflow: hidden;
        }

        .room-slider-container {
            position: relative;
            margin-top: 3rem;
        }

        .room-slider {
            display: flex;
            gap: 2rem;
            width: 100%;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scrollbar-width: none;
            -ms-overflow-style: none;
            padding-bottom: 1rem;
        }

        .room-slider::-webkit-scrollbar {
            display: none;
        }

        .room-card {
            flex: 0 0 350px;
            scroll-snap-align: start;
            background-color: var(--white);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .room-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .room-image {
            height: 250px;
            overflow: hidden;
        }

        .room-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .room-card:hover .room-image img {
            transform: scale(1.1);
        }

        .room-details {
            padding: 2rem;
        }

        .room-type {
            color: var(--gold);
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 0.5rem;
        }

        .room-title {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .room-info {
            font-size: 0.9rem;
            color: var(--medium-gray);
            margin-bottom: 1.5rem;
        }

        .room-price {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid var(--light-gray);
        }

        .price {
            font-weight: 600;
            color: var(--navy);
        }

        .price span {
            font-size: 1.3rem;
        }

        .slider-controls {
            position: absolute;
            top: -70px;
            right: 0;
            display: flex;
            gap: 0.5rem;
        }

        .slider-btn {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--white);
            border: 1px solid var(--light-gray);
            color: var(--navy);
            cursor: pointer;
            transition: var(--transition);
        }

        .slider-btn:hover {
            background-color: var(--navy);
            color: var(--white);
            border-color: var(--navy);
        }

        /* Contact Section */
        .contact {
            background-color: var(--white);
        }

        .contact-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 1.5rem;
        }

        .contact-icon {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(10, 20, 53, 0.05);
            color: var(--navy);
            border-radius: 50%;
            font-size: 1.3rem;
        }

        .contact-text h4 {
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }

        .contact-text p {
            font-size: 0.9rem;
            color: var(--medium-gray);
            margin-bottom: 0;
        }

        .contact-map {
            height: 240px;
            margin-top: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .contact-map iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .contact-form {
            background-color: var(--off-white);
            padding: 3rem;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.05);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            color: var(--dark-gray);
        }

        .form-control {
            width: 100%;
            padding: 1rem;
            border: 1px solid var(--light-gray);
            background-color: var(--white);
            font-family: 'Montserrat', sans-serif;
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }

        /* Footer */
        footer {
            background-color: var(--navy);
            color: var(--white);
            padding: 4rem 0 2rem;
        }

        .footer-container {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 4rem;
        }

        .footer-logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            display: flex;
        }

        .footer-logo span {
            color: var(--gold);
        }

        .footer-about {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 2rem;
        }

        .social-links {
            display: flex;
            gap: 1rem;
        }

        .social-link {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--white);
            border-radius: 50%;
            transition: var(--transition);
        }

        .social-link:hover {
            background-color: var(--gold);
            color: var(--navy);
            transform: translateY(-5px);
        }

        .footer-heading {
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
            color: var(--white);
        }

        .footer-links {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .footer-links a:hover {
            color: var(--gold);
            transform: translateX(5px);
        }

        .footer-bottom {
            margin-top: 4rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.5);
        }

        /* Responsive Styles */
        @media (max-width: 1200px) {
            .footer-container {
                grid-template-columns: 1fr 1fr;
                gap: 3rem;
            }
        }

        @media (max-width: 992px) {
            html {
                font-size: 15px;
            }
            
            .nav-links {
                gap: 1.5rem;
            }
            
            .about-container {
                grid-template-columns: 1fr;
                gap: 3rem;
            }
            
            .about-content {
                padding-right: 0;
            }
            
            .contact-container {
                grid-template-columns: 1fr;
                gap: 3rem;
            }
        }

        @media (max-width: 768px) {
            .mobile-toggle {
                display: flex;
            }
            
            .nav-links, .auth-buttons {
                display: none;
            }
            
            .nav-links.active {
                display: flex;
                flex-direction: column;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background-color: var(--navy);
                padding: 2rem;
                gap: 1.5rem;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            }
            
            .auth-buttons.active {
                display: flex;
                flex-direction: column;
                width: 100%;
                margin-left: 0;
                margin-top: 1.5rem;
            }
            
            .hero h1 {
                font-size: 3rem;
            }
            
            .about-features {
                grid-template-columns: 1fr;
            }
            
            .footer-container {
                grid-template-columns: 1fr;
                gap: 2.5rem;
            }
        }

        @media (max-width: 576px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero p {
                font-size: 1rem;
            }
            
            section {
                padding: 4rem 0;
            }
            
            .section-heading h2 {
                font-size: 2rem;
            }
            
            .room-card {
                flex: 0 0 290px;
            }
            
            .contact-form {
                padding: 2rem;
            }
        }

        /* Animations and Effects */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
            100% {
                transform: translateY(0px);
            }
        }

        .float {
            animation: float 4s ease-in-out infinite;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container navbar">
            <div class="logo">
                <a href="#"><span>MEC</span>MEC BH</a>
            </div>
            <div class="mobile-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <nav class="nav-links">
                <a href="#" class="active">Home</a>
                <a href="#about">About Us</a>
                <a href="#rooms">Rooms & Rates</a>
                <a href="#contact">Contact</a>
            </nav>
            <div class="auth-buttons">
                <a href="#" class="btn btn-secondary">Sign In</a>
                <a href="#" class="btn btn-primary">Register</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <img src="data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='1920' height='1080' viewBox='0 0 1920 1080'%3E%3Crect fill='%230A1435' width='1920' height='1080'/%3E%3Cpath fill='%23D4AF37' fill-opacity='0.2' d='M0,72L60,66.3C120,61,240,48,360,80C480,112,600,187,720,208C840,229,960,197,1080,192C1200,187,1320,208,1380,218.7L1440,229L1440,1080L1380,1080C1320,1080,1200,1080,1080,1080C960,1080,840,1080,720,1080C600,1080,480,1080,360,1080C240,1080,120,1080,60,1080L0,1080Z'/%3E%3C/svg%3E" class="hero-bg" alt="MECMEC BH Background">
        <div class="hero-content">
            <h1>Comfortable Living, <br>Professionally Managed</h1>
            <p>Experience modern boarding house living redefined with premium amenities, community spaces, and exceptional service.</p>
            <a href="#rooms" class="btn btn-primary">View Rooms</a>
        </div>
        <div class="scroll-arrow">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7 10L12 15L17 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="container about-container">
            <div class="about-content fade-in">
                <div class="section-heading">
                    <p>Who We Are</p>
                    <h2>Modern Living for Professionals & Students</h2>
                </div>
                <p>MECMEC BH redefines the boarding house experience with a perfect blend of comfort, community, and convenience. We've created living spaces that cater to the modern resident, offering quality accommodations with professional management.</p>
                <p>Our mission is to provide a home away from home where residents can thrive personally and professionally in a well-maintained, secure environment.</p>
                <div class="about-features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2 L16 6 10 12 16 18 12 22 2 12Z"></path>
                            </svg>
                        </div>
                        <div class="feature-text">
                            <h4>Professional Management</h4>
                            <p>Dedicated staff ensuring exceptional service and maintenance.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M12 8v4l2 2"></path>
                            </svg>
                        </div>
                        <div class="feature-text">
                            <h4>24/7 Security</h4>
                            <p>Advanced security systems and protocols for peace of mind.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 8h1a4 4 0 010 8h-1"></path>
                                <path d="M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z"></path>
                                <line x1="6" y1="1" x2="6" y2="4"></line>
                                <line x1="10" y1="1" x2="10" y2="4"></line>
                                <line x1="14" y1="1" x2="14" y2="4"></line>
                            </svg>
                        </div>
                        <div class="feature-text">
                            <h4>Premium Amenities</h4>
                            <p>Modern facilities designed for comfort and convenience.</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 00-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 010 7.75"></path>
                            </svg>
                        </div>
                        <div class="feature-text">
                            <h4>Community Focus</h4>
                            <p>Cultivating a friendly, supportive resident community.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="about-image fade-in">
                <img src="data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='600' height='500' viewBox='0 0 600 500'%3E%3Crect fill='%23D4AF37' width='600' height='500'/%3E%3Cpath fill='%230A1435' fill-opacity='0.8' d='M0 0h600v500H0z'/%3E%3Ccircle cx='300' cy='250' r='100' fill='%23FFFFFF' fill-opacity='0.2'/%3E%3Crect x='150' y='100' width='300' height='300' stroke='%23FFFFFF' stroke-width='4' fill='none'/%3E%3C/svg%3E" alt="MECMEC BH Interior">
            </div>
        </div>
    </section>

    <!-- Rooms Section -->
    <section class="rooms" id="rooms">
        <div class="container">
            <div class="section-heading fade-in">
                <p>Our Accommodations</p>
                <h2>Rooms & Rates</h2>
            </div>
            <div class="room-slider-container fade-in">
                <div class="slider-controls">
                    <button class="slider-btn prev">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 18l-6-6 6-6"/>
                        </svg>
                    </button>
                    <button class="slider-btn next">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 18l6-6-6-6"/>
                        </svg>
                    </button>
                </div>
                <div class="room-slider">
                    <div class="room-card">
                        <div class="room-image">
                            <img src="data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='250' viewBox='0 0 400 250'%3E%3Crect fill='%230A1435' width='400' height='250'/%3E%3Crect x='50' y='50' width='300' height='150' fill='%23D4AF37' fill-opacity='0.3'/%3E%3Crect x='100' y='75' width='200' height='100' stroke='%23FFFFFF' stroke-width='2' fill='none'/%3E%3C/svg%3E" alt="Standard Room">
                        </div>
                        <div class="room-details">
                            <div class="room-type">Standard</div>
                            <h3 class="room-title">Single Occupancy Room</h3>
                            <p class="room-info">Comfortable space with essential amenities and shared bathroom facilities. Perfect for students and young professionals.</p>
                            <div class="room-price">
                                <div class="price">From <span>$399</span>/month</div>
                                <a href="#" class="btn btn-secondary">Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="room-card">
                        <div class="room-image">
                            <img src="data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='250' viewBox='0 0 400 250'%3E%3Crect fill='%230A1435' width='400' height='250'/%3E%3Ccircle cx='200' cy='125' r='75' fill='%23D4AF37' fill-opacity='0.3'/%3E%3Crect x='75' y='75' width='250' height='100' stroke='%23FFFFFF' stroke-width='2' fill='none'/%3E%3C/svg%3E" alt="Deluxe Room">
                        </div>
                        <div class="room-details">
                            <div class="room-type">Deluxe</div>
                            <h3 class="room-title">Private En-suite Room</h3>
                            <p class="room-info">Spacious accommodation with private bathroom, premium furnishings, and added storage space.</p>
                            <div class="room-price">
                                <div class="price">From <span>$599</span>/month</div>
                                <a href="#" class="btn btn-secondary">Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="room-card">
                        <div class="room-image">
                            <img src="data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='250' viewBox='0 0 400 250'%3E%3Crect fill='%230A1435' width='400' height='250'/%3E%3Cpolygon points='200,50 100,200 300,200' fill='%23D4AF37' fill-opacity='0.3'/%3E%3Crect x='100' y='75' width='200' height='125' stroke='%23FFFFFF' stroke-width='2' fill='none'/%3E%3C/svg%3E" alt="Premium Suite">
                        </div>
                        <div class="room-details">
                            <div class="room-type">Premium</div>
                            <h3 class="room-title">Studio Apartment</h3>
                            <p class="room-info">Exclusive studio with private kitchenette, en-suite bathroom, and premium furnishings for utmost comfort.</p>
                            <div class="room-price">
                                <div class="price">From <span>$799</span>/month</div>
                                <a href="#" class="btn btn-secondary">Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="room-card">
                        <div class="room-image">
                            <img src="data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='250' viewBox='0 0 400 250'%3E%3Crect fill='%230A1435' width='400' height='250'/%3E%3Cpath d='M100,50 L300,50 L300,200 L100,200 Z' fill='%23D4AF37' fill-opacity='0.3'/%3E%3Crect x='125' y='75' width='150' height='100' stroke='%23FFFFFF' stroke-width='2' fill='none'/%3E%3C/svg%3E" alt="Shared Room">
                        </div>
                        <div class="room-details">
                            <div class="room-type">Economy</div>
                            <h3 class="room-title">Shared Double Room</h3>
                            <p class="room-info">Budget-friendly option with twin beds, shared amenities, and a collaborative environment for students.</p>
                            <div class="room-price">
                                <div class="price">From <span>$299</span>/month</div>
                                <a href="#" class="btn btn-secondary">Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <div class="container contact-container">
            <div class="contact-info fade-in">
                <div class="section-heading">
                    <p>Get In Touch</p>
                    <h2>Contact Us</h2>
                </div>
                <p>We're here to answer any questions you may have about our accommodations, amenities, or availability. Reach out to us through any of the following channels.</p>
                <div class="contact-item">
                    <div class="contact-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"></path>
                        </svg>
                    </div>
                    <div class="contact-text">
                        <h4>Phone</h4>
                        <p>+1 (123) 456-7890</p>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                    </div>
                    <div class="contact-text">
                        <h4>Email</h4>
                        <p>info@mecmecbh.com</p>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                    </div>
                    <div class="contact-text">
                        <h4>Address</h4>
                        <p>123 Boarding Lane, Metropolitan City, MC 10101</p>
                    </div>
                </div>
                <div class="contact-map">
                    <div style="background-color: #e9eaee; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#0A1435" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="contact-form fade-in">
                <h3>Send a Message</h3>
                <form>
                    <div class="form-group">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" id="subject" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="message" class="form-label">Message</label>
                        <textarea id="message" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container footer-container">
            <div class="footer-col">
                <div class="footer-logo"><span>MEC</span>MEC BH</div>
                <p class="footer-about">MECMEC BH provides modern, comfortable, and professionally managed living spaces for students and young professionals in a community-focused environment.</p>
                <div class="social-links">
                    <a href="#" class="social-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path>
                        </svg>
                    </a>
                    <a href="#" class="social-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"></path>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                        </svg>
                    </a>
                    <a href="#" class="social-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"></path>
                        </svg>
                    </a>
                    <a href="#" class="social-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6z"></path>
                            <rect x="2" y="9" width="4" height="12"></rect>
                            <circle cx="4" cy="4" r="2"></circle>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="footer-col">
                <h4 class="footer-heading">Quick Links</h4>
                <div class="footer-links">
                    <a href="#">Home</a>
                    <a href="#about">About Us</a>
                    <a href="#rooms">Rooms & Rates</a>
                    <a href="#contact">Contact</a>
                </div>
            </div>
            <div class="footer-col">
                <h4 class="footer-heading">Resources</h4>
                <div class="footer-links">
                    <a href="#">FAQ</a>
                    <a href="#">Resident Portal</a>
                    <a href="#">House Rules</a>
                    <a href="#">Privacy Policy</a>
                </div>
            </div>
            <div class="footer-col">
                <h4 class="footer-heading">Get In Touch</h4>
                <div class="footer-links">
                    <a href="tel:+11234567890">+1 (123) 456-7890</a>
                    <a href="mailto:info@mecmecbh.com">info@mecmecbh.com</a>
                    <a href="#">123 Boarding Lane</a>
                    <a href="#">Metropolitan City, MC 10101</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom container">
            <p>&copy; 2025 MECMEC BH. All rights reserved. Designed with excellence.</p>
        </div>
    </footer>

    <script>
        // Header scroll effect
        const header = document.querySelector('header');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Mobile navigation toggle
        const mobileToggle = document.querySelector('.mobile-toggle');
        const navLinks = document.querySelector('.nav-links');
        const authButtons = document.querySelector('.auth-buttons');
        
        mobileToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            authButtons.classList.toggle('active');
            mobileToggle.classList.toggle('active');
        });

        // Scroll to section
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
                
                // Close mobile menu if open
                if (navLinks.classList.contains('active')) {
                    navLinks.classList.remove('active');
                    authButtons.classList.remove('active');
                    mobileToggle.classList.remove('active');
                }
            });
        });

        // Scroll arrow
        document.querySelector('.scroll-arrow').addEventListener('click', () => {
            window.scrollTo({
                top: document.querySelector('#about').offsetTop - 80,
                behavior: 'smooth'
            });
        });

        // Room slider
        const slider = document.querySelector('.room-slider');
        const prevBtn = document.querySelector('.slider-btn.prev');
        const nextBtn = document.querySelector('.slider-btn.next');
        
        prevBtn.addEventListener('click', () => {
            slider.scrollBy({
                left: -370,
                behavior: 'smooth'
            });
        });
        
        nextBtn.addEventListener('click', () => {
            slider.scrollBy({
                left: 370,
                behavior: 'smooth'
            });
        });

        // Fade-in animations
        const fadeElements = document.querySelectorAll('.fade-in');
        
        const fadeCallback = (entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        };
        
        const fadeObserver = new IntersectionObserver(fadeCallback, {
            threshold: 0.1
        });
        
        fadeElements.forEach(element => {
            fadeObserver.observe(element);
        });

        // Form submission (prevent default)
        const form = document.querySelector('form');
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Your message has been sent successfully!');
            form.reset();
        });
    </script>
</body>
</html>