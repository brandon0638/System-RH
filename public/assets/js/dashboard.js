// ============================================
// DASHBOARD JS — Regime Expert 2026
// ============================================

'use strict';

let _dashCharts = {};

$(document).ready(function() {
    loadDashboardStats();
    // Auto-refresh every 60s
    setInterval(loadDashboardStats, 60000);
});

function loadDashboardStats() {
    if (!navigator.onLine) {
        // Offline: show skeleton
        showNotification('info', 'Hors connexion', 'Les statistiques ne sont pas disponibles');
        return;
    }

    $.ajax({
        url:      '/admin/dashboard/stats',
        method:   'GET',
        dataType: 'json',
        success: function(data) {
            // Stat cards
            if (data.stats) {
                const vals = [data.stats.users, data.stats.regimes, data.stats.sports, data.stats.codes];
                $('.stat-value').each(function(i) {
                    const target = parseInt(vals[i]) || 0;
                    animateNumber($(this), target);
                });
            }

            // Régimes chart
            if (data.regimes && data.regimes.length) {
                renderChart('regimesChart', {
                    labels: data.regimes.map(r => r.nom),
                    values: data.regimes.map(r => r.variation_poids_grammes),
                    label:  'Variation (g/jour)',
                    color:  '#3DAA72'
                });
            }

            // Sports chart
            if (data.sports && data.sports.length) {
                renderChart('sportsChart', {
                    labels: data.sports.map(s => s.nom),
                    values: data.sports.map(s => s.variation_poids_grammes),
                    label:  'Variation (g/séance)',
                    color:  '#3B82F6'
                });
            }
        },
        error: function() {
            console.warn('Dashboard: impossible de charger les statistiques');
        }
    });
}

// Animate counter
function animateNumber($el, target) {
    const start    = parseInt($el.text()) || 0;
    const duration = 600;
    const step     = 16;
    const steps    = duration / step;
    const delta    = (target - start) / steps;
    let current    = start;
    let count       = 0;

    const timer = setInterval(() => {
        count++;
        current += delta;
        $el.text(Math.round(count >= steps ? target : current));
        if (count >= steps) clearInterval(timer);
    }, step);
}

function renderChart(canvasId, { labels, values, label, color }) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;

    if (_dashCharts[canvasId]) {
        _dashCharts[canvasId].destroy();
    }

    const positiveColor = '#3DAA72';
    const negativeColor = '#EF4444';

    const bgColors = values.map(v => v >= 0
        ? (color === positiveColor ? 'rgba(61,170,114,0.15)' : 'rgba(59,130,246,0.15)')
        : 'rgba(239,68,68,0.15)');

    const borderColors = values.map(v => v >= 0
        ? (color === positiveColor ? '#3DAA72' : '#3B82F6')
        : '#EF4444');

    _dashCharts[canvasId] = new Chart(ctx.getContext('2d'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label,
                data:            values,
                backgroundColor: bgColors,
                borderColor:     borderColors,
                borderWidth:     2,
                borderRadius:    8,
                borderSkipped:   false,
                barPercentage:   0.65
            }]
        },
        options: {
            responsive:          true,
            maintainAspectRatio: true,
            animation: {
                duration: 800,
                easing:   'easeOutQuart'
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#111827',
                    titleColor:      '#fff',
                    bodyColor:       'rgba(255,255,255,0.75)',
                    padding:         12,
                    cornerRadius:    12,
                    callbacks: {
                        label: ctx => `${ctx.raw > 0 ? '+' : ''}${ctx.raw} g`
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        font:      { family: "'DM Sans', sans-serif", size: 11 },
                        color:     '#9CA3AF',
                        maxRotation: 35
                    }
                },
                y: {
                    grid: {
                        color:     'rgba(0,0,0,0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        font:      { family: "'DM Sans', sans-serif", size: 11 },
                        color:     '#9CA3AF',
                        callback:  v => v + ' g'
                    }
                }
            }
        }
    });
}