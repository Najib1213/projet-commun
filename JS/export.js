    function openExportModal() {
      document.getElementById('exportModalBg').classList.remove('hidden');
      document.getElementById('exportModalBg').classList.add('flex');
    }
    function closeExportModal() {
      document.getElementById('exportModalBg').classList.add('hidden');
      document.getElementById('exportModalBg').classList.remove('flex');
    }
    // Fermer modal export sur clic fond noir
    document.getElementById('exportModalBg').addEventListener('click', function (e) {
      if (e.target === this) closeExportModal();
    });
