// =========================
// CUSTOM ALERT SYSTEM
// =========================

// Create toast container
let toastContainer = null;

function initToastContainer() {
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container';
        document.body.appendChild(toastContainer);
    }
}

// Toast Notification
function showToast(title, message, type = 'info', duration = 4000) {
    initToastContainer();

    const icons = {
        success: '<i class="fas fa-check-circle"></i>',
        error: '<i class="fas fa-times-circle"></i>',
        warning: '<i class="fas fa-exclamation-triangle"></i>',
        info: '<i class="fas fa-info-circle"></i>'
    };

    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
        <div class="toast-icon">${icons[type]}</div>
        <div class="toast-content">
            <div class="toast-title">${title}</div>
            <div class="toast-message">${message}</div>
        </div>
        <button class="toast-close">&times;</button>
    `;

    toastContainer.appendChild(toast);

    // Close button
    toast.querySelector('.toast-close').addEventListener('click', () => {
        removeToast(toast);
    });

    // Auto remove
    if (duration > 0) {
        setTimeout(() => {
            removeToast(toast);
        }, duration);
    }
}

function removeToast(toast) {
    toast.style.animation = 'slideOutRight 0.3s ease forwards';
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 300);
}

// Custom Alert (replaces window.alert)
function showAlert(options) {
    return new Promise((resolve) => {
        const {
            title = 'Information',
            message = '',
            type = 'info', // success, error, warning, info, question
            confirmText = 'OK',
            showCancel = false,
            cancelText = 'Annuler'
        } = options;

        const icons = {
            success: '<i class="fas fa-check-circle"></i>',
            error: '<i class="fas fa-times-circle"></i>',
            warning: '<i class="fas fa-exclamation-triangle"></i>',
            info: '<i class="fas fa-info-circle"></i>',
            question: '<i class="fas fa-question-circle"></i>'
        };

        const overlay = document.createElement('div');
        overlay.className = 'custom-alert-overlay';
        overlay.innerHTML = `
            <div class="custom-alert">
                <div class="custom-alert-header">
                    <div class="custom-alert-icon ${type}">${icons[type]}</div>
                    <h3 class="custom-alert-title">${title}</h3>
                </div>
                <div class="custom-alert-message">${message}</div>
                <div class="custom-alert-buttons">
                    ${showCancel ? `<button class="custom-alert-btn custom-alert-btn-secondary" data-action="cancel">${cancelText}</button>` : ''}
                    <button class="custom-alert-btn custom-alert-btn-primary" data-action="confirm">${confirmText}</button>
                </div>
            </div>
        `;

        document.body.appendChild(overlay);

        // Handle button clicks
        overlay.querySelectorAll('.custom-alert-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const action = btn.dataset.action;
                overlay.style.animation = 'fadeOut 0.3s ease forwards';
                setTimeout(() => {
                    document.body.removeChild(overlay);
                    resolve(action === 'confirm');
                }, 300);
            });
        });

        // Close on overlay click
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                overlay.style.animation = 'fadeOut 0.3s ease forwards';
                setTimeout(() => {
                    document.body.removeChild(overlay);
                    resolve(false);
                }, 300);
            }
        });
    });
}

// Confirm Dialog
async function showConfirm(title, message) {
    return await showAlert({
        title: title,
        message: message,
        type: 'question',
        confirmText: 'Confirmer',
        showCancel: true,
        cancelText: 'Annuler'
    });
}

// Success Alert
function showSuccess(title, message) {
    showToast(title, message, 'success');
}

// Error Alert
function showError(title, message) {
    showToast(title, message, 'error', 6000);
}

// Warning Alert
function showWarning(title, message) {
    showToast(title, message, 'warning', 5000);
}

// Info Alert
function showInfo(title, message) {
    showToast(title, message, 'info');
}

// Loading Toast
function showLoading(message = 'Chargement en cours...') {
    initToastContainer();

    const toast = document.createElement('div');
    toast.className = 'toast info';
    toast.id = 'loading-toast';
    toast.innerHTML = `
        <div class="toast-icon"><i class="fas fa-spinner fa-spin"></i></div>
        <div class="toast-content">
            <div class="toast-title">Veuillez patienter</div>
            <div class="toast-message">${message}</div>
        </div>
    `;

    toastContainer.appendChild(toast);
    return toast;
}

function hideLoading() {
    const loadingToast = document.getElementById('loading-toast');
    if (loadingToast) {
        removeToast(loadingToast);
    }
}

// Add fadeOut animation to CSS if not exists
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeOut {
        from {
            opacity: 1;
        }
        to {
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
