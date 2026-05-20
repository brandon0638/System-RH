// calendar.js - Script pour le calendrier des congés

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    if (!calendarEl) return; // Sortir si l'élément n'existe pas
    
    // Récupérer les événements depuis l'attribut data
    var evenements = [];
    var eventsData = calendarEl.getAttribute('data-events');
    if (eventsData) {
        try {
            evenements = JSON.parse(eventsData);
        } catch(e) {
            console.error('Erreur parsing événements:', e);
        }
    }
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'fr',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: {
            today: "Aujourd'hui",
            month: 'Mois',
            week: 'Semaine',
            day: 'Jour'
        },
        events: evenements,
        eventDisplay: 'block',
        height: 'auto',
        slotMinTime: '08:00:00',
        slotMaxTime: '20:00:00',
        allDaySlot: true,
        
        eventMouseEnter: function(info) {
            const event = info.event;
            const motif = event.extendedProps.motif || 'Aucun motif';
            const nbJours = event.extendedProps.nb_jours || 0;
            
            let tooltip = document.getElementById('fc-tooltip');
            if (!tooltip) {
                tooltip = document.createElement('div');
                tooltip.id = 'fc-tooltip';
                tooltip.className = 'fc-event-tooltip';
                document.body.appendChild(tooltip);
            }
            
            tooltip.style.display = 'block';
            tooltip.style.position = 'absolute';
            tooltip.style.left = (info.jsEvent.pageX + 10) + 'px';
            tooltip.style.top = (info.jsEvent.pageY - 30) + 'px';
            tooltip.innerHTML = `
                <strong>${event.title.split(' : ')[0]}</strong><br>
                Dates: ${event.start.toLocaleDateString('fr-FR')}${event.end ? ' → ' + new Date(event.end).toLocaleDateString('fr-FR') : ''}<br>
                Motif: ${motif}<br>
                Duree: ${nbJours} jour(s)
            `;
        },
        eventMouseLeave: function() {
            const tooltip = document.getElementById('fc-tooltip');
            if (tooltip) {
                tooltip.style.display = 'none';
            }
        },
        eventClick: function(info) {
            const event = info.event;
            const motif = event.extendedProps.motif || 'Aucun motif';
            const nbJours = event.extendedProps.nb_jours || 0;
            
            alert(`
Details du congé
--------------------------------
Type: ${event.extendedProps.type || event.title.split(' : ')[0]}
Debut: ${event.start.toLocaleDateString('fr-FR')}
Fin: ${event.end ? new Date(event.end).toLocaleDateString('fr-FR') : event.start.toLocaleDateString('fr-FR')}
Duree: ${nbJours} jour(s)
Motif: ${motif}
            `);
        }
    });
    
    calendar.render();
});