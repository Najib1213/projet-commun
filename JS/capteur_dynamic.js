const capteurs = [
  { id: 'son', url: '../Controleurs/get_son.php', key: 'value_percent', suffix: 'dB' },
  { id: 'lumiere', url: '../Controleurs/get_luminosite.php', key: 'value_percent', suffix: 'lux' },
  { id: 'gaz', url: '../Controleurs/get_gaz.php', key: 'value_percent', suffix: '%' },
  { id: 'temp', url: '../Controleurs/get_temperature.php', key: 'value_percent', suffix: '°C' },
  { id: 'humidite', url: '../Controleurs/get_humidite.php', key: 'value_percent', suffix: '%' }
];
function isOnline(dateString) {
  if (!dateString) return false;
  const now = new Date();
  const recorded = new Date(dateString);
  const diffHours = (now - recorded) / (1000 * 60 * 60);
  return diffHours <= 2;
}

function timeAgo(dateString) {
  if (!dateString) return 'N/A';
  const diff = Math.floor((Date.now() - new Date(dateString)) / 1000);
  if (diff < 60) return "À l’instant";
  if (diff < 3600) return `Il y a ${Math.floor(diff / 60)} min`;
  if (diff < 86400) return `Il y a ${Math.floor(diff / 3600)} h`;
  return `Il y a ${Math.floor(diff / 86400)} j`;
}

function updateCapteur(id, valeur, time) {
  const valElem = document.getElementById(`val-${id}`);
  const timeElem = document.getElementById(`time-${id}`);
  const statusElem = document.getElementById(`status-${id}`); // <--- NOUVEAU
  console.log(`[updateCapteur] id=${id}, valeur=${valeur}, time=${time}`);

  if (valElem) valElem.textContent = valeur;
  if (timeElem) timeElem.textContent = timeAgo(time);

  // Ajout de l'état "en ligne" ou "hors ligne"
  if (statusElem) {
    const online = isOnline(time);
    statusElem.textContent = online ? "En ligne" : "Hors ligne";
    statusElem.className = online
      ? "bg-green-500 text-white px-2 py-1 rounded-full text-xs font-bold shadow"
      : "bg-gray-500 text-white px-2 py-1 rounded-full text-xs font-bold shadow";
  }
}


function fetchAllCapteurs() {
  capteurs.forEach(capteur => {
    console.log(`[fetch] Lecture de ${capteur.id} via ${capteur.url}`);
    fetch(capteur.url)
      .then(res => {
        console.log(`[fetch] Réponse reçue pour ${capteur.id}`);
        return res.json();
      })
      .then(data => {
        console.log(`[fetch] Données ${capteur.id}:`, data);
        const last = data[data.length - 1];
        if (!last) {
          console.warn(`⚠️ Aucune donnée pour ${capteur.id}`);
          return;
        }
        const valeur = last[capteur.key] + ' ' + capteur.suffix;
        updateCapteur(capteur.id, valeur, last.recorded_at);
      })
      .catch(err => {
        console.error(`❌ Erreur capteur ${capteur.id}:`, err);
      });
  });
}

document.addEventListener('DOMContentLoaded', () => {
  console.log('[init] fetchAllCapteurs déclenché');
  fetchAllCapteurs();
});
