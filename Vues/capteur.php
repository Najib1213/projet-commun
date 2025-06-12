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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TheaterSound Monitor - Gestion des Capteurs</title>
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
          <div class="sound-wave"></div><div class="sound-wave"></div><div class="sound-wave"></div><div class="sound-wave"></div><div class="sound-wave"></div>
        </div>
        <h1 class="text-xl font-bold ml-3">TheaterSound</h1>
      </div>
<div class="hidden md:flex space-x-6">
    <a href="Accueil.php" class="nav-link hover:text-indigo-400 transition-colors">Accueil</a>

    <?php if (utilisateurConnecte()): ?>
        <a href="tableau.php" class="nav-link hover:text-indigo-400 transition-colors">Tableau de bord</a>
        <a href="capteur.php" class="nav-link text-indigo-400 hover:text-indigo-300 transition-colors">Capteurs</a>
        <a href="deconnexion.php" class="nav-link text-red-400 hover:text-red-300 transition-colors">
            Déconnexion (<?= htmlspecialchars($_SESSION['user']['prenom'] ?? 'Utilisateur') ?>)
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

<!-- Capteurs -->
<section class="min-h-screen bg-gray-800 pt-32">
  <div class="container mx-auto px-6">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-3xl font-bold">Capteurs installés</h2>
      <button id="add-sensor-btn" class="bg-indigo-600 hover:bg-indigo-700 px-6 py-3 rounded-lg font-semibold transition-colors">
        <i class="fas fa-plus mr-2"></i> Ajouter un capteur
      </button>
    </div>
    <div class="bg-gray-700 rounded-xl overflow-hidden">
      <table class="w-full">
        <thead class="bg-gray-600">
        <tr>
          <th class="px-6 py-3 text-left">Nom</th>
          <th class="px-6 py-3 text-left">Zone</th>
          <th class="px-6 py-3 text-left">État</th>
          <th class="px-6 py-3 text-left">Niveau actuel</th>
          <th class="px-6 py-3 text-left">Dernière mise à jour</th>
          <th class="px-6 py-3 text-left">Actions</th>
        </tr>
        </thead>
        <tbody id="sensors-table">

        <tr class="border-b border-gray-600">
          <td class="px-6 py-4">
            <div class="flex items-center">
              <i class="fas fa-microphone text-indigo-400 mr-3"></i>
              Capteur Scène Principal
            </div>
          </td>
          <td class="px-6 py-4">Scène centrale</td>
          <td class="px-6 py-4">
            <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs">En ligne</span>
          </td>
          <td class="px-6 py-4 font-semibold">72 dB</td>
          <td class="px-6 py-4 text-gray-400">Il y a 2 min</td>
          <td class="px-6 py-4 flex flex-wrap gap-2">
            <button class="edit-btn text-indigo-400 hover:text-indigo-300 mr-2" title="Éditer"><i class="fas fa-edit"></i></button>
            <button class="delete-btn text-red-400 hover:text-red-300 mr-2" title="Supprimer"><i class="fas fa-trash"></i></button>
            <a href="capteur_son.html" class="action-btn bg-indigo-600 hover:bg-indigo-700" title="Voir Son"><i class="fas fa-volume-up"></i></a>
            <a href="capteur_lumiere.html" class="action-btn bg-yellow-500 hover:bg-yellow-600" title="Voir Lumière"><i class="fas fa-lightbulb"></i></a>
            <a href="capteur_proximite.html" class="action-btn bg-green-600 hover:bg-green-700" title="Voir Proximité"><i class="fas fa-ruler-horizontal"></i></a>
            <a href="capteur_gaz.html" class="action-btn bg-red-600 hover:bg-red-700" title="Voir Gaz"><i class="fas fa-wind"></i></a>
            <a href="capteur_temperature.html" class="action-btn bg-blue-600 hover:bg-blue-700" title="Voir Temp./Humidité"><i class="fas fa-thermometer-half"></i></a>
          </td>
        </tr>

        <tr class="border-b border-gray-600">
          <td class="px-6 py-4">
            <div class="flex items-center">
              <i class="fas fa-microphone text-indigo-400 mr-3"></i>
              Capteur Public Gauche
            </div>
          </td>
          <td class="px-6 py-4">Côté jardin</td>
          <td class="px-6 py-4">
            <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs">En ligne</span>
          </td>
          <td class="px-6 py-4 font-semibold">65 dB</td>
          <td class="px-6 py-4 text-gray-400">Il y a 1 min</td>
          <td class="px-6 py-4 flex flex-wrap gap-2">
            <button class="edit-btn text-indigo-400 hover:text-indigo-300 mr-2" title="Éditer"><i class="fas fa-edit"></i></button>
            <button class="delete-btn text-red-400 hover:text-red-300 mr-2" title="Supprimer"><i class="fas fa-trash"></i></button>
            <a href="capteur_son.html" class="action-btn bg-indigo-600 hover:bg-indigo-700" title="Voir Son"><i class="fas fa-volume-up"></i></a>
            <a href="capteur_lumiere.html" class="action-btn bg-yellow-500 hover:bg-yellow-600" title="Voir Lumière"><i class="fas fa-lightbulb"></i></a>
            <a href="capteur_proximite.html" class="action-btn bg-green-600 hover:bg-green-700" title="Voir Proximité"><i class="fas fa-ruler-horizontal"></i></a>
            <a href="capteur_gaz.html" class="action-btn bg-red-600 hover:bg-red-700" title="Voir Gaz"><i class="fas fa-wind"></i></a>
            <a href="capteur_temperature.html" class="action-btn bg-blue-600 hover:bg-blue-700" title="Voir Temp./Humidité"><i class="fas fa-thermometer-half"></i></a>
          </td>
        </tr>

        <tr class="border-b border-gray-600">
          <td class="px-6 py-4">
            <div class="flex items-center">
              <i class="fas fa-microphone text-red-400 mr-3"></i>
              Capteur Coulisses
            </div>
          </td>
          <td class="px-6 py-4">Coulisses</td>
          <td class="px-6 py-4">
            <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs">Hors ligne</span>
          </td>
          <td class="px-6 py-4 text-gray-400">-- dB</td>
          <td class="px-6 py-4 text-gray-400">Il y a 2h</td>
          <td class="px-6 py-4 flex flex-wrap gap-2">
            <button class="edit-btn text-indigo-400 hover:text-indigo-300 mr-2" title="Éditer"><i class="fas fa-edit"></i></button>
            <button class="delete-btn text-red-400 hover:text-red-300 mr-2" title="Supprimer"><i class="fas fa-trash"></i></button>
            <a href="capteur_son.html" class="action-btn bg-indigo-600 hover:bg-indigo-700" title="Voir Son"><i class="fas fa-volume-up"></i></a>
            <a href="capteur_lumiere.html" class="action-btn bg-yellow-500 hover:bg-yellow-600" title="Voir Lumière"><i class="fas fa-lightbulb"></i></a>
            <a href="capteur_proximite.html" class="action-btn bg-green-600 hover:bg-green-700" title="Voir Proximité"><i class="fas fa-ruler-horizontal"></i></a>
            <a href="capteur_gaz.html" class="action-btn bg-red-600 hover:bg-red-700" title="Voir Gaz"><i class="fas fa-wind"></i></a>
            <a href="capteur_temperature.html" class="action-btn bg-blue-600 hover:bg-blue-700" title="Voir Temp./Humidité"><i class="fas fa-thermometer-half"></i></a>
          </td>
        </tr>

        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- Modal -->
