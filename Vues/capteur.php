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
  <style>
    body {
      background: #141725;
    }
    .glass-effect {
      background: rgba(36, 36, 43, 0.97);
      backdrop-filter: blur(4px);
      box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.13);
      border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    .table-main {
      background: #191c2d;
      border-radius: 1rem;
      box-shadow: 0 10px 40px 0 #0007;
      border: 1.5px solid #232346;
      position: relative;
      overflow: hidden;
    }
    .table-main::before {
      content: "";
      position: absolute;
      inset: 0;
      background: repeating-linear-gradient(
        135deg,
        rgba(99,102,241,0.03) 0px,
        rgba(99,102,241,0.03) 2px,
        transparent 3px,
        transparent 20px
      );
      z-index: 0;
      pointer-events: none;
    }
    .table-main > * {
      position: relative;
      z-index: 1;
    }
    .card-hover {
      transition: box-shadow .18s, transform .18s, background .18s, border-color .18s;
    }
    .card-hover:hover {
      box-shadow: 0 6px 24px 0 #6366f199;
      background: #232334;
      transform: scale(1.01);
      border-left: 6px solid #6366f1;
    }
    .sensor-link {
      transition: color .18s, text-shadow .18s;
      text-shadow: 0 2px 8px #2d3748, 0 0px 2px #6366f155;
    }
    .sensor-link:hover {
      color: #fff !important;
      text-shadow: 0 2px 18px #6366f1, 0 0px 8px #fff3;
    }
    .cell-highlight {
      background: #232346;
      border-radius: 7px;
      padding: 0.25rem 0.75rem;
      font-weight: 600;
      letter-spacing: 0.02em;
      color: #e0e7ff;
      box-shadow: 0 1px 2px #0002;
    }
    thead th {
      background: #232346 !important;
      border-bottom: 2px solid #6366f1 !important;
      color: #a5b4fc !important;
      letter-spacing: 0.09em !important;
    }
    tbody td {
      border-bottom: 1px solid #232346 !important;
    }
    /* Couleurs boutons comme avant */
    .btn-indigo {
      background: #4f46e5;
      color: #fff;
      border: none;
      transition: background 0.18s, box-shadow 0.18s;
      box-shadow: 0 2px 8px 0 #312e8133;
    }
    .btn-indigo:hover {
      background: #3730a3;
      box-shadow: 0 4px 12px 0 #312e8166;
    }
    .btn-green {
      background: #22c55e;
      color: #fff;
      border: none;
      transition: background 0.18s, box-shadow 0.18s;
      box-shadow: 0 2px 8px 0 #05966933;
    }
    .btn-green:hover {
      background: #16a34a;
      box-shadow: 0 4px 12px 0 #05966966;
    }
  </style>
