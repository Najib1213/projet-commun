<?php
require_once '../Modèles/session.php';

if (!utilisateurConnecte()) {
    header('Location: Connexion.php');
    exit;
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TheaterSound Monitor - Tableau de bord</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../css/Accueil.css">
</head>
<body class="bg-gray-900 text-white">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 glass-effect">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="flex items-center">
                        <div class="sound-wave"></div>
                        <div class="sound-wave"></div>
                        <div class="sound-wave"></div>
                        <div class="sound-wave"></div>
                        <div class="sound-wave"></div>
                    </div>
                    <h1 class="text-xl font-bold ml-3">TheaterSound</h1>
                </div>
<div class="hidden md:flex space-x-6">
    <a href="Accueil.php" class="nav-link hover:text-indigo-400 transition-colors">Accueil</a>
    <a href="tableau.php" class="nav-link text-indigo-400 hover:text-indigo-300 transition-colors">Tableau de bord</a>
    <a href="capteur.php" class="nav-link hover:text-indigo-400 transition-colors">Capteurs</a>

    <?php if (utilisateurConnecte()): ?>
        <a href="deconnexion.php" class="nav-link text-red-400 hover:text-red-300 transition-colors">
            Déconnexion (<?= htmlspecialchars(getPrenomConnecte()) ?>)
        </a>
    <?php else: ?>
        <a href="Connexion.php" class="nav-link text-indigo-400 hover:text-indigo-300 transition-colors">
            Connexion
        </a>
    <?php endif; ?>
</div>

                <button class="md:hidden" id="mobile-menu-btn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Tableau de bord -->
    <section class="min-h-screen bg-gray-800 pt-20">
        <div class="container mx-auto px-6 py-20">
            <div class="fade-in-up">
                <h2 class="text-4xl font-bold mb-8">Tableau de bord</h2>
                
                <!-- Métriques en temps réel -->
                <div class="grid md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-gray-700 p-6 rounded-xl">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold">Niveau Actuel</h3>
                            <i class="fas fa-volume-up text-indigo-400"></i>
                        </div>
                        <div class="text-3xl font-bold text-indigo-400" id="current-level">68 dB</div>
                        <div class="text-sm text-gray-400 mt-2">
                            <span class="text-green-400">↓ 2 dB</span> depuis 5 min
                        </div>
                    </div>
                    <div class="bg-gray-700 p-6 rounded-xl">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold">Niveau Maximum</h3>
                            <i class="fas fa-chart-line text-red-400"></i>
                        </div>
                        <div class="text-3xl font-bold text-red-400">89 dB</div>
                        <div class="text-sm text-gray-400 mt-2">À 14:32 aujourd'hui</div>
                    </div>
                    <div class="bg-gray-700 p-6 rounded-xl">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold">Moyenne 24h</h3>
                            <i class="fas fa-calculator text-green-400"></i>
                        </div>
                        <div class="text-3xl font-bold text-green-400">64 dB</div>
                        <div class="text-sm text-gray-400 mt-2">
                            <span class="text-green-400">Optimal</span>
                        </div>
                    </div>
                </div>

                <!-- Graphiques -->
                <div class="grid lg:grid-cols-2 gap-8 mb-8">
                    <div class="bg-gray-700 p-6 rounded-xl">
                        <h3 class="text-xl font-semibold mb-4">Évolution du niveau sonore</h3>
                        <canvas id="soundChart" width="400" height="200"></canvas>
                    </div>
                    <div class="bg-gray-700 p-6 rounded-xl">
                        <h3 class="text-xl font-semibold mb-4">Distribution par zones</h3>
                        <canvas id="zoneChart" width="400" height="200"></canvas>
                    </div>
                </div>

                <!-- Alertes récentes -->
                <div class="bg-gray-700 rounded-xl p-6">
                    <h3 class="text-xl font-semibold mb-4">Alertes récentes</h3>
                    <div class="space-y-4">
                        <div class="flex items-center p-4 bg-yellow-500 bg-opacity-20 border-l-4 border-yellow-500 rounded">
                            <i class="fas fa-exclamation-triangle text-yellow-500 mr-3"></i>
                            <div class="flex-1">
                                <p class="font-semibold">Niveau élevé détecté</p>
                                <p class="text-sm text-gray-400">Capteur Scène Principal - 85 dB à 15:23</p>
                            </div>
                            <span class="text-xs text-gray-400">Il y a 12 min</span>
                        </div>
                        <div class="flex items-center p-4 bg-red-500 bg-opacity-20 border-l-4 border-red-500 rounded">
                            <i class="fas fa-wifi text-red-500 mr-3"></i>
                            <div class="flex-1">
                                <p class="font-semibold">Capteur hors ligne</p>
                                <p class="text-sm text-gray-400">Capteur Coulisses - Perte de connexion</p>
                            </div>
                            <span class="text-xs text-gray-400">Il y a 2h</span>
                        </div>
                        <div class="flex items-center p-4 bg-green-500 bg-opacity-20 border-l-4 border-green-500 rounded">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <div class="flex-1">
                                <p class="font-semibold">Calibration terminée</p>
                                <p class="text-sm text-gray-400">Capteur Public Droite - Calibration automatique</p>
                            </div>
                            <span class="text-xs text-gray-400">Il y a 3h</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="../js/Accueil.js"></script>

<script>
let soundChart;
let zoneChart;

// Simulation de données en temps réel
function updateRealTimeData() {
    const currentLevel = Math.floor(Math.random() * 20) + 60; // 60-80 dB
    document.getElementById('current-level').textContent = currentLevel + ' dB';
}

// Graphique d'évolution du son
const ctx = document.getElementById('soundChart')?.getContext('2d');
if (ctx) {
    soundChart = new Chart(ctx, {
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
                    grid: {
                        color: '#374151'
                    },
                    ticks: {
                        color: '#9CA3AF'
                    }
                },
                x: {
                    grid: {
                        color: '#374151'
                    },
                    ticks: {
                        color: '#9CA3AF'
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: '#9CA3AF'
                    }
                }
            }
        }
    });
}

// Graphique de distribution par zones
const zoneCtx = document.getElementById('zoneChart')?.getContext('2d');
if (zoneCtx) {
    zoneChart = new Chart(zoneCtx, {
        type: 'doughnut',
        data: {
            labels: ['Scène', 'Public Gauche', 'Public Droite', 'Balcon', 'Coulisses'],
            datasets: [{
                data: [68, 65, 67, 60, 55],
                backgroundColor: [
                    '#4F46E5',
                    '#10B981',
                    '#F59E0B',
                    '#EF4444',
                    '#8B5CF6'
                ]
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

// Mise à jour dynamique des graphiques
function updateCharts() {
    if (soundChart) {
        const now = new Date();
        const timeLabel = now.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
        const newValue = Math.floor(Math.random() * 25) + 60;

        soundChart.data.labels.push(timeLabel);
        soundChart.data.datasets[0].data.push(newValue);

        if (soundChart.data.labels.length > 7) {
            soundChart.data.labels.shift();
            soundChart.data.datasets[0].data.shift();
        }

        soundChart.update('none');
    }

    if (zoneChart) {
        zoneChart.data.datasets[0].data = zoneChart.data.datasets[0].data.map(() =>
            Math.floor(Math.random() * 25) + 55
        );
        zoneChart.update('none');
    }
}

setInterval(updateRealTimeData, 5000);
setInterval(updateCharts, 5000);
</script>

</body>
</html>