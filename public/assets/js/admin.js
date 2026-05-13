// ============================================
// ADMIN JS GLOBAL — Regime Expert 2026
// Loading overlay + Toast + Offline support
// ============================================

'use strict';

// ── Loading Bar + Overlay ──────────────────
let _loaderCount  = 0;
let _loadingBar   = null;
let _barInterval  = null;
let _barProgress  = 0;

function _ensureBar() {
    if (!_loadingBar) {
        _loadingBar = document.createElement('div');
        _loadingBar.className = 'loading-bar';
        document.body.appendChild(_loadingBar);
    }
    return _loadingBar;
}

function _startBar() {
    const bar = _ensureBar();
    bar.style.display = 'block';
    _barProgress = 0;
    bar.style.width = '0%';
    bar.style.transition = 'none';

    clearInterval(_barInterval);
    _barInterval = setInterval(() => {
        if (_barProgress < 80) {
            _barProgress += Math.random() * 8;
            bar.style.transition = 'width 0.4s ease-out';
            bar.style.width = Math.min(_barProgress, 80) + '%';
        }
    }, 400);
}

function _finishBar() {
    clearInterval(_barInterval);
    const bar = _ensureBar();
    bar.style.transition = 'width 0.2s ease-out';
    bar.style.width = '100%';
    setTimeout(() => {
        bar.style.transition = 'opacity 0.3s';
        bar.style.opacity = '0';
        setTimeout(() => {
            bar.style.display = 'none';
            bar.style.opacity = '1';
            bar.style.width   = '0%';
        }, 300);
    }, 300);
}

function showLoader(message) {
    _loaderCount++;
    _startBar();

    let overlay = document.getElementById('loadingOverlay');
    if (!overlay) {
        overlay = _createOverlay();
        document.body.appendChild(overlay);
    }

    if (message) {
        const sub = overlay.querySelector('.loader-sub');
        if (sub) sub.textContent = message;
    }

    overlay.classList.add('show');
    overlay.style.display = 'flex';
}

function hideLoader() {
    _loaderCount = Math.max(0, _loaderCount - 1);
    if (_loaderCount > 0) return;

    _finishBar();

    const overlay = document.getElementById('loadingOverlay');
    if (!overlay) return;

    overlay.classList.remove('show');
    setTimeout(() => {
        if (!overlay.classList.contains('show')) {
            overlay.style.display = 'none';
        }
    }, 250);
}

function _createOverlay() {
    const div = document.createElement('div');
    div.id = 'loadingOverlay';
    div.className = 'loading-overlay';
    div.innerHTML = `
        <div class="loader-card">
            <div class="loader-ring">
                <svg viewBox="0 0 56 56">
                    <circle class="track" cx="28" cy="28" r="22"/>
                    <circle class="fill"  cx="28" cy="28" r="22"/>
                </svg>
            </div>
            <div class="loader-text">Chargement…</div>
            <div class="loader-sub">Veuillez patienter</div>
        </div>`;
    return div;
}

// ── Toast Notifications ────────────────────
let _toastContainer = null;

function _ensureToastContainer() {
    if (!_toastContainer) {
        _toastContainer = document.createElement('div');
        _toastContainer.className = 'toast-container';
        document.body.appendChild(_toastContainer);
    }
    return _toastContainer;
}

/**
 * showNotification(type, title, message)
 * type: 'success' | 'error' | 'warning' | 'info'
 */
function showNotification(type, title, message) {
    const icons = {
        success: 'fa-check',
        error:   'fa-times',
        warning: 'fa-exclamation',
        info:    'fa-info'
    };

    const container = _ensureToastContainer();
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
        <div class="toast-icon">
            <i class="fas ${icons[type] || 'fa-info'}"></i>
        </div>
        <div class="toast-content">
            <div class="toast-title">${title}</div>
            ${message ? `<div class="toast-message">${message}</div>` : ''}
        </div>`;

    container.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('out');
        setTimeout(() => toast.remove(), 250);
    }, 4000);
}

// Legacy alias: showNotification('success', msg) → also works with 2 args
const _origShowNotification = showNotification;
window.showNotification = function(type, titleOrMsg, message) {
    // If called with 2 args (old style), treat second as title only
    _origShowNotification(type, titleOrMsg, message || '');
};

// ── Offline Detection ──────────────────────
function _handleOffline() {
    showNotification('warning', 'Hors connexion', 'Vérifiez votre connexion internet');
    const banner = document.getElementById('offlineBanner');
    if (banner) banner.style.display = 'flex';
}

function _handleOnline() {
    showNotification('success', 'Reconnecté', 'La connexion est rétablie');
    const banner = document.getElementById('offlineBanner');
    if (banner) banner.style.display = 'none';
}

// Inject offline banner
function _injectOfflineBanner() {
    const banner = document.createElement('div');
    banner.id = 'offlineBanner';
    banner.style.cssText = `
        display: none; position: fixed; top: 0; left: 0; right: 0;
        background: #F59E0B; color: #fff; z-index: 10000;
        padding: 10px 20px; text-align: center;
        align-items: center; justify-content: center; gap: 10px;
        font-size: 13.5px; font-weight: 600; font-family: 'DM Sans', sans-serif;
    `;
    banner.innerHTML = `<i class="fas fa-wifi-slash"></i> Mode hors connexion — Certaines fonctions sont limitées`;

    if (!navigator.onLine) banner.style.display = 'flex';
    document.body.appendChild(banner);
}

// ── AJAX Wrapper with Offline Fallback ────
function adminAjax(options) {
    if (!navigator.onLine) {
        hideLoader();
        showNotification('warning', 'Hors connexion', options.offlineMessage || 'Pas de connexion internet');
        if (typeof options.offlineCallback === 'function') options.offlineCallback();
        return;
    }

    const defaults = {
        method:     'GET',
        dataType:   'json',
        beforeSend: showLoader,
        complete:   hideLoader,
        error: function(xhr) {
            let msg = 'Une erreur est survenue';
            try {
                const r = JSON.parse(xhr.responseText);
                msg = r.message || msg;
            } catch(e) {}
            showNotification('error', 'Erreur', msg);
        }
    };

    $.ajax(Object.assign({}, defaults, options));
}

// ── Page Transition ────────────────────────
function navigateTo(url) {
    if (!url || url === '#') return;
    showLoader('Navigation…');
    setTimeout(() => { window.location.href = url; }, 250);
}

// ── Delete Confirm Helper ─────────────────
function confirmDelete(message, callback) {
    // Simple custom confirm (could be upgraded to a modal)
    if (confirm(message || 'Supprimer définitivement cet élément ?')) {
        callback();
    }
}

// ── Document Ready ─────────────────────────
$(document).ready(function() {
    _injectOfflineBanner();

    // Offline/online events
    window.addEventListener('offline', _handleOffline);
    window.addEventListener('online',  _handleOnline);

    // Auto-hide alerts
    setTimeout(() => {
        $('.alert').fadeOut(600);
    }, 4500);

    // Smooth page transitions (skip anchors, externals, javascript:)
    $(document).on('click', 'a[href]', function(e) {
        const href = $(this).attr('href');
        if (
            !href ||
            href === '#' ||
            href.startsWith('javascript') ||
            href.startsWith('http') ||
            href.startsWith('//') ||
            $(this).hasClass('no-loader') ||
            $(this).attr('target') === '_blank' ||
            $(this).hasClass('logout-btn')
        ) return;

        e.preventDefault();
        navigateTo(href);
    });
});

// Expose globals
window.showLoader        = showLoader;
window.hideLoader        = hideLoader;
window.adminAjax         = adminAjax;
window.navigateTo        = navigateTo;
window.confirmDelete     = confirmDelete;