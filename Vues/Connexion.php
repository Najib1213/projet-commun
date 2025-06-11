<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TheaterSound Monitor - Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../Css/Accueil.css">
</head>
<body class="bg-gray-900 text-white">
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
                <a href="Accueil.html" class="nav-link hover:text-indigo-400 transition-colors">Accueil</a>
                <a href="tableau.html" class="nav-link hover:text-indigo-400 transition-colors">Tableau de bord</a>
                <a href="capteur.html" class="nav-link hover:text-indigo-400 transition-colors">Capteurs</a>
                <a href="Connexion.php" class="nav-link text-indigo-400 hover:text-indigo-300 transition-colors">Connexion</a>
            </div>
            <button class="md:hidden" id="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
</nav>

<!-- Page d'authentification -->
<section class="min-h-screen bg-gray-800 flex items-center">
    <div class="container mx-auto px-6 py-20">
        <div class="max-w-md mx-auto">
            <div class="bg-gray-700 rounded-xl p-8 shadow-2xl">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold mb-2">Connexion</h2>
                    <p class="text-gray-400">Accédez à votre tableau de bord</p>
                </div>

                <div class="mb-6">
                    <div class="flex border-b border-gray-600">
                        <button id="login-tab" class="flex-1 py-2 text-center font-semibold border-b-2 border-indigo-500 text-indigo-400" disabled>Connexion</button>
                        <a href="Inscription.php" id="register-tab" class="flex-1 py-2 text-center font-semibold text-gray-400 hover:text-indigo-400">Inscription</a>
                    </div>
                </div>

                <?php if (isset($_GET['error'])): ?>
                <div class="bg-red-100 text-red-700 p-2 rounded mb-4 text-center"><?= htmlspecialchars($_GET['error']) ?></div>
                <?php endif; ?>

                <!-- Formulaire de connexion -->
                <form id="login-form" class="space-y-4" method="post" action="../Controleurs/login.php">
                    <div>
                        <label class="block text-sm font-medium mb-2">Email</label>
                        <input type="email" name="email" required class="w-full px-4 py-3 bg-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none" placeholder="prenom.nom@isep.fr">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Mot de passe</label>
                        <input type="password" name="password" required class="w-full px-4 py-3 bg-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none" placeholder="••••••••">
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 py-3 rounded-lg font-semibold transition-colors">
                        Se connecter
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-gray-400">Pas encore de compte ? <a href="Inscription.php" class="text-indigo-400 hover:text-indigo-300">S'inscrire</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="../Js/Accueil.js"></script>

</body>
</html>