<div id="sensor-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
  <div class="bg-gray-700 rounded-xl p-8 max-w-md w-full mx-4">
    <div class="flex justify-between items-center mb-6">
      <h3 class="text-2xl font-bold">Ajouter un capteur</h3>
      <button id="close-modal" class="text-gray-400 hover:text-white">
        <i class="fas fa-times text-xl"></i>
      </button>
    </div>
    <form id="sensor-form" class="space-y-4">
      <input type="text" id="sensor-name" placeholder="Nom du capteur" class="w-full px-4 py-3 bg-gray-600 rounded-lg text-white"/>
      <input type="text" id="sensor-zone" placeholder="Zone" class="w-full px-4 py-3 bg-gray-600 rounded-lg text-white"/>
      <input type="text" id="sensor-state" placeholder="État (En ligne / Hors ligne)" class="w-full px-4 py-3 bg-gray-600 rounded-lg text-white"/>
      <input type="text" id="sensor-level" placeholder="Niveau (ex: 72 dB)" class="w-full px-4 py-3 bg-gray-600 rounded-lg text-white"/>
      <input type="text" id="sensor-time" placeholder="Dernière mise à jour (ex: Il y a 1 min)" class="w-full px-4 py-3 bg-gray-600 rounded-lg text-white"/>
      <div class="flex justify-end space-x-4">
        <button type="button" id="cancel-modal" class="bg-gray-500 hover:bg-gray-600 px-4 py-2 rounded-lg">Annuler</button>
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-lg">Ajouter</button>
      </div>
    </form>
  </div>
</div>

<script src="../js/capteur.js"></script>
<style>
  .action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.5rem;
    font-size: 1.25rem;
    transition: background .2s;
  }
</style>
</body>
</html>