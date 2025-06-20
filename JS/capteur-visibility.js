const capteursVisibles = {
  son: true,
  lumiere: true,
  gaz: true,
  temp: true,
  humidite: true
};

function masquerCapteur(id) {
  const row = document.getElementById(`row-${id}`);
  if (row) {
    row.style.display = 'none';
    capteursVisibles[id] = false;
    console.log(`[masquerCapteur] ${id} masqué`);
  }
}

function afficherCapteur(id) {
  const row = document.getElementById(`row-${id}`);
  if (row) {
    row.style.display = '';
    capteursVisibles[id] = true;
    console.log(`[afficherCapteur] ${id} réaffiché`);
  }
  closeReactivateModal();
}

function openReactivateModal() {
  const liste = document.getElementById('reactivateList');
  liste.innerHTML = '';

  let auMoinsUn = false;
  for (const [id, visible] of Object.entries(capteursVisibles)) {
    if (!visible) {
      auMoinsUn = true;
      const li = document.createElement('li');
      li.innerHTML = `
        <button onclick="afficherCapteur('${id}')" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">
          Réafficher ${id.charAt(0).toUpperCase() + id.slice(1)}
        </button>`;
      liste.appendChild(li);
    }
  }

  if (!auMoinsUn) {
    liste.innerHTML = `<p class="text-sm text-gray-400 text-center">Tous les capteurs sont visibles</p>`;
  }

  document.getElementById('reactivateModal').classList.remove('hidden');
}

function closeReactivateModal() {
  document.getElementById('reactivateModal').classList.add('hidden');
}

document.getElementById('show-capteur-btn').addEventListener('click', openReactivateModal);
