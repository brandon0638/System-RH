// ============================================
// CODES JS — Regime Expert 2026
// ============================================

'use strict';

$(document).ready(function() {
    if ($('#codesTable').length) {
        $('#codesTable').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json' },
            pageLength:  10,
            responsive:  true,
            order:       [[0, 'desc']]
        });
    }
});

function submitCodeForm(url) {
    if (!navigator.onLine) {
        showNotification('warning', 'Hors connexion', 'Impossible d\'enregistrer sans connexion');
        return;
    }

    const code = $('#code').val().trim();
    if (code.length < 3) {
        showNotification('error', 'Code invalide', 'Le code doit contenir au moins 3 caractères');
        return;
    }

    const valeur = parseFloat($('#valeur').val());
    if (isNaN(valeur) || valeur <= 0) {
        showNotification('error', 'Valeur invalide', 'La valeur doit être un nombre positif');
        return;
    }

    adminAjax({
        url: url,
        method: 'POST',
        data: $('#codeForm').serialize(),
        success: function(res) {
            // ✅ Vérifier si la réponse est un succès
            if (res.success === true) {
                showNotification('success', 'Succès', res.message || 'Code enregistré');
                setTimeout(() => {
                    window.location.href = '/admin/codes';
                }, 1600);
            } else {
                showNotification('error', 'Erreur', res.message || 'Une erreur est survenue');
            }
        },
        error: function(xhr) {
            let msg = 'Une erreur est survenue';
            try {
                const res = JSON.parse(xhr.responseText);
                msg = res.message || msg;
            } catch(e) {
                if (xhr.status === 403) {
                    msg = 'Token CSRF invalide. Rechargez la page.';
                }
            }
            showNotification('error', 'Erreur', msg);
        }
    });
}

function deleteCode(id) {
    if (!navigator.onLine) {
        showNotification('warning', 'Hors connexion', 'Impossible de supprimer sans connexion');
        return;
    }

    confirmDelete('Supprimer définitivement ce code promo ?', function() {
        adminAjax({
            url: `/admin/codes/delete/${id}`,
            success: function(res) {
                if (res.success === true) {
                    showNotification('success', 'Succès', res.message || 'Code supprimé');
                    $(`#row-${id}`).fadeOut(350, function() {
                        $(this).remove();
                    });
                } else {
                    showNotification('error', 'Erreur', res.message || 'Impossible de supprimer');
                }
            },
            error: function() {
                showNotification('error', 'Erreur', 'Impossible de supprimer le code');
            }
        });
    });
}