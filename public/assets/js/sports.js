// ============================================
// SPORTS JS — Regime Expert 2026
// ============================================

'use strict';

$(document).ready(function() {
    if ($('#sportsTable').length) {
        $('#sportsTable').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json' },
            pageLength: 10,
            responsive: true
        });
    }
});

function submitSportForm(url) {
    if (!navigator.onLine) {
        showNotification('warning', 'Hors connexion', 'Impossible d\'enregistrer sans connexion');
        return;
    }

    const nom = $('#nom').val().trim();
    if (nom.length < 3) {
        showNotification('error', 'Nom invalide', 'Le nom doit contenir au moins 3 caractères');
        return;
    }

    adminAjax({
        url,
        method: 'POST',
        data:   $('#sportForm').serialize(),
        success: function(res) {
            showNotification('success', 'Sport enregistré', res.message || '');
            setTimeout(() => { window.location.href = '/admin/sports'; }, 1600);
        }
    });
}

function deleteSport(id) {
    if (!navigator.onLine) {
        showNotification('warning', 'Hors connexion', 'Impossible de supprimer sans connexion');
        return;
    }

    confirmDelete('Supprimer définitivement ce sport ?', function() {
        adminAjax({
            url: `/admin/sports/delete/${id}`,
            success: function() {
                showNotification('success', 'Sport supprimé');
                $(`#row-${id}`).fadeOut(350, function() { $(this).remove(); });
            }
        });
    });
}