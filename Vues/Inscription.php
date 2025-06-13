<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TheaterSound Monitor - Inscription</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../Css/Accueil.css">
  <style>
    .criteria-list li.invalid { color: red; }
    .criteria-list li.valid { color: green; }
  </style>
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
          <a href="Accueil.php" class="nav-link hover:text-indigo-400 transition-colors">Accueil</a>
          <a href="Connexion.php" class="nav-link text-indigo-400 hover:text-indigo-300 transition-colors">Connexion</a>
        </div>
        <button class="md:hidden" id="mobile-menu-btn">
          <i class="fas fa-bars"></i>
        </button>
      </div>
    </div>
  </nav>

  <section class="min-h-screen bg-gray-800 flex items-center pt-20">
    <div class="container mx-auto px-6">
      <div class="max-w-md mx-auto">
        <div class="bg-gray-700 rounded-xl p-8 shadow-2xl">
          <h2 class="text-3xl font-bold mb-2 text-center">Inscription</h2>
          <p class="text-gray-400 text-center mb-6">Créez votre compte TheaterSound</p>

          <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4 text-center">
              <?= htmlspecialchars($_GET['error']) ?>
            </div>
          <?php endif; ?>

          <form id="register-form" method="post" action="../Controleurs/register.php" class="space-y-4">
            <input type="text" name="prenom" required placeholder="Prénom" class="w-full px-4 py-3 bg-gray-600 rounded-lg" />
            <input type="text" name="nom" required placeholder="Nom" class="w-full px-4 py-3 bg-gray-600 rounded-lg" />
            <input type="email" name="email" required placeholder="prenom.nom@isep.fr" class="w-full px-4 py-3 bg-gray-600 rounded-lg" />

            <input type="password" id="password" name="password" required placeholder="Mot de passe" class="w-full px-4 py-3 bg-gray-600 rounded-lg" />
<ul id="password-criteria" class="hidden text-sm space-y-1 ml-1">
  <li id="length" class="text-red-500 list-disc list-inside">8 caractères minimum</li>
  <li id="lowercase" class="text-red-500 list-disc list-inside">1 minuscule</li>
  <li id="uppercase" class="text-red-500 list-disc list-inside">1 majuscule</li>
  <li id="number" class="text-red-500 list-disc list-inside">1 chiffre</li>
  <li id="special" class="text-red-500 list-disc list-inside">1 caractère spécial</li>
</ul>


            <input type="password" name="confirm_password" required placeholder="Confirmer le mot de passe" class="w-full px-4 py-3 bg-gray-600 rounded-lg" />
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 py-3 rounded-lg font-semibold transition-colors">
              S'inscrire
            </button>
          </form>

          <p class="text-center text-gray-400 mt-4">
            Déjà un compte ?
            <a href="Connexion.php" class="text-indigo-400 hover:text-indigo-300">Se connecter</a>
          </p>
        </div>
      </div>
    </div>
  </section>

  <script src="../Js/Accueil.js"></script>
  <script src="../Js/mdp.js"></script>
</body>
</html>
