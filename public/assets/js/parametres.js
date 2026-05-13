// ============================================
// PARAMETRES JS — Regime Expert 2026
// ============================================

'use strict';

function submitParametreForm(url) {
    if (!navigator.onLine) {
        showNotification('warning', 'Hors connexion', 'Impossible de mettre à jour sans connexion');
        return;
    }

    const valeur = $('#valeur').val().trim();
    if (!valeur) {
        showNotification('error', 'Valeur manquante', 'La valeur ne peut pas être vide');
        return;
    }

    adminAjax({
        url,
        method: 'POST',
        data:   $('#parametreForm').serialize(),
        success: function() {
            showNotification('success', 'Paramètre mis à jour');
            setTimeout(() => { window.location.href = '/admin/parametres'; }, 1600);
        }
    });
}