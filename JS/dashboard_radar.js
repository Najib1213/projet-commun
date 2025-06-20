console.log("📊 dashboard_radar.js bien chargé");

const capteurs = ['son', 'luminosite', 'temperature', 'humidite', 'gaz'];
const maxRef = {
    son: 120,
    luminosite: 100,
    temperature: 50,
    humidite: 100,
    gaz: 1000
};
const valeurs = {};
const labels = {
    son: "Son",
    luminosite: "Luminosité",
    temperature: "Température",
    humidite: "Humidité",
    gaz: "Gaz"
};
const couleursTexte = {
    son: "text-yellow-400",
    luminosite: "text-purple-400",
    temperature: "text-red-400",
    humidite: "text-blue-400",
    gaz: "text-green-400"
};
const icones = {
    son: "fa-volume-up",
    luminosite: "fa-lightbulb",
    temperature: "fa-thermometer-half",
    humidite: "fa-tint",
    gaz: "fa-cloud"
};

const seuilsAlerte = {
    son: { seuil: 80, danger: 100 },
    luminosite: { seuil: 90, danger: 95 },
    temperature: { seuil: 35, danger: 45 },
    humidite: { seuil: 80, danger: 90 },
    gaz: { seuil: 500, danger: 800 }
};

const alertesDejaEnvoyees = {};
let radarChart;

function majCapteurs() {
    Promise.all(
        capteurs.map(capteur =>
            fetch(`../Controleurs/get_${capteur}.php`)
                .then(res => res.json())
                .then(data => {
                    const valeur = parseFloat(data[data.length - 1]?.value_percent ?? 0);
                    const pourcentage = Math.min(100, (valeur / maxRef[capteur]) * 100);
                    valeurs[capteur] = Math.round(pourcentage * 10) / 10;
                })
                .catch(err => {
                    console.error(`❌ Erreur sur ${capteur}`, err);
                    valeurs[capteur] = 0;
                })
        )
    ).then(() => {
        const container = document.getElementById("capteur-blocs");
        container.innerHTML = "";

        capteurs.forEach(c => {
            const bloc = document.createElement("div");
            bloc.className = "bg-gray-700 p-6 rounded-xl";
            bloc.innerHTML = `
                <div class="flex justify-between mb-2">
                    <h3 class="text-lg font-semibold">${labels[c]}</h3>
                    <i class="fas ${icones[c]} ${couleursTexte[c]}"></i>
                </div>
                <div class="text-3xl font-bold ${couleursTexte[c]}">${valeurs[c]} %</div>
                <div class="text-sm text-gray-400 mt-2">Stimulation</div>
            `;
            container.appendChild(bloc);
        });

        const data = capteurs.map(c => valeurs[c]);
        if (radarChart) {
            radarChart.data.datasets[0].data = data;
            radarChart.update();
        } else {
            const ctx = document.getElementById('radarChart').getContext('2d');
            radarChart = new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: capteurs.map(c => labels[c]),
                    datasets: [{
                        label: "Stimulation actuelle (%)",
                        data: data,
                        fill: true,
                        backgroundColor: "rgba(59, 130, 246, 0.2)",
                        borderColor: "rgba(59, 130, 246, 1)",
                        pointBackgroundColor: "rgba(59, 130, 246, 1)",
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        r: {
                            min: 0,
                            max: 100,
                            ticks: { stepSize: 20, color: "#9CA3AF" },
                            grid: { color: "#4B5563" },
                            pointLabels: { color: "#E5E7EB", font: { size: 14 } }
                        }
                    },
                    plugins: {
                        legend: { labels: { color: "#E5E7EB" } }
                    }
                }
            });
        }

        capteurs.forEach(c => {
            const val = valeurs[c];
            const seuil = seuilsAlerte[c];
            const cle = `${c}-${val}`;

            if (val >= seuil.danger && alertesDejaEnvoyees[c] !== cle) {
                envoyerAlerte(c, val, `>= ${seuil.danger}`, "danger", `Alerte DANGER : ${labels[c]} à ${val}%`);
                alertesDejaEnvoyees[c] = cle;
            } else if (val >= seuil.seuil && val < seuil.danger && alertesDejaEnvoyees[c] !== cle) {
                envoyerAlerte(c, val, `>= ${seuil.seuil}`, "moyen", `Alerte : ${labels[c]} élevée à ${val}%`);
                alertesDejaEnvoyees[c] = cle;
            }
        });

        chargerAlertes();
    });
}

function envoyerAlerte(capteur, niveau, seuil, niveauAlerte, message) {
    fetch("../Controleurs/log_alerte.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({
            capteur,
            niveau,
            niveau_alerte: niveauAlerte,
            message
        })
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                console.log(`✅ Alerte enregistrée pour ${capteur}`);
            }
        });
}

function chargerAlertes() {
    fetch("../Controleurs/get_alertes.php")
        .then(res => {
            if (!res.ok) throw new Error("HTTP " + res.status);
            return res.json();
        })
        .then(alertes => {
            const container = document.getElementById("liste-alertes");
            if (!container) return;
            container.innerHTML = "";

            alertes
                .slice().reverse().slice(0, 5)
                .forEach(alert => {
                    const couleur = alert.niveau_alerte === "danger" ? "red" :
                        alert.niveau_alerte === "moyen" ? "yellow" : "gray";
                    const div = document.createElement("div");
                    div.className = `mb-2 p-3 rounded-lg border-l-4 border-${couleur}-400 bg-${couleur}-100 text-${couleur}-800`;
                    div.innerHTML = `
                        <strong>${alert.capteur.toUpperCase()}</strong> — ${alert.message} <br>
                        <span class="text-xs text-${couleur}-600">${alert.horodatage}</span>
                    `;
                    container.appendChild(div);
                });

            if (alertes.length === 0) {
                container.innerHTML = "<div class='text-gray-400'>Aucune alerte récente.</div>";
            }
        })
        .catch(error => {
            console.error("❌ Erreur lors du chargement des alertes :", error);
        });
}

majCapteurs();
setInterval(majCapteurs, 1000);
