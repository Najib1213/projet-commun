function ouvrirModalSeuils(capteur) {
  fetch(`../Controleurs/get_seuils.php?capteur=${capteur}`)
    .then(res => res.json())
    .then(data => {
      document.getElementById('capteur_nom').value = capteur;
      document.getElementById('input_seuil').value = data.seuil;
      document.getElementById('input_danger').value = data.danger;
      document.getElementById('modalSeuils').classList.remove('hidden');
      document.getElementById('modalSeuils').classList.add('flex');
    });
}

function fermerModalSeuils() {
  document.getElementById('modalSeuils').classList.add('hidden');
  document.getElementById('modalSeuils').classList.remove('flex');
}

function enregistrerSeuils(event) {
  event.preventDefault();
  const formData = new FormData(document.getElementById('formSeuils'));
  fetch('../Controleurs/update_seuils.php', {
    method: 'POST',
    body: formData
  })
    .then(res => res.text())
    .then(() => {
      fermerModalSeuils();
      alert("Seuils mis à jour !");
      location.reload();
    });
}
