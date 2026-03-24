// Script pour mettre à jour toutes les alertes dans les pages

// LOGIN PAGE
function updateLoginPage() {
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const accountType = document.getElementById('accountType').value;

            const submitBtn = this.querySelector('.btn-primary');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Connexion...';
            submitBtn.disabled = true;

            setTimeout(() => {
                localStorage.setItem('isLoggedIn', 'true');
                localStorage.setItem('accountType', accountType);
                localStorage.setItem('userEmail', email);
                localStorage.setItem('userName', email.split('@')[0]);

                showSuccess('Connexion réussie !', `Bienvenue sur JobHub, ${email.split('@')[0]} !`);

                setTimeout(() => {
                    if (accountType === 'entreprise') {
                        window.location.href = 'dashboard-entreprise.html';
                    } else {
                        window.location.href = 'dashboard-candidat.html';
                    }
                }, 1500);
            }, 1500);
        });
    }
}

// INSCRIPTION PAGE
function updateInscriptionPage() {
    const inscriptionForm = document.getElementById('inscriptionForm');
    if (inscriptionForm) {
        inscriptionForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(inscriptionForm);
            const data = Object.fromEntries(formData);

            let isValid = true;
            const requiredFields = inscriptionForm.querySelectorAll('[required]');

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#ef4444';
                } else {
                    field.style.borderColor = '#e5e7eb';
                }
            });

            if (isValid) {
                const submitBtn = this.querySelector('.btn-primary');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Inscription en cours...';
                submitBtn.disabled = true;

                setTimeout(() => {
                    showSuccess('Inscription réussie !', 'Votre compte a été créé avec succès. Vous pouvez maintenant postuler aux offres !');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    inscriptionForm.reset();
                }, 1500);
            } else {
                showError('Champs manquants', 'Veuillez remplir tous les champs obligatoires.');
            }
        });
    }
}

// JOB DETAILS PAGE - Apply button
function updateJobDetailsPage() {
    const applyBtn = document.querySelector('.apply-section .btn-primary');
    if (applyBtn && !document.getElementById('inscriptionForm')) {
        applyBtn.addEventListener('click', async (e) => {
            e.preventDefault();

            const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';

            if (!isLoggedIn) {
                const confirmed = await showConfirm(
                    'Inscription requise',
                    'Vous devez être inscrit pour postuler à cette offre. Voulez-vous créer un compte maintenant ?'
                );

                if (confirmed) {
                    window.location.href = 'inscription.html';
                }
            } else {
                const originalText = applyBtn.textContent;
                applyBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi en cours...';
                applyBtn.disabled = true;

                setTimeout(() => {
                    showSuccess('Candidature envoyée !', 'Votre candidature a été envoyée avec succès. L\'entreprise examinera votre profil sous peu.');
                    applyBtn.innerHTML = '<i class="fas fa-check"></i> Candidature envoyée';
                    applyBtn.style.backgroundColor = '#10b981';
                }, 1500);
            }
        });
    }
}

// CONTACT PAGE
function updateContactPage() {
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const nom = document.getElementById('nom').value;
            const email = document.getElementById('email').value;
            const sujet = document.getElementById('sujet').value;
            const message = document.getElementById('message').value;

            if (!nom || !email || !sujet || !message) {
                showError('Champs manquants', 'Veuillez remplir tous les champs obligatoires (*).');
                return;
            }

            const submitBtn = this.querySelector('.btn-primary');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi en cours...';
            submitBtn.disabled = true;

            setTimeout(() => {
                showSuccess('Message envoyé !', `Merci ${nom} ! Nous avons bien reçu votre message. Notre équipe vous répondra dans les plus brefs délais à ${email}.`);
                this.reset();
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 1500);
        });
    }
}

// POSTER OFFRE PAGE
function updatePosterOffrePage() {
    const posterOffreForm = document.getElementById('posterOffreForm');
    if (posterOffreForm) {
        posterOffreForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const titre = document.getElementById('titre').value;
            const categorie = document.getElementById('categorie').value;
            const ville = document.getElementById('ville').value;
            const salaireMin = document.getElementById('salaireMin').value;
            const salaireMax = document.getElementById('salaireMax').value;

            if (!titre || !categorie || !ville || !salaireMin || !salaireMax) {
                showError('Champs manquants', 'Veuillez remplir tous les champs obligatoires (*).');
                return;
            }

            const submitBtn = this.querySelector('.btn-primary');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Publication en cours...';
            submitBtn.disabled = true;

            setTimeout(() => {
                showSuccess(
                    'Offre publiée !',
                    `Votre offre "${titre}" à ${ville} a été publiée avec succès ! Elle est maintenant visible par tous les candidats.`
                );
                setTimeout(() => {
                    window.location.href = 'dashboard-entreprise.html';
                }, 2000);
            }, 2000);
        });
    }
}

// PROFIL PAGES
function updateProfilPages() {
    const profilForm = document.getElementById('profilForm');
    if (profilForm) {
        profilForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = this.querySelector('.btn-primary');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';
            submitBtn.disabled = true;

            setTimeout(() => {
                showSuccess('Profil mis à jour !', 'Vos informations ont été mises à jour avec succès.');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 1500);
        });
    }
}

// LOGOUT FUNCTION
async function logout() {
    const confirmed = await showConfirm(
        'Déconnexion',
        'Êtes-vous sûr de vouloir vous déconnecter ?'
    );

    if (confirmed) {
        localStorage.removeItem('isLoggedIn');
        localStorage.removeItem('accountType');
        localStorage.removeItem('userEmail');
        localStorage.removeItem('userName');

        showSuccess('Déconnexion réussie', 'Vous avez été déconnecté avec succès. À bientôt !');

        setTimeout(() => {
            window.location.href = 'index.html';
        }, 1500);
    }
}

// DASHBOARD ACTIONS
function updateDashboardActions() {
    // Dashboard candidat - annuler candidature
    document.querySelectorAll('.btn-outline').forEach(btn => {
        if (btn.textContent.includes('Annuler') && btn.onclick && btn.onclick.toString().includes('Candidature annulée')) {
            btn.onclick = async () => {
                const confirmed = await showConfirm(
                    'Annuler la candidature',
                    'Êtes-vous sûr de vouloir annuler cette candidature ?'
                );
                if (confirmed) {
                    showSuccess('Candidature annulée', 'Votre candidature a été annulée avec succès.');
                }
            };
        }
    });
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    updateLoginPage();
    updateInscriptionPage();
    updateJobDetailsPage();
    updateContactPage();
    updatePosterOffrePage();
    updateProfilPages();
    updateDashboardActions();
});

// Make logout function global
window.logout = logout;
