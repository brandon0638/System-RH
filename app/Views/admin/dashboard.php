<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="content">
    <h2>Tableau de bord</h2>

    <!-- =========================
         CARTES DE STATISTIQUES
    ========================== -->
    <div class="metrics">
        <div class="metric">
            <div class="metric-top">
                <div>
                    <div class="metric-val"><?= $total_emp ?></div>
                    <div class="metric-label">Employés actifs</div>
                </div>
                <div class="metric-icon mi-forest">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>

        <div class="metric">
            <div class="metric-top">
                <div>
                    <div class="metric-val"><?= $en_attente ?></div>
                    <div class="metric-label">Demandes en attente</div>
                </div>
                <div class="metric-icon mi-amber">
                    <i class="bi bi-clock-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- =========================
         GRAPHIQUE : CONGÉS PAR MOIS
    ========================== -->
    <div class="data-card mt-4">
        <div class="data-card-head">
            <h3>Nombre de congés par mois</h3>
        </div>
        <div class="content">
            <canvas id="chartMois" height="100"></canvas>
        </div>
    </div>

    <!-- =========================
         GRAPHIQUE : CONGÉS PAR JOUR
    ========================== -->
    <div class="data-card mt-4">
        <div class="data-card-head">
            <h3>Répartition des congés par jour de la semaine</h3>
        </div>
        <div class="content">
            <canvas id="chartJours" height="100"></canvas>
        </div>
    </div>

    <!-- =========================
         DÉTAILS DYNAMIQUES PAR MOIS
    ========================== -->
    <div class="data-card mt-4">
        <div class="data-card-head">
            <h3>Absences par mois</h3>
        </div>

        <div class="content">
            <p class="td-muted">Affichage des 12 mois. Cliquez sur un mois pour dérouler les détails.</p>

            <div id="monthsGrid" class="mt-3 months-grid"></div>
        </div>
    </div>

</div>

<!-- =========================
     CHART.JS LOCAL
========================== -->
<style>
/* Mois - grille */
.months-grid{
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
        gap:12px;
}
.month-card{
        border:1px solid #e6e6e9;
        padding:12px;
        border-radius:8px;
        background:linear-gradient(180deg,#ffffff,#fbfbfc);
        box-shadow:0 1px 2px rgba(15,15,15,0.03);
        transition:transform .12s ease,box-shadow .12s ease;
}
.month-card:hover{transform:translateY(-3px);box-shadow:0 6px 18px rgba(50,50,93,0.06)}
.month-card .month-header{display:flex;justify-content:space-between;align-items:center}
.month-card .badge{background:#0d6efd;color:#fff;padding:4px 8px;border-radius:999px;font-weight:600}
.month-card .month-actions{margin-top:8px}
.toggle-details{background:transparent;border:1px solid #d0d7de;padding:6px 8px;border-radius:6px;cursor:pointer}
.toggle-details:hover{background:#f6f8fa}
.month-details{display:none;margin-top:10px;border-top:1px dashed #eee;padding-top:8px}
.absence-item{padding:8px 0;border-bottom:1px solid #f1f1f1}
.absence-item:last-child{border-bottom:none}
.absence-item strong{display:block}
.td-muted{color:#6b7280;font-size:13px}

/* Responsive tweaks */
@media (max-width:600px){
    .months-grid{grid-template-columns:repeat(2,1fr)}
}
</style>
<script src="<?= base_url('assets/js/chart.umd.min.js') ?>"></script>

<script>
/* =========================
   DONNÉES PHP → JS
========================= */
const congesParMois = <?= json_encode($congesParMois ?? []) ?>;
const congesParJour = <?= json_encode($congesParJour ?? []) ?>;
const absents = <?= json_encode($absents ?? []) ?>;

/* =========================
   MOIS / JOUR LABELS
========================= */
const labelsMois = congesParMois.map(item => item.mois);
const dataMois = congesParMois.map(item => item.total);

const labelsJours = congesParJour.map(item => item.jour);
const dataJours = congesParJour.map(item => item.total);

/* =========================
   FONCTION AFFICHAGE DÉTAILS
========================= */
function chargerAbsencesParMois(moisNum) {
    const filtered = absents.filter(a => a.date_debut && a.date_debut.substring(5,7) === moisNum);

    let html = "";
    if (filtered.length === 0) {
        html = "<p class='td-muted'>Aucune absence pour ce mois.</p>";
    } else {
        filtered.forEach(a => {
            html += `
                <div class="absence-item">
                    <strong>${a.emp_nom}</strong>
                    <span class="td-muted">${a.type_nom}</span>
                    <span class="td-muted">${a.date_debut} → ${a.date_fin}</span>
                </div>
            `;
        });
    }

    return html;
}

function renderMonthsGrid() {
    const months = [
        {num: '01', label: 'Jan'}, {num: '02', label: 'Fév'}, {num: '03', label: 'Mar'}, {num: '04', label: 'Avr'},
        {num: '05', label: 'Mai'}, {num: '06', label: 'Juin'}, {num: '07', label: 'Juil'}, {num: '08', label: 'Août'},
        {num: '09', label: 'Sep'}, {num: '10', label: 'Oct'}, {num: '11', label: 'Nov'}, {num: '12', label: 'Déc'}
    ];

    const mapTotals = {};
    (congesParMois || []).forEach(c => { mapTotals[c.mois_num] = c.total; });

    let html = '';
    months.forEach(m => {
        const total = mapTotals[m.num] || 0;
        const details = chargerAbsencesParMois(m.num);
        html += `
            <div class="month-card">
                <div class="month-header">
                    <strong>${m.label}</strong>
                    <span class="badge">${total}</span>
                </div>
                <div class="month-actions">
                    <button data-month="${m.num}" class="toggle-details">Détails</button>
                </div>
                <div class="month-details" id="details-${m.num}">${details}</div>
            </div>
        `;
    });

    document.getElementById('monthsGrid').innerHTML = html;

    document.querySelectorAll('.toggle-details').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const m = e.currentTarget.getAttribute('data-month');
            const el = document.getElementById('details-' + m);
            if (el.style.display === 'none' || el.style.display === '') {
                el.style.display = 'block';
            } else {
                el.style.display = 'none';
            }
        });
    });
}

// Render the months grid on load
renderMonthsGrid();

/* =========================
   GRAPHIQUE MOIS (CLICK ACTIVE)
========================= */
new Chart(document.getElementById('chartMois'), {
    type: 'bar',
    data: {
        labels: labelsMois,
        datasets: [{
            label: 'Nombre de congés',
            data: dataMois,
            backgroundColor: 'rgba(54, 162, 235, 0.7)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,

        onClick: (event, elements) => {
            if (elements.length > 0) {
                const index = elements[0].index;
                const mois = labelsMois[index];

                chargerAbsences(mois);
            }
        },

        scales: {
            y: {
                beginAtZero: true,
                ticks: { precision: 0 }
            }
        }
    }
});

/* =========================
   GRAPHIQUE JOUR
========================= */
new Chart(document.getElementById('chartJours'), {
    type: 'pie',
    data: {
        labels: labelsJours,
        datasets: [{
            data: dataJours,
            backgroundColor: [
                '#4CAF50',
                '#2196F3',
                '#FFC107',
                '#FF5722',
                '#9C27B0',
                '#009688',
                '#795548'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>

<?= $this->endSection() ?>