const modal = document.getElementById("sensor-modal");
const openModalBtn = document.getElementById("add-sensor-btn");
const closeModalBtn = document.getElementById("close-modal");
const cancelModalBtn = document.getElementById("cancel-modal");
const sensorTable = document.getElementById("sensors-table");
const form = modal.querySelector("form");

let editingRow = null;

// Ouvrir / fermer le modal
openModalBtn.addEventListener("click", () => {
  modal.classList.remove("hidden");
  form.reset();
  editingRow = null;
});
closeModalBtn.addEventListener("click", () => modal.classList.add("hidden"));
cancelModalBtn.addEventListener("click", () => modal.classList.add("hidden"));

// Soumettre le formulaire
form.addEventListener("submit", (e) => {
  e.preventDefault();

  const name = document.getElementById("sensor-name").value.trim();
  const zone = document.getElementById("sensor-zone").value;
  const status = document.getElementById("sensor-status").value;
  const dB = Math.floor(Math.random() * 20 + 60); // Simuler un niveau
  const updated = "Il y a quelques secondes";

  const statusClass = status === "online"
    ? "bg-green-500 text-white"
    : "bg-red-500 text-white";
  const statusLabel = status === "online" ? "En ligne" : "Hors ligne";
  const dBValue = status === "online" ? `${dB} dB` : "-- dB";
  const micColor = status === "online" ? "text-indigo-400" : "text-red-400";

  const rowHTML = `
    <tr class="border-b border-gray-600">
      <td class="px-6 py-4">
        <div class="flex items-center">
          <i class="fas fa-microphone ${micColor} mr-3"></i>${name}
        </div>
      </td>
      <td class="px-6 py-4">${zone}</td>
      <td class="px-6 py-4"><span class="${statusClass} px-2 py-1 rounded-full text-xs">${statusLabel}</span></td>
      <td class="px-6 py-4 font-semibold">${dBValue}</td>
      <td class="px-6 py-4 text-gray-400">${updated}</td>
      <td class="px-6 py-4">
        <button class="text-indigo-400 hover:text-indigo-300 mr-3 edit-btn"><i class="fas fa-edit"></i></button>
        <button class="text-red-400 hover:text-red-300 delete-btn"><i class="fas fa-trash"></i></button>
      </td>
    </tr>
  `;

  if (editingRow) {
    // Remplacer la ligne existante par une nouvelle ligne DOM
    const temp = document.createElement("tbody");
    temp.innerHTML = rowHTML.trim();
    sensorTable.replaceChild(temp.firstElementChild, editingRow);
    editingRow = null;
  } else {
    sensorTable.insertAdjacentHTML("beforeend", rowHTML);
  }

  modal.classList.add("hidden");
});

// Gérer édition / suppression
sensorTable.addEventListener("click", (e) => {
  const row = e.target.closest("tr");
  if (!row) return;

  if (e.target.closest(".delete-btn")) {
    if (confirm("Voulez-vous vraiment supprimer ce capteur ?")) {
      row.remove();
    }
  } else if (e.target.closest(".edit-btn")) {
    const cells = row.querySelectorAll("td");
    const name = cells[0].innerText.trim();
    const zone = cells[1].innerText.trim();
    const status = cells[2].innerText.includes("Hors") ? "offline" : "online";

    document.getElementById("sensor-name").value = name;
    document.getElementById("sensor-zone").value = zone;
    document.getElementById("sensor-status").value = status;

    editingRow = row;
    modal.classList.remove("hidden");
  }
});