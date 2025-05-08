<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MECMEC Boarding House</title>
  <meta name="description" content="MECMEC Boarding House - Modern and comfortable accommodation for students and professionals.">
  <!-- Tailwind CSS CDN -->
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- AOS Animation Library -->
  <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
  <!-- Swiper JS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
  <!-- Custom Styles -->
<link rel="stylesheet" href="style.css">

<script  src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<!-- <link href="src/output.css" rel="stylesheet"> -->
<script src="https://cdn.tailwindcss.com"></script>

  <!-- Tailwind Config -->
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            primary: {
              DEFAULT: '#1e40af',
              dark: '#1e3a8a',
              light: '#3b82f6',
            },
            accent: {
              DEFAULT: '#f97316',
              dark: '#ea580c',
              light: '#fb923c',
            },
          },
          fontFamily: {
            sans: ['Inter', 'sans-serif'],
            heading: ['Poppins', 'sans-serif'],
          },
          boxShadow: {
            'custom': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
          }
        },
      },
    }
  </script>