// Navigation
function showSection(sectionId) {
  document.querySelectorAll('section').forEach(section => {
    section.classList.add('hidden');
  });
  document.getElementById(sectionId)?.classList.remove('hidden');
  
document.querySelectorAll('.nav-link').forEach(link => {
  const href = link.getAttribute('href');
  if (href && href.startsWith('#')) {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      const sectionId = href.substring(1);
      showSection(sectionId);
    });
  }
});

  document.querySelector(`a[href="#${sectionId}"]`)?.classList.add('text-indigo-400');
}


document.querySelectorAll('.nav-link').forEach(link => {
  const href = link.getAttribute('href');
  if (href && href.startsWith('#')) {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      const sectionId = href.substring(1);
      showSection(sectionId);
    });
  }
});




// Tabs d'authentification
document.getElementById('login-tab')?.addEventListener('click', () => {
  document.getElementById('login-tab').classList.add('border-indigo-500', 'text-indigo-400');
  document.getElementById('login-tab').classList.remove('text-gray-400');
  document.getElementById('register-tab').classList.remove('border-indigo-500', 'text-indigo-400');
  document.getElementById('register-tab').classList.add('text-gray-400');
  document.getElementById('login-form').classList.remove('hidden');
  document.getElementById('register-form').classList.add('hidden');
});

document.getElementById('register-tab')?.addEventListener('click', () => {
  document.getElementById('register-tab').classList.add('border-indigo-500', 'text-indigo-400');
  document.getElementById('register-tab').classList.remove('text-gray-400');
  document.getElementById('login-tab').classList.remove('border-indigo-500', 'text-indigo-400');
  document.getElementById('login-tab').classList.add('text-gray-400');
  document.getElementById('register-form').classList.remove('hidden');
  document.getElementById('login-form').classList.add('hidden');
});

// Modal de capteur
document.getElementById('add-sensor-btn')?.addEventListener('click', () => {
  document.getElementById('sensor-modal')?.classList.remove('hidden');
});
document.getElementById('close-modal')?.addEventListener('click', () => {
  document.getElementById('sensor-modal')?.classList.add('hidden');
});
document.getElementById('cancel-modal')?.addEventListener('click', () => {
  document.getElementById('sensor-modal')?.classList.add('hidden');
});

// Simulation de données en temps réel
function updateRealTimeData() {
  const currentLevel = Math.floor(Math.random() * 20) + 60;
  const el = document.getElementById('current-level');
  if (el) el.textContent = currentLevel + ' dB';
}

// Graphique d'évolution du son
if (document.getElementById('soundChart')) {
  const ctx = document.getElementById('soundChart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00'],
      datasets: [{
        label: 'Niveau sonore (dB)',
        data: [62, 65, 70, 85, 78, 68, 72],
        borderColor: '#4F46E5',
        backgroundColor: 'rgba(79, 70, 229, 0.1)',
        tension: 0.4,
        fill: true
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: false,
          min: 50,
          max: 100,
          grid: { color: '#374151' },
          ticks: { color: '#9CA3AF' }
        },
        x: {
          grid: { color: '#374151' },
          ticks: { color: '#9CA3AF' }
        }
      },
      plugins: {
        legend: {
          labels: { color: '#9CA3AF' }
        }
      }
    }
  });
}

// Graphique de distribution par zones
if (document.getElementById('zoneChart')) {
  const zoneCtx = document.getElementById('zoneChart').getContext('2d');
  new Chart(zoneCtx, {
    type: 'doughnut',
    data: {
      labels: ['Scène', 'Public Gauche', 'Public Droite', 'Balcon', 'Coulisses'],
      datasets: [{
        data: [68, 65, 67, 60, 55],
        backgroundColor: ['#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6']
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            color: '#9CA3AF',
            padding: 20
          }
        }
      }
    }
  });
}

setInterval(updateRealTimeData, 5000);

// Gestion du formulaire de connexion
document.getElementById('login-form')?.addEventListener('submit', (e) => {
  e.preventDefault();
  alert('Connexion simulée réussie !');
  showSection('dashboard');
});

// Gestion du formulaire d'inscription
document.getElementById('register-form')?.addEventListener('submit', (e) => {
  e.preventDefault();
  alert('Inscription simulée réussie !');
  showSection('dashboard');
});

// Menu mobile (placeholder)
document.getElementById('mobile-menu-btn')?.addEventListener('click', () => {
  alert("Menu mobile à implémenter");
});

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
  if (document.getElementById('home')) {
    showSection('home');
  }
});




// Navigation
function showSection(sectionId) {
  document.querySelectorAll('section').forEach(section => {
    section.classList.add('hidden');
  });
  document.getElementById(sectionId)?.classList.remove('hidden');
  
document.querySelectorAll('.nav-link').forEach(link => {
  const href = link.getAttribute('href');
  if (href && href.startsWith('#')) {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      const sectionId = href.substring(1);
      showSection(sectionId);
    });
  }
});

  document.querySelector(`a[href="#${sectionId}"]`)?.classList.add('text-indigo-400');
}

// Navigation par liens

document.querySelectorAll('.nav-link').forEach(link => {
  const href = link.getAttribute('href');
  if (href && href.startsWith('#')) {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      const sectionId = href.substring(1);
      showSection(sectionId);
    });
  }
});


// Tabs d'authentification
document.getElementById('login-tab')?.addEventListener('click', () => {
  document.getElementById('login-tab').classList.add('border-indigo-500', 'text-indigo-400');
  document.getElementById('login-tab').classList.remove('text-gray-400');
  document.getElementById('register-tab').classList.remove('border-indigo-500', 'text-indigo-400');
  document.getElementById('register-tab').classList.add('text-gray-400');
  document.getElementById('login-form').classList.remove('hidden');
  document.getElementById('register-form').classList.add('hidden');
});

document.getElementById('register-tab')?.addEventListener('click', () => {
  document.getElementById('register-tab').classList.add('border-indigo-500', 'text-indigo-400');
  document.getElementById('register-tab').classList.remove('text-gray-400');
  document.getElementById('login-tab').classList.remove('border-indigo-500', 'text-indigo-400');
  document.getElementById('login-tab').classList.add('text-gray-400');
  document.getElementById('register-form').classList.remove('hidden');
  document.getElementById('login-form').classList.add('hidden');
});

// Modal de capteur
document.getElementById('add-sensor-btn')?.addEventListener('click', () => {
  document.getElementById('sensor-modal')?.classList.remove('hidden');
});
document.getElementById('close-modal')?.addEventListener('click', () => {
  document.getElementById('sensor-modal')?.classList.add('hidden');
});
document.getElementById('cancel-modal')?.addEventListener('click', () => {
  document.getElementById('sensor-modal')?.classList.add('hidden');
});

// Simulation de données en temps réel
function updateRealTimeData() {
  const currentLevel = Math.floor(Math.random() * 20) + 60;
  const el = document.getElementById('current-level');
  if (el) el.textContent = currentLevel + ' dB';
}

// Graphique d'évolution du son
if (document.getElementById('soundChart')) {
  const ctx = document.getElementById('soundChart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00'],
      datasets: [{
        label: 'Niveau sonore (dB)',
        data: [62, 65, 70, 85, 78, 68, 72],
        borderColor: '#4F46E5',
        backgroundColor: 'rgba(79, 70, 229, 0.1)',
        tension: 0.4,
        fill: true
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: false,
          min: 50,
          max: 100,
          grid: { color: '#374151' },
          ticks: { color: '#9CA3AF' }
        },
        x: {
          grid: { color: '#374151' },
          ticks: { color: '#9CA3AF' }
        }
      },
      plugins: {
        legend: {
          labels: { color: '#9CA3AF' }
        }
      }
    }
  });
}

// Graphique de distribution par zones
if (document.getElementById('zoneChart')) {
  const zoneCtx = document.getElementById('zoneChart').getContext('2d');
  new Chart(zoneCtx, {
    type: 'doughnut',
    data: {
      labels: ['Scène', 'Public Gauche', 'Public Droite', 'Balcon', 'Coulisses'],
      datasets: [{
        data: [68, 65, 67, 60, 55],
        backgroundColor: ['#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6']
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            color: '#9CA3AF',
            padding: 20
          }
        }
      }
    }
  });
}

setInterval(updateRealTimeData, 5000);

// Gestion du formulaire de connexion
document.getElementById('login-form')?.addEventListener('submit', (e) => {
  e.preventDefault();
  alert('Connexion simulée réussie !');
  showSection('dashboard');
});

// Gestion du formulaire d'inscription
document.getElementById('register-form')?.addEventListener('submit', (e) => {
  e.preventDefault();
  alert('Inscription simulée réussie !');
  showSection('dashboard');
});

// Menu mobile (placeholder)
document.getElementById('mobile-menu-btn')?.addEventListener('click', () => {
  alert("Menu mobile à implémenter");
});

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
  if (document.getElementById('home')) {
    showSection('home');
  }
});
