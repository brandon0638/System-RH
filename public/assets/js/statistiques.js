// statistiques.js - Scripts pour les graphiques Chart.js

document.addEventListener('DOMContentLoaded', function() {
    // Graphique par type de congé (Pie Chart)
    var typeCanvas = document.getElementById('typeChart');
    if (typeCanvas) {
        var typeData = typeCanvas.getAttribute('data-data');
        var typeLabels = typeCanvas.getAttribute('data-labels');
        
        if (typeData && typeLabels) {
            new Chart(typeCanvas.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: JSON.parse(typeLabels),
                    datasets: [{
                        data: JSON.parse(typeData),
                        backgroundColor: ['#2d5a3d', '#1a4f7a', '#5a2d82'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { font: { family: 'DM Sans' } }
                        }
                    }
                }
            });
        }
    }
    
    // Graphique par mois (Bar Chart)
    var moisCanvas = document.getElementById('moisChart');
    if (moisCanvas) {
        var moisData = moisCanvas.getAttribute('data-data');
        var moisLabels = moisCanvas.getAttribute('data-labels');
        
        if (moisData && moisLabels) {
            new Chart(moisCanvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: JSON.parse(moisLabels),
                    datasets: [{
                        label: 'Jours de congé',
                        data: JSON.parse(moisData),
                        backgroundColor: '#2d5a3d',
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1 } }
                    }
                }
            });
        }
    }
    
    // Graphique par jour de semaine (Bar Chart)
    var semaineCanvas = document.getElementById('semaineChart');
    if (semaineCanvas) {
        var semaineData = semaineCanvas.getAttribute('data-data');
        var semaineLabels = semaineCanvas.getAttribute('data-labels');
        
        if (semaineData && semaineLabels) {
            new Chart(semaineCanvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: JSON.parse(semaineLabels),
                    datasets: [{
                        label: 'Jours de congé',
                        data: JSON.parse(semaineData),
                        backgroundColor: '#3d7a52',
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1 } }
                    }
                }
            });
        }
    }
    
    // Graphique par statut (Doughnut Chart)
    var statutCanvas = document.getElementById('statutChart');
    if (statutCanvas) {
        var statutData = statutCanvas.getAttribute('data-data');
        var statutLabels = statutCanvas.getAttribute('data-labels');
        
        if (statutData && statutLabels) {
            new Chart(statutCanvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: JSON.parse(statutLabels),
                    datasets: [{
                        data: JSON.parse(statutData),
                        backgroundColor: ['#1e6b3f', '#b8750a', '#c0392b', '#7a8f80'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { font: { family: 'DM Sans' } }
                        }
                    }
                }
            });
        }
    }
});