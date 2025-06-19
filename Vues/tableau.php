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
    <title>Luminosité - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../css/Accueil.css">
</head>

<body class="bg-gray-900 text-white">
    <nav class="fixed top-0 w-full z-50 glass-effect">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="sound-wave"></div>
                    <div class="sound-wave"></div>
                    <div class="sound-wave"></div>
                    <div class="sound-wave"></div>
                    <div class="sound-wave"></div>
                    <h1 class="text-xl font-bold ml-3">TheaterSound</h1>
                </div>
                <div class="hidden md:flex space-x-6">
                    <a href="Accueil.php" class="nav-link hover:text-indigo-400">Accueil</a>
                    <a href="tableau.php" class="nav-link hover:text-indigo-400">Tableau de bord</a>
                    <a href="capteur.php" class="nav-link hover:text-indigo-400">Capteurs</a>
                    <?php if (utilisateurConnecte()): ?>
                        <a href="deconnexion.php" class="nav-link text-red-400">Déconnexion
                            (<?= htmlspecialchars(getPrenomConnecte()) ?>)</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <section class="min-h-screen bg-gray-800 pt-20">
        <div class="container mx-auto px-6 py-20">
            <div class="fade-in-up">
                <div class="inline-flex mt-2 mb-6 rounded-xl overflow-hidden bg-gray-700 text-white font-semibold">
                    <a href="tableau.php?sensor=son" class="px-6 py-3 hover:bg-gray-600">Son</a>
                    <a href="tableau.php?sensor=luminosite" class="px-6 py-3 hover:bg-gray-600">Luminosité</a>
                    <a href="tableau.php?sensor=temperature" class="px-6 py-3 hover:bg-gray-600">Température</a>
                    <a href="tableau.php?sensor=humidite" class="px-6 py-3 hover:bg-gray-600">Humidité</a>
                    <a href="tableau.php?sensor=gaz" class="px-6 py-3 hover:bg-gray-600">Gaz</a>

                </div>

                <h2 class="text-4xl font-bold mb-8">Tableau de bord</h2>

                <div class="grid md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-gray-700 p-6 rounded-xl">
                        <div class="flex justify-between mb-4">
                            <h3 class="text-lg font-semibold">Niveau Actuel</h3>
                            <i id="icon-niveau" class="fas text-yellow-400"></i>
                        </div>
                        <div class="text-3xl font-bold text-yellow-400" id="current-level">-- %</div>
                        <div class="text-sm text-gray-400 mt-2" id="current-time">--</div>
                    </div>
                    <div class="bg-gray-700 p-6 rounded-xl">
                        <div class="flex justify-between mb-4">
                            <h3 class="text-lg font-semibold">Niveau Maximum</h3>
                            <i class="fas fa-chart-line text-red-400"></i>
                        </div>
                        <div class="text-3xl font-bold text-red-400" id="max-level">-- %</div>
                        <div class="text-sm text-gray-400 mt-2" id="max-time">--</div>
                    </div>
                    <div class="bg-gray-700 p-6 rounded-xl">
                        <div class="flex justify-between mb-4">
                            <h3 class="text-lg font-semibold">Moyenne 24h</h3>
                            <i class="fas fa-calculator text-green-400"></i>
                        </div>
                        <div class="text-3xl font-bold text-green-400" id="avg-level">-- %</div>
                        <div class="text-sm text-gray-400 mt-2">Analyse</div>
                    </div>
                </div>

                <div class="grid lg:grid-cols-2 gap-8 mb-8">
                    <div class="bg-gray-700 p-6 rounded-xl">
                        <h3 class="text-xl font-semibold mb-4">Évolution de la luminosité</h3>
                        <canvas id="soundChart" width="400" height="200"></canvas>
                    </div>
                    <div class="bg-gray-700 p-6 rounded-xl">
                        <h3 class="text-xl font-semibold mb-4">Distribution par zones</h3>
                        <canvas id="zoneChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="../JS/dashboard.js"></script>

</body>

</html>