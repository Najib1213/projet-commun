<?php
require_once '../Modèles/session.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TheaterSound Monitor - Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
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
    <a href="Accueil.php" class="nav-link text-indigo-400 hover:text-indigo-300 transition-colors">Accueil</a>

    <?php if (utilisateurConnecte()): ?>
        <a href="tableau.php" class="nav-link hover:text-indigo-400 transition-colors">Tableau de bord</a>
        <a href="capteur.php" class="nav-link hover:text-indigo-400 transition-colors">Capteurs</a>
        <a href="profil.php" class="nav-link hover:text-indigo-400 transition-colors">Profil</a>
        <a href="deconnexion.php" class="nav-link text-red-400 hover:text-red-300 transition-colors">
            Déconnexion (<?= htmlspecialchars(getPrenomConnecte()) ?>)
        </a>
    <?php else: ?>
        <a href="Connexion.php" class="nav-link hover:text-indigo-400 transition-colors">Connexion</a>
    <?php endif; ?>
</div>

                <button class="md:hidden" id="mobile-menu-btn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Page d'accueil -->
    <section class="min-h-screen gradient-bg flex items-center">
        <div class="container mx-auto px-6 py-20">
            <div class="text-center fade-in-up">
                <div class="mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-white bg-opacity-20 rounded-full mb-6">
                        <i class="fas fa-microphone-alt text-3xl text-white"></i>
                    </div>
                </div>
                <h1 class="text-5xl md:text-7xl font-bold mb-6">
                    TheaterSound
                    <span class="block text-3xl md:text-4xl font-light text-indigo-200">Monitor</span>
                </h1>
                <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto text-gray-200">
                    Système intelligent de monitoring sonore pour théâtres. 
                    Surveillez les niveaux de décibels en temps réel et optimisez l'expérience acoustique.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="tableau.html" class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-all transform hover:scale-105 inline-block">
                        <i class="fas fa-chart-line mr-2"></i>
                        Tableau de bord
                    </a>
                    <a href="capteur.html" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition-all inline-block">
                        <i class="fas fa-cogs mr-2"></i>
                        Gérer les capteurs
                    </a>
                </div>
            </div>
            
            <!-- Fonctionnalités -->
            <div class="grid md:grid-cols-3 gap-8 mt-20">
                <div class="text-center glass-effect p-6 rounded-xl">
                    <div class="w-16 h-16 bg-indigo-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-volume-up text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Monitoring en Temps Réel</h3>
                    <p class="text-gray-300">Surveillance continue des niveaux sonores avec alertes automatiques</p>
                </div>
                <div class="text-center glass-effect p-6 rounded-xl">
                    <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-bar text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Analyse Avancée</h3>
                    <p class="text-gray-300">Graphiques détaillés et historique des données acoustiques</p>
                </div>
                <div class="text-center glass-effect p-6 rounded-xl">
                    <div class="w-16 h-16 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-network-wired text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Gestion Multi-Capteurs</h3>
                    <p class="text-gray-300">Contrôle centralisé de tous vos capteurs et actionneurs</p>
                </div>
            </div>
        </div>
    </section>

    <script src="../js/Accueil.js"></script>
</body>
</html>