// RH - Gestion des demandes

// Filtre par employé
const filterEmploye = document.getElementById('filter-employe');
if (filterEmploye) {
    filterEmploye.addEventListener('change', function(e) {
        const rows = document.querySelectorAll('#demandes-table tbody tr');
        const value = e.target.value;
        rows.forEach(row => {
            if (row.querySelector('td')) {
                const employeId = row.getAttribute('data-employe-id');
                if (!value || employeId == value) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    });
}

// Filtre par statut pour historique
const filterStatut = document.getElementById('filter-statut');
if (filterStatut) {
    filterStatut.addEventListener('change', function(e) {
        const rows = document.querySelectorAll('#historique-table tbody tr');
        const value = e.target.value;
        rows.forEach(row => {
            if (row.querySelector('td')) {
                const statut = row.getAttribute('data-statut');
                if (!value || statut === value) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    });
}

// Modals
function showApproveModal(id, employe, jours) {
    const approveForm = document.getElementById('approveForm');
    if (approveForm) {
        approveForm.action = '/rh/approuver/' + id;
    }
    const approveEmploye = document.getElementById('approve-employe');
    if (approveEmploye) {
        approveEmploye.textContent = employe + ' (' + jours + ' jours)';
    }
    const approveModal = document.getElementById('approveModal');
    if (approveModal) {
        approveModal.style.display = 'flex';
    }
}

function showRefuseModal(id, employe) {
    const refuseForm = document.getElementById('refuseForm');
    if (refuseForm) {
        refuseForm.action = '/rh/refuser/' + id;
    }
    const refuseEmploye = document.getElementById('refuse-employe');
    if (refuseEmploye) {
        refuseEmploye.textContent = employe;
    }
    const refuseModal = document.getElementById('refuseModal');
    if (refuseModal) {
        refuseModal.style.display = 'flex';
    }
}

function closeModals() {
    const approveModal = document.getElementById('approveModal');
    if (approveModal) {
        approveModal.style.display = 'none';
    }
    const refuseModal = document.getElementById('refuseModal');
    if (refuseModal) {
        refuseModal.style.display = 'none';
    }
}

// Fermer modals en cliquant en dehors
window.onclick = function(event) {
    const approveModal = document.getElementById('approveModal');
    if (approveModal && event.target === approveModal) {
        closeModals();
    }
    const refuseModal = document.getElementById('refuseModal');
    if (refuseModal && event.target === refuseModal) {
        closeModals();
    }
}