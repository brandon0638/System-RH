// ============================================
// REGIMES JS — Regime Expert 2026
// ============================================

'use strict';

$(document).ready(function() {
    if ($('#regimesTable').length) {
        $('#regimesTable').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json' },
            pageLength:  10,
            responsive:  true,
            dom:         '<"dt-top"lf>rt<"dt-bot"ip>',
            drawCallback: function() {
                // Wrap in scroll div on redraw
                if (!$('.data-table-wrap').length) {
                    $('#regimesTable').wrap('<div class="data-table-wrap"></div>');
                }
            }
        });
    }

    if ($('#regimeForm').length) {
        updateTotal();
        $('#viande, #poisson, #volaille').on('input', updateTotal);
    }
});

function updateTotal() {
    const v = parseFloat($('#viande').val())  || 0;
    const p = parseFloat($('#poisson').val()) || 0;
    const vol = parseFloat($('#volaille').val()) || 0;
    const total = v + p + vol;

    $('#totalPourcent').text(total.toFixed(2));

    if (Math.abs(total - 100) > 0.01) {
        $('#totalDisplay').addClass('error');
    } else {
        $('#totalDisplay').removeClass('error');
    }
}

function submitRegimeForm(url) {
    if (!navigator.onLine) {
        showNotification('warning', 'Hors connexion', 'Impossible d\'enregistrer sans connexion');
        return;
    }

    const v   = parseFloat($('#viande').val())   || 0;
    const p   = parseFloat($('#poisson').val())  || 0;
    const vol = parseFloat($('#volaille').val()) || 0;
    const total = v + p + vol;

    if (Math.abs(total - 100) > 0.01) {
        showNotification('error', 'Composition invalide', 'La somme des pourcentages doit être 100 %');
        return;
    }

    adminAjax({
        url,
        method: 'POST',
        data:   $('#regimeForm').serialize(),
        success: function(res) {
            showNotification('success', 'Régime enregistré', res.message || '');
            setTimeout(() => { window.location.href = '/admin/regimes'; }, 1600);
        }
    });
}

function deleteRegime(id, nom) {
    if (!navigator.onLine) {
        showNotification('warning', 'Hors connexion', 'Impossible de supprimer sans connexion');
        return;
    }

    confirmDelete(`Supprimer définitivement le régime "${nom}" ?`, function() {
        adminAjax({
            url: `/admin/regimes/delete/${id}`,
            success: function() {
                showNotification('success', 'Régime supprimé', `"${nom}" a été supprimé`);
                $(`#row-${id}`).addClass('out').fadeOut(350, function() { $(this).remove(); });
            }
        });
    });
}