// Auto-fermeture des messages flash après 5 secondes
document.addEventListener('DOMContentLoaded', function() {
    // Messages flash
    const flashMessages = document.querySelectorAll('.flash');
    flashMessages.forEach(flash => {
        setTimeout(() => {
            flash.style.transition = 'opacity 0.5s';
            flash.style.opacity = '0';
            setTimeout(() => flash.remove(), 500);
        }, 5000);
    });
    
    // Confirmation pour les actions destructives
    const confirmButtons = document.querySelectorAll('.btn-cancel, .btn-del, .btn-refuse');
    confirmButtons.forEach(btn => {
        if (!btn.hasAttribute('data-confirm-set')) {
            btn.setAttribute('data-confirm-set', 'true');
            btn.addEventListener('click', function(e) {
                if (!confirm('Êtes-vous sûr de vouloir effectuer cette action ?')) {
                    e.preventDefault();
                }
            });
        }
    });
    
    // Tooltip sur les badges
    const badges = document.querySelectorAll('.statut, .type-badge');
    badges.forEach(badge => {
        badge.setAttribute('title', badge.textContent.trim());
    });
    
    // Sidebar active link
    const currentPath = window.location.pathname;
    const sidebarLinks = document.querySelectorAll('.sidebar-nav a');
    sidebarLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });
});