</head>
<body class="text-white min-h-screen">
<!-- Navigation -->
<nav class="fixed top-0 w-full z-50 glass-effect">
  <div class="container mx-auto px-6 py-4">
    <div class="flex items-center justify-between">
      <div class="flex items-center space-x-2">
        <div class="flex items-center">
          <div class="sound-wave"></div><div class="sound-wave"></div><div class="sound-wave"></div><div class="sound-wave"></div><div class="sound-wave"></div>
        </div>
        <h1 class="text-xl font-bold ml-3 tracking-widest text-indigo-300">TheaterSound</h1>
      </div>
      <div class="hidden md:flex space-x-6">
        <a href="Accueil.php" class="nav-link hover:text-indigo-400 transition-colors">Accueil</a>
        <?php if (utilisateurConnecte()): ?>
            <a href="tableau.php" class="nav-link hover:text-indigo-400 transition-colors">Tableau de bord</a>
            <a href="capteur.php" class="nav-link text-indigo-400 hover:text-indigo-300 transition-colors font-semibold">Capteurs</a>
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
<section class="min-h-screen pt-32 pb-10">
  <div class="container mx-auto px-6">
    <div class="flex flex-wrap md:flex-nowrap justify-between items-center mb-8 gap-4">
      <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-indigo-200 drop-shadow-lg">Capteurs installés</h2>
      <div class="flex flex-wrap gap-3">
        <button id="add-sensor-btn" class="btn-indigo px-6 py-3 rounded-lg font-semibold shadow flex items-center gap-2">
          <i class="fas fa-plus"></i> Ajouter un capteur
        </button>
        <button onclick="openExportModal()" class="btn-green px-6 py-3 rounded-lg font-semibold shadow flex items-center gap-2">
          <i class="fas fa-download"></i> Exporter les données
        </button>
      </div>
    </div>
    <div class="table-main mb-8">
      <table class="w-full">
        <thead>
        <tr>
          <th class="px-6 py-3 text-left uppercase text-xs font-semibold tracking-wider">Élément</th>
          <th class="px-6 py-3 text-left uppercase text-xs font-semibold tracking-wider">Zone</th>
          <th class="px-6 py-3 text-left uppercase text-xs font-semibold tracking-wider">État</th>
          <th class="px-6 py-3 text-left uppercase text-xs font-semibold tracking-wider">Niveau actuel</th>
          <th class="px-6 py-3 text-left uppercase text-xs font-semibold tracking-wider">Dernière mise à jour</th>
        </tr>
        </thead>
        <tbody id="sensors-table">
        <!-- Son -->
        <tr class="card-hover">
          <td class="px-6 py-4 font-semibold">
            <a href="capteur_son.html" class="flex items-center text-indigo-300 sensor-link">
              <i class="fas fa-volume-up mr-2 text-xl"></i>
              <span class="cell-highlight">Son</span>
            </a>
          </td>
          <td class="px-6 py-4">Scène centrale</td>
          <td class="px-6 py-4">
            <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs font-bold shadow">En ligne</span>
          </td>
          <td class="px-6 py-4 font-semibold">72 dB</td>
          <td class="px-6 py-4 text-gray-400">Il y a 2 min</td>
        </tr>
        <!-- Lumière -->
        <tr class="card-hover">
          <td class="px-6 py-4 font-semibold">
            <a href="capteur_lumiere.html" class="flex items-center text-yellow-300 sensor-link">
              <i class="fas fa-lightbulb mr-2 text-xl"></i>
              <span class="cell-highlight" style="background:#46460b;color:#fef9c3;">Lumière</span>
            </a>
          </td>
          <td class="px-6 py-4">Scène centrale</td>
          <td class="px-6 py-4">
            <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs font-bold shadow">En ligne</span>
          </td>
          <td class="px-6 py-4 font-semibold">800 lux</td>
          <td class="px-6 py-4 text-gray-400">Il y a 2 min</td>
        </tr>
        <!-- Proximité -->
        <tr class="card-hover">
          <td class="px-6 py-4 font-semibold">
            <a href="capteur_proximite.html" class="flex items-center text-green-300 sensor-link">
              <i class="fas fa-ruler-horizontal mr-2 text-xl"></i>
              <span class="cell-highlight" style="background:#183c2a;color:#bbf7d0;">Proximité</span>
            </a>
          </td>
          <td class="px-6 py-4">Scène centrale</td>
          <td class="px-6 py-4">
            <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs font-bold shadow">En ligne</span>
          </td>
          <td class="px-6 py-4 font-semibold">1.2 m</td>
          <td class="px-6 py-4 text-gray-400">Il y a 2 min</td>
        </tr>
        <!-- Gaz -->
        <tr class="card-hover">
          <td class="px-6 py-4 font-semibold">
            <a href="capteur_gaz.html" class="flex items-center text-red-300 sensor-link">
              <i class="fas fa-wind mr-2 text-xl"></i>
              <span class="cell-highlight" style="background:#3b161b;color:#fecaca;">Gaz</span>
            </a>
          </td>
          <td class="px-6 py-4">Scène centrale</td>
          <td class="px-6 py-4">
            <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs font-bold shadow">En ligne</span>
          </td>
          <td class="px-6 py-4 font-semibold">0.03%</td>
          <td class="px-6 py-4 text-gray-400">Il y a 2 min</td>
        </tr>
        <!-- Température / Humidité -->
        <tr class="card-hover">
          <td class="px-6 py-4 font-semibold">
            <a href="capteur_temperature.html" class="flex items-center text-blue-300 sensor-link">
              <i class="fas fa-thermometer-half mr-2 text-xl"></i>
              <span class="cell-highlight" style="background:#10243a;color:#dbeafe;">Température / Humidité</span>
            </a>
          </td>
          <td class="px-6 py-4">Scène centrale</td>
          <td class="px-6 py-4">
            <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs font-bold shadow">En ligne</span>
          </td>
          <td class="px-6 py-4 font-semibold">22°C / 55%</td>
          <td class="px-6 py-4 text-gray-400">Il y a 2 min</td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- Modal Export Data -->
<div id="exportModalBg" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden justify-center items-center">
  <div id="exportModal" class="bg-gray-800 rounded-xl p-8 max-w-md w-full mx-4 relative shadow-2xl border border-gray-600">
    <button onclick="closeExportModal()" class="absolute top-3 right-3 text-gray-300 hover:text-white text-2xl">
      <i class="fas fa-times"></i>
    </button>
    <h2 class="text-2xl font-bold mb-4 text-center text-green-400">Exporter les données capteurs</h2>
    <form id="exportForm" action="export_pdf_mail.php" method="post">
      <label for="destinataire" class="block text-sm font-semibold mb-2">Destinataire (email) :</label>
      <input type="email" id="destinataire" name="destinataire" required placeholder="ex: alice@example.com"
             class="w-full px-4 py-2 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-green-500 mb-4"/>
      <div class="flex flex-wrap justify-between items-center gap-2 mb-4">
        <button type="button" onclick="document.getElementById('destinataire').value='<?= htmlspecialchars($_SESSION['user']['email']) ?>'" class="bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded-lg text-sm flex-1">
          M'envoyer à moi-même
        </button>
      </div>
      <button type="submit" class="w-full bg-green-600 hover:bg-green-700 px-4 py-2 rounded-lg font-semibold transition-colors">
        <i class="fas fa-paper-plane"></i> Envoyer le PDF
      </button>
    </form>
  </div>
</div>

<!-- Modal Ajout Capteur (inchangée) -->
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

<script>
function openExportModal() {
  document.getElementById('exportModalBg').classList.remove('hidden');
  document.getElementById('exportModalBg').classList.add('flex');
}
function closeExportModal() {
  document.getElementById('exportModalBg').classList.add('hidden');
  document.getElementById('exportModalBg').classList.remove('flex');
}
// Fermer modal export sur clic fond noir
document.getElementById('exportModalBg').addEventListener('click', function(e) {
  if (e.target === this) closeExportModal();
});
</script>
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
  .sound-wave {
    display:inline-block;width:4px;height:20px;background:#6366f1;margin-right:2px;border-radius:2px;animation:wave 1s infinite ease-in-out;animation-delay:calc(-0.2s * var(--i, 0));
  }
  .sound-wave:nth-child(2){background:#818cf8;--i:1;}
  .sound-wave:nth-child(3){background:#a5b4fc;--i:2;}
  .sound-wave:nth-child(4){background:#818cf8;--i:3;}
  .sound-wave:nth-child(5){background:#6366f1;--i:4;}
  @keyframes wave {0%,100%{height:20px}50%{height:30px}}
</style>
</body>
</html>