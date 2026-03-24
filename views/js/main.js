// =========================
// MOBILE MENU TOGGLE
// =========================
const hamburger = document.querySelector('.hamburger');
const navMenu = document.querySelector('.nav-menu');
const navButtons = document.querySelector('.nav-buttons');

if (hamburger) {
    hamburger.addEventListener('click', () => {
        hamburger.classList.toggle('active');
        navMenu.classList.toggle('active');
        navButtons.classList.toggle('active');
    });
}

// =========================
// SMOOTH SCROLLING
// =========================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// =========================
// ACTIVE NAV LINK ON SCROLL
// =========================
const sections = document.querySelectorAll('section[id]');
const navLinks = document.querySelectorAll('.nav-link');

function activateNavLink() {
    let scrollY = window.pageYOffset;

    sections.forEach(section => {
        const sectionHeight = section.offsetHeight;
        const sectionTop = section.offsetTop - 100;
        const sectionId = section.getAttribute('id');

        if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${sectionId}`) {
                    link.classList.add('active');
                }
            });
        }
    });
}

window.addEventListener('scroll', activateNavLink);

// =========================
// JOB FILTERS
// =========================
const filterBtns = document.querySelectorAll('.filter-btn');
const jobCards = document.querySelectorAll('.job-card');

filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        // Remove active class from all buttons
        filterBtns.forEach(b => b.classList.remove('active'));
        // Add active class to clicked button
        btn.classList.add('active');

        const filter = btn.getAttribute('data-filter');

        jobCards.forEach(card => {
            const category = card.getAttribute('data-category');

            if (filter === 'all' || category === filter) {
                card.style.display = 'block';
                card.style.opacity = '1';
                card.style.animation = 'fadeIn 0.5s ease';
            } else {
                card.style.display = 'none';
            }
        });
    });
});

// =========================
// SEARCH FUNCTIONALITY
// =========================
const searchInput = document.getElementById('searchInput');
const locationInput = document.getElementById('locationInput');
const searchBtn = document.querySelector('.btn-search');

if (searchBtn) {
    searchBtn.addEventListener('click', () => {
        const searchTerm = searchInput.value.toLowerCase();
        const location = locationInput.value.toLowerCase();

        jobCards.forEach(card => {
            const jobTitle = card.querySelector('.job-title').textContent.toLowerCase();
            const jobLocation = card.querySelector('.job-details span').textContent.toLowerCase();
            const companyName = card.querySelector('.company-name').textContent.toLowerCase();

            const matchesSearch = jobTitle.includes(searchTerm) || companyName.includes(searchTerm);
            const matchesLocation = location === '' || jobLocation.includes(location);

            if (matchesSearch && matchesLocation) {
                card.style.display = 'block';
                card.style.opacity = '1';
                card.style.animation = 'fadeIn 0.5s ease';
            } else {
                card.style.display = 'none';
            }
        });

        // Scroll to jobs section
        document.getElementById('jobs').scrollIntoView({ behavior: 'smooth' });
    });
}

// Enable search on Enter key
if (searchInput) {
    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            searchBtn.click();
        }
    });
}

if (locationInput) {
    locationInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            searchBtn.click();
        }
    });
}

// =========================
// CATEGORY CARDS ANIMATION
// =========================
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.animation = 'fadeInUp 0.6s ease forwards';
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

// Observe category cards
document.querySelectorAll('.category-card').forEach((card, index) => {
    card.style.opacity = '0';
    card.style.animationDelay = `${index * 0.1}s`;
    observer.observe(card);
});

// Observe job cards
document.querySelectorAll('.job-card').forEach((card, index) => {
    card.style.opacity = '0';
    card.style.animation = `fadeInUp 0.6s ease forwards ${index * 0.05}s`;
});

// =========================
// LOAD MORE JOBS
// =========================
const loadMoreBtn = document.querySelector('.load-more .btn');

if (loadMoreBtn) {
    loadMoreBtn.addEventListener('click', () => {
        // Simulate loading more jobs
        loadMoreBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Chargement...';

        setTimeout(() => {
            loadMoreBtn.innerHTML = 'Voir Plus d\'Offres';
            showInfo('Bientôt disponible', 'De nouvelles offres seront ajoutées prochainement. Revenez bientôt !');
        }, 1000);
    });
}

// =========================
// HEADER SHADOW ON SCROLL
// =========================
const header = document.querySelector('.header');

window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
        header.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
    } else {
        header.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1)';
    }
});

// =========================
// ACCOUNT TYPE SELECTION (for inscription page)
// =========================
const accountTypeCards = document.querySelectorAll('.account-type-card');

accountTypeCards.forEach(card => {
    card.addEventListener('click', () => {
        accountTypeCards.forEach(c => c.classList.remove('active'));
        card.classList.add('active');

        const accountType = card.getAttribute('data-type');

        // Show/hide form fields based on account type
        const companyFields = document.querySelectorAll('.company-field');
        const candidateFields = document.querySelectorAll('.candidate-field');

        if (accountType === 'entreprise') {
            companyFields.forEach(field => field.style.display = 'block');
            candidateFields.forEach(field => field.style.display = 'none');
        } else {
            companyFields.forEach(field => field.style.display = 'none');
            candidateFields.forEach(field => field.style.display = 'block');
        }
    });
});

// =========================
// FORM VALIDATION
// =========================
const inscriptionForm = document.getElementById('inscriptionForm');

if (inscriptionForm) {
    inscriptionForm.addEventListener('submit', (e) => {
        e.preventDefault();

        // Get form values
        const formData = new FormData(inscriptionForm);
        const data = Object.fromEntries(formData);

        // Simple validation
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
            // Simulate form submission
            const submitBtn = inscriptionForm.querySelector('.btn-primary');
            const originalText = submitBtn.textContent;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Inscription en cours...';
            submitBtn.disabled = true;

            setTimeout(() => {
                alert('Inscription réussie ! Vous pouvez maintenant postuler aux offres.');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                inscriptionForm.reset();
            }, 1500);
        } else {
            alert('Veuillez remplir tous les champs obligatoires.');
        }
    });
}

// =========================
// JOB APPLICATION (for job details page)
// =========================
const applyBtn = document.querySelector('.apply-section .btn-primary');

if (applyBtn && !inscriptionForm) {
    applyBtn.addEventListener('click', (e) => {
        e.preventDefault();

        // Check if user is logged in (simulation)
        const isLoggedIn = false; // Change to true to simulate logged in user

        if (!isLoggedIn) {
            if (confirm('Vous devez être inscrit pour postuler. Voulez-vous créer un compte ?')) {
                window.location.href = 'inscription.html';
            }
        } else {
            const originalText = applyBtn.textContent;
            applyBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi en cours...';
            applyBtn.disabled = true;

            setTimeout(() => {
                alert('Votre candidature a été envoyée avec succès !');
                applyBtn.innerHTML = '<i class="fas fa-check"></i> Candidature envoyée';
                applyBtn.style.backgroundColor = '#10b981';
            }, 1500);
        }
    });
}

// =========================
// ANIMATE NUMBERS IN HERO STATS
// =========================
function animateNumber(element, target, duration = 2000) {
    const start = 0;
    const increment = target / (duration / 16);
    let current = start;

    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            element.textContent = target.toLocaleString('fr-FR');
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(current).toLocaleString('fr-FR');
        }
    }, 16);
}

// Animate stats when they come into view
const stats = document.querySelectorAll('.stat h3');
let statsAnimated = false;

const statsObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting && !statsAnimated) {
            stats.forEach((stat, index) => {
                const target = parseInt(stat.textContent.replace(/,/g, ''));
                setTimeout(() => {
                    animateNumber(stat, target);
                }, index * 200);
            });
            statsAnimated = true;
        }
    });
}, { threshold: 0.5 });

const heroStats = document.querySelector('.hero-stats');
if (heroStats) {
    statsObserver.observe(heroStats);
}

// =========================
// TOOLTIP FOR JOB CARDS
// =========================
jobCards.forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-8px)';
    });

    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
    });
});

// =========================
// CONSOLE MESSAGE
// =========================
console.log('%c🚀 JobHub - Votre Avenir Commence Ici', 'color: #3b82f6; font-size: 20px; font-weight: bold;');
console.log('%cBienvenue sur notre plateforme de recherche d\'emploi !', 'color: #6b7280; font-size: 14px;');
