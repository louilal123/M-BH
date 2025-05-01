
document.getElementById('profile-menu-btn').addEventListener('click', function () {
    const dropdown = document.getElementById('profile-dropdown');
    dropdown.classList.toggle('hidden');
});

document.addEventListener('click', function (event) {
    const btn = document.getElementById('profile-menu-btn');
    const dropdown = document.getElementById('profile-dropdown');
    if (!btn.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.classList.add('hidden');
    }
});
const collapseBtn = document.getElementById('collapse-btn');
const sidebar = document.getElementById('sidebar');
const sidebarHeaderText = document.querySelector('#sidebar .flex.items-center.space-x-2 span');

collapseBtn.classList.remove('md:hidden');

collapseBtn.addEventListener('click', function() {
sidebar.classList.toggle('w-64');
sidebar.classList.toggle('w-20');
sidebarHeaderText.classList.toggle('hidden');

const sidebarTexts = document.querySelectorAll('.sidebar-item span:not(#sidebar .flex.items-center.space-x-2 span)');
sidebarTexts.forEach(text => {
text.classList.toggle('hidden');
});

const icon = collapseBtn.querySelector('i');
icon.classList.toggle('fa-bars');
icon.classList.toggle('fa-chevron-right');
});

// Initialize Charts
document.addEventListener('DOMContentLoaded', function() {
  // Revenue Chart
  const revenueCtx = document.createElement('canvas');
  document.getElementById('revenue-chart').appendChild(revenueCtx);
  
  new Chart(revenueCtx, {
      type: 'line',
      data: {
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
          datasets: [{
              label: 'Revenue',
              data: [9500, 10200, 11000, 10700, 11500, 12200, 12800, 13100, 12700, 13500, 14100, 14800],
              borderColor: '#6366F1',
              backgroundColor: 'rgba(99, 102, 241, 0.1)',
              tension: 0.4,
              fill: true
          }]
      },
      options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
              legend: {
                  display: false
              }
          },
          scales: {
              y: {
                  beginAtZero: false,
                  grid: {
                      display: true,
                      color: 'rgba(0, 0, 0, 0.05)'
                  },
                  ticks: {
                      callback: function(value) {
                          return '$' + value;
                      }
                  }
              },
              x: {
                  grid: {
                      display: false
                  }
              }
          }
      }
  });

  // Occupancy Chart
  const occupancyCtx = document.createElement('canvas');
  document.getElementById('occupancy-chart').appendChild(occupancyCtx);
  
  new Chart(occupancyCtx, {
      type: 'doughnut',
      data: {
          labels: ['Occupied', 'Available', 'Maintenance'],
          datasets: [{
              data: [28, 2, 0],
              backgroundColor: ['#6366F1', '#D1D5DB', '#FCD34D'],
              borderWidth: 0
          }]
      },
      options: {
          responsive: true,
          maintainAspectRatio: false,
          cutout: '70%',
          plugins: {
              legend: {
                  position: 'bottom'
              }
          }
      }
  });
});