console.log("✅ dashboard.js bien chargé");

const ctx = document.getElementById('soundChart')?.getContext('2d');
if (!ctx) {
    console.error("❌ Le canvas #soundChart est introuvable !");
} else {
    let soundChart;

    const capteurs = {
        'luminosite': {
            label: 'Luminosité (%)',
            icone: 'fa-sun',
            couleur: '#F59E0B',
            bgCouleur: 'rgba(245, 158, 11, 0.1)',
            fichier: 'get_luminosite.php',
            max: 100,
            unite: '%'
        },
        'son': {
            label: 'Niveau sonore (dB)',
            icone: 'fa-volume-up',
            couleur: '#3B82F6',
            bgCouleur: 'rgba(59, 130, 246, 0.1)',
            fichier: 'get_son.php',
            max: 120,
            unite: 'dB'
        },
        'temperature': {
            label: 'Température (°C)',
            icone: 'fa-thermometer-half',
            couleur: '#EF4444',
            bgCouleur: 'rgba(239, 68, 68, 0.1)',
            fichier: 'get_temperature.php',
            max: 50,
            unite: '°C'
        },
        'humidite': {
            label: 'Humidité (%)',
            icone: 'fa-tint',
            couleur: '#10B981',
            bgCouleur: 'rgba(16, 185, 129, 0.1)',
            fichier: 'get_humidite.php',
            max: 100,
            unite: '%'
        },
        'gaz': {
            label: 'Concentration gaz (ppm)',
            icone: 'fa-cloud',
            couleur: '#A855F7',
            bgCouleur: 'rgba(168, 85, 247, 0.1)',
            fichier: 'get_gaz.php',
            max: 1000,
            unite: 'ppm'
        }
    };

    const params = new URLSearchParams(window.location.search);
    const capteur = params.get('sensor') || 'luminosite';
    const config = capteurs[capteur];

    // Met à jour l’icône dynamique
    const iconElement = document.getElementById('icon-niveau');
    if (iconElement && config.icone) {
        iconElement.className = `fas ${config.icone} text-yellow-400`;
    }

    // Surligne le bon bouton
    document.querySelectorAll('.inline-flex a').forEach(a => {
        const url = new URL(a.href);
        const current = url.searchParams.get("sensor");
        if (current === capteur) {
            a.classList.add('bg-gray-600');
        } else {
            a.classList.remove('bg-gray-600');
        }
    });

    soundChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: config.label,
                data: [],
                borderColor: config.couleur,
                backgroundColor: config.bgCouleur,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: config.max,
                    grid: { color: '#374151' },
                    ticks: { color: '#9CA3AF' }
                },
                x: {
                    grid: { color: '#374151' },
                    ticks: { color: '#9CA3AF' }
                }
            },
            plugins: {
                legend: {
                    labels: { color: '#9CA3AF' }
                }
            }
        }
    });

    function chargerDonnees() {
        console.log("📦 Fichier à charger :", config.fichier);
        fetch(`../Controleurs/${config.fichier}`)
            .then(response => response.json())
            .then(data => {
                if (!Array.isArray(data) || data.length === 0) {
                    console.warn("📭 Aucune donnée reçue");
                    return;
                }

                const labels = data.map(entry =>
                    new Date(entry.recorded_at).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })
                );

                const values = data.map(entry => parseFloat(entry.value_percent)).filter(v => !isNaN(v));

                soundChart.data.labels = labels;
                soundChart.data.datasets[0].data = values;
                soundChart.update();

                const current = values.at(-1) ?? "--";
                const currentTimeRaw = data.at(-1)?.recorded_at ?? null;
                const currentTime = currentTimeRaw ? new Date(currentTimeRaw).toLocaleTimeString('fr-FR') : "--";

                let max = "--", maxTime = "--";
                if (values.length > 0) {
                    max = Math.max(...values);
                    const maxIndex = values.indexOf(max);
                    const maxTimeRaw = data[maxIndex]?.recorded_at;
                    if (maxTimeRaw) {
                        maxTime = new Date(maxTimeRaw).toLocaleTimeString('fr-FR');
                    }
                }

                const avg = values.length > 0 ? values.reduce((a, b) => a + b, 0) / values.length : "--";

                document.getElementById('current-level').textContent = (typeof current === "number" ? current.toFixed(2) : "--") + ' ' + config.unite;
                document.getElementById('current-time').textContent = currentTime;
                document.getElementById('max-level').textContent = (typeof max === "number" ? max.toFixed(2) : "--") + ' ' + config.unite;
                document.getElementById('max-time').textContent = maxTime;
                document.getElementById('avg-level').textContent = (typeof avg === "number" ? avg.toFixed(2) : "--") + ' ' + config.unite;
            })
            .catch(error => console.error("❌ Erreur de chargement des données :", error));
    }

    setInterval(chargerDonnees, 10000);
    chargerDonnees();
}
