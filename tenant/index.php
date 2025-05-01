<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Boarding House Management</title>
  
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="includes/styles.css" />
  
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
  </style>
</head>
<body class="bg-gray-50 text-gray-800">
  <div class="flex h-screen overflow-hidden">
    
    <?php include 'includes/sidebar.php'; ?>

    <div class="flex-1 flex flex-col overflow-hidden">
      <?php include 'includes/header.php'; ?>

      <main id="main-content" class="flex-1 overflow-y-auto p-4 bg-gray-200">

        <?php
          $page = $_GET['page'] ?? 'dashboard';
          $file = "pages/$page.php";
          if (file_exists($file)) {
              include $file;
          } else {
              echo "<h1>404 - Page not found</h1>";
          }
        ?>
      </main>
    </div>
  </div>

  <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden md:hidden"></div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="includes/dashboard.js"></script>
  <script>
    document.querySelectorAll('.nav-link').forEach(link => {
  link.addEventListener('click', function (e) {
    e.preventDefault();
    const url = this.getAttribute('href');

    fetch(url)
      .then(res => res.text())
      .then(html => {
        document.getElementById('main-content').innerHTML = html;
        history.pushState(null, '', url);

        // Optional: update active class
        document.querySelectorAll('.nav-link').forEach(item => item.classList.remove('bg-indigo-800'));
        this.classList.add('bg-indigo-800');
      });
  });
});

  </script>
</body>
</html>
