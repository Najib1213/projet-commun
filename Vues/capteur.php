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
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TheaterSound Monitor - Gestion des Capteurs</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../css/Accueil.css">
  <link rel="stylesheet" href="../css/anim.css">
  <link rel="stylesheet" href="../css/capteur.css">


</head>

<body class="text-white min-h-screen">
  <!-- Navigation -->
  <nav class="fixed top-0 w-full z-50 glass-effect">
    <div class="container mx-auto px-6 py-4">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-1">
          <div class="sound-wave" style="--i:0"></div>
          <div class="sound-wave" style="--i:1"></div>
          <div class="sound-wave" style="--i:2"></div>
          <div class="sound-wave" style="--i:3"></div>
          <div class="sound-wave" style="--i:4"></div>
          <h1 class="text-xl font-bold ml-3 text-white">TheaterSound</h1>
        </div>
        <div class="hidden md:flex space-x-6">
          <a href="Accueil.php" class="nav-link hover:text-indigo-400 <?php if (basename($_SERVER['PHP_SELF']) == 'Accueil.php')
            echo 'active'; ?>">Accueil</a>
          <a href="tableau.php?sensor=all" class="nav-link hover:text-indigo-400 <?php if (strpos($_SERVER['REQUEST_URI'], 'tableau.php') !== false)
            echo 'active'; ?>">Tableau
            de bord</a>
          <a href="capteur.php" class="nav-link hover:text-indigo-400 <?php if (basename($_SERVER['PHP_SELF']) == 'capteur.php')
            echo 'active'; ?>">Capteurs</a>
          <?php if (function_exists('utilisateurConnecte') && utilisateurConnecte()): ?>
            <a href="deconnexion.php" class="nav-link text-red-400">Déconnexion
              (<?= htmlspecialchars(getPrenomConnecte()) ?>)</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </nav>




  <!-- Capteurs -->
  <section class="min-h-screen pt-32 pb-10">
    <div class="container mx-auto px-6">
      <div class="flex flex-wrap md:flex-nowrap justify-between items-center mb-8 gap-4">
        <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-indigo-200 drop-shadow-lg">Capteurs installés
        </h2>
        <div class="flex flex-wrap gap-3">
          <button id="show-capteur-btn"
            class="btn-indigo px-6 py-3 rounded-lg font-semibold shadow flex items-center gap-2">
            <i class="fas fa-plus"></i> Ajouter un capteur
          </button>

          <button onclick="openExportModal()"
            class="btn-green px-6 py-3 rounded-lg font-semibold shadow flex items-center gap-2">
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
              <th class="px-6 py-3 text-left uppercase text-xs font-semibold tracking-wider">Seuils</th>

            </tr>
          </thead>
          <tbody id="sensors-table">
            <!-- Son -->
            <tr id="row-son" class="card-hover">
              <td class="px-6 py-4 font-semibold">
                <a href="capteur_son.html" class="flex items-center text-indigo-300 sensor-link">
                  <i class="fas fa-volume-up mr-2 text-xl"></i>
                  <span class="cell-highlight">Son</span>
                </a>
              </td>
              <td class="px-6 py-4">Scène centrale</td>
              <td class="px-6 py-4">
                <span id="status-son"
                  class="bg-gray-500 text-white px-2 py-1 rounded-full text-xs font-bold shadow">Chargement...</span>
              </td>
              <td class="px-6 py-4 font-semibold" id="val-son">Chargement...</td>
              <td class="px-6 py-4 text-gray-400" id="time-son">Chargement...</td>
              <td class="px-6 py-4">
                <button class="action-btn bg-gray-700 hover:bg-gray-600" onclick="ouvrirModalSeuils('son')">
                  <i class="fas fa-cog text-white"></i>
                </button>
              </td>
              <td class="px-6 py-4">
                <button class="text-red-400 hover:text-red-600 text-lg" onclick="masquerCapteur('son')">
                  <i class="fas fa-times"></i>
                </button>
              </td>
            </tr>

            <!-- Lumière -->
            <tr id="row-lumiere" class="card-hover">
              <td class="px-6 py-4 font-semibold">
                <a href="capteur_lumiere.html" class="flex items-center text-yellow-300 sensor-link">
                  <i class="fas fa-lightbulb mr-2 text-xl"></i>
                  <span class="cell-highlight" style="background:#46460b;color:#fef9c3;">Lumière</span>
                </a>
              </td>
              <td class="px-6 py-4">Scène centrale</td>
              <td class="px-6 py-4">
                <span id="status-lumiere"
                  class="bg-gray-500 text-white px-2 py-1 rounded-full text-xs font-bold shadow">Chargement...</span>
              </td>
              <td class="px-6 py-4 font-semibold" id="val-lumiere">Chargement...</td>
              <td class="px-6 py-4 text-gray-400" id="time-lumiere">Chargement...</td>
              <td class="px-6 py-4">
                <button class="action-btn bg-gray-700 hover:bg-gray-600" onclick="ouvrirModalSeuils('lumiere')">
                  <i class="fas fa-cog text-white"></i>
                </button>
              </td>
              <td class="px-6 py-4">
                <button class="text-red-400 hover:text-red-600 text-lg" onclick="masquerCapteur('lumiere')">
                  <i class="fas fa-times"></i>
                </button>
              </td>
            </tr>

            <!-- Gaz -->
            <tr id="row-gaz" class="card-hover">
              <td class="px-6 py-4 font-semibold">
                <a href="capteur_gaz.html" class="flex items-center text-red-300 sensor-link">
                  <i class="fas fa-wind mr-2 text-xl"></i>
                  <span class="cell-highlight" style="background:#3b161b;color:#fecaca;">Gaz</span>
                </a>
              </td>
              <td class="px-6 py-4">Scène centrale</td>
              <td class="px-6 py-4">
                <span id="status-gaz"
                  class="bg-gray-500 text-white px-2 py-1 rounded-full text-xs font-bold shadow">Chargement...</span>
              </td>
              <td class="px-6 py-4 font-semibold" id="val-gaz">Chargement...</td>
              <td class="px-6 py-4 text-gray-400" id="time-gaz">Chargement...</td>
              <td class="px-6 py-4">
                <button class="action-btn bg-gray-700 hover:bg-gray-600" onclick="ouvrirModalSeuils('gaz')">
                  <i class="fas fa-cog text-white"></i>
                </button>
              </td>
              <td class="px-6 py-4">
                <button class="text-red-400 hover:text-red-600 text-lg" onclick="masquerCapteur('gaz')">
                  <i class="fas fa-times"></i>
                </button>
              </td>
            </tr>

            <!-- Température -->
            <tr id="row-temp" class="card-hover">
              <td class="px-6 py-4 font-semibold">
                <a href="capteur_temperature.html" class="flex items-center text-blue-300 sensor-link">
                  <i class="fas fa-temperature-low mr-2 text-xl"></i>
                  <span class="cell-highlight" style="background:#10243a;color:#dbeafe;">Température</span>
                </a>
              </td>
              <td class="px-6 py-4">Scène centrale</td>
              <td class="px-6 py-4">
                <span id="status-temp"
                  class="bg-gray-500 text-white px-2 py-1 rounded-full text-xs font-bold shadow">Chargement...</span>
              </td>
              <td class="px-6 py-4 font-semibold" id="val-temp">Chargement...</td>
              <td class="px-6 py-4 text-gray-400" id="time-temp">Chargement...</td>
              <td class="px-6 py-4">
                <button class="action-btn bg-gray-700 hover:bg-gray-600" onclick="ouvrirModalSeuils('temp')">
                  <i class="fas fa-cog text-white"></i>
                </button>
              </td>
              <td class="px-6 py-4">
                <button class="text-red-400 hover:text-red-600 text-lg" onclick="masquerCapteur('temp')">
                  <i class="fas fa-times"></i>
                </button>
              </td>
            </tr>

            <!-- Humidité -->
            <tr id="row-humidite" class="card-hover">
              <td class="px-6 py-4 font-semibold">
                <a href="capteur_humidite.html" class="flex items-center text-cyan-300 sensor-link">
                  <i class="fas fa-tint mr-2 text-xl"></i>
                  <span class="cell-highlight" style="background:#0e374b;color:#a5f3fc;">Humidité</span>
                </a>
              </td>
              <td class="px-6 py-4">Scène centrale</td>
              <td class="px-6 py-4">
                <span id="status-humidite"
                  class="bg-gray-500 text-white px-2 py-1 rounded-full text-xs font-bold shadow">Chargement...</span>
              </td>
              <td class="px-6 py-4 font-semibold" id="val-humidite">Chargement...</td>
              <td class="px-6 py-4 text-gray-400" id="time-humidite">Chargement...</td>
              <td class="px-6 py-4">
                <button class="action-btn bg-gray-700 hover:bg-gray-600" onclick="ouvrirModalSeuils('humidite')">
                  <i class="fas fa-cog text-white"></i>
                </button>
              </td>
              <td class="px-6 py-4">
                <button class="text-red-400 hover:text-red-600 text-lg" onclick="masquerCapteur('humidite')">
                  <i class="fas fa-times"></i>
                </button>
              </td>
            </tr>
          </tbody>



        </table>
      </div>
    </div>
  </section>

  <!-- Modal Export Data -->
  <div id="exportModalBg" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden justify-center items-center">
    <div id="exportModal"
      class="bg-gray-800 rounded-xl p-8 max-w-md w-full mx-4 relative shadow-2xl border border-gray-600">
      <button onclick="closeExportModal()" class="absolute top-3 right-3 text-gray-300 hover:text-white text-2xl">
        <i class="fas fa-times"></i>
      </button>
      <h2 class="text-2xl font-bold mb-4 text-center text-green-400">Exporter les données capteurs</h2>
      <form id="exportForm" action="export_pdf_mail.php" method="post">
        <label for="destinataire" class="block text-sm font-semibold mb-2">Destinataire (email) :</label>
        <input type="email" id="destinataire" name="destinataire" required placeholder="ex: alice@example.com"
          class="w-full px-4 py-2 rounded-lg bg-gray-700 border border-gray-600 text-white focus:outline-none focus:ring-2 focus:ring-green-500 mb-4" />
        <div class="flex flex-wrap justify-between items-center gap-2 mb-4">
          <button type="button"
            onclick="document.getElementById('destinataire').value='<?= htmlspecialchars($_SESSION['user']['email']) ?>'"
            class="bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded-lg text-sm flex-1">
            M'envoyer à moi-même
          </button>
        </div>
        <button type="submit"
          class="w-full bg-green-600 hover:bg-green-700 px-4 py-2 rounded-lg font-semibold transition-colors">
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
        <input type="text" id="sensor-name" placeholder="Nom du capteur"
          class="w-full px-4 py-3 bg-gray-600 rounded-lg text-white" />
        <input type="text" id="sensor-zone" placeholder="Zone"
          class="w-full px-4 py-3 bg-gray-600 rounded-lg text-white" />
        <input type="text" id="sensor-state" placeholder="État (En ligne / Hors ligne)"
          class="w-full px-4 py-3 bg-gray-600 rounded-lg text-white" />
        <input type="text" id="sensor-level" placeholder="Niveau (ex: 72 dB)"
          class="w-full px-4 py-3 bg-gray-600 rounded-lg text-white" />
        <input type="text" id="sensor-time" placeholder="Dernière mise à jour (ex: Il y a 1 min)"
          class="w-full px-4 py-3 bg-gray-600 rounded-lg text-white" />
        <div class="flex justify-end space-x-4">
          <button type="button" id="cancel-modal"
            class="bg-gray-500 hover:bg-gray-600 px-4 py-2 rounded-lg">Annuler</button>
          <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-lg">Ajouter</button>
        </div>
      </form>
    </div>
  </div>
  <div id="modalSeuils" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden justify-center items-center">
    <div class="bg-gray-800 p-6 rounded-lg max-w-sm w-full shadow-lg relative">
      <button onclick="fermerModalSeuils()" class="absolute top-2 right-3 text-white text-xl">&times;</button>
      <h3 class="text-xl font-semibold text-indigo-300 mb-4">Modifier les seuils</h3>
      <form id="formSeuils" class="space-y-3" onsubmit="return enregistrerSeuils(event)">
        <input type="hidden" id="capteur_nom" name="capteur" />
        <label class="block text-sm">Seuil :
          <input type="number" id="input_seuil" name="seuil" required
            class="w-full px-3 py-2 rounded bg-gray-700 text-white mt-1" />
        </label>
        <label class="block text-sm">Danger :
          <input type="number" id="input_danger" name="danger" required
            class="w-full px-3 py-2 rounded bg-gray-700 text-white mt-1" />
        </label>
        <button type="submit" class="btn-indigo px-4 py-2 rounded-lg w-full">Enregistrer</button>
      </form>
    </div>
  </div>
  <!-- Modal Réactivation de capteur -->
  <div id="reactivateModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden justify-center items-center">
    <div class="bg-gray-800 rounded-xl p-6 w-96 shadow-2xl border border-gray-600 relative">
      <button onclick="closeReactivateModal()" class="absolute top-3 right-3 text-gray-300 hover:text-white text-2xl">
        &times;
      </button>
      <h2 class="text-xl font-bold mb-4 text-center text-indigo-200">Réactiver un capteur</h2>
      <ul id="reactivateList" class="space-y-2">
        <!-- Capteurs masqués injectés ici -->
      </ul>
    </div>
  </div>

  <script src="../js/capteur.js"></script>
  <script src="../js/capteur-seuils.js"></script>
  <script src="../js/export.js"></script>
  <script src="../js/capteur_dynamic.js"></script>
  <script src="../js/capteur-visibility.js"></script>



</body>

</html>