'use strict';

// ── Mobile Menu ──────────────────────────────────────────────────
const hamburger = document.querySelector('.hamburger');
const navMenu = document.querySelector('.nav-menu');
const navButtons = document.querySelector('.nav-buttons');
const header = document.querySelector('.header');

if (hamburger) {
    hamburger.addEventListener('click', () => {
        const isOpen = hamburger.classList.toggle('active');
        navMenu?.classList.toggle('active', isOpen);
        navButtons?.classList.toggle('active', isOpen);
        document.body.style.overflow = isOpen ? 'hidden' : '';
    });

    // Close menu when clicking a nav link
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
            hamburger.classList.remove('active');
            navMenu?.classList.remove('active');
            navButtons?.classList.remove('active');
            document.body.style.overflow = '';
        });
    });
}

// ── Smooth Scroll ────────────────────────────────────────────────
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', e => {
        const href = anchor.getAttribute('href');
        if (!href || href === '#') return;
        const target = document.querySelector(href);
        if (!target) return;
        e.preventDefault();
        const offset = header ? header.offsetHeight + 20 : 80;
        const top = target.getBoundingClientRect().top + window.pageYOffset - offset;
        window.scrollTo({ top, behavior: 'smooth' });
    });
});

// ── Header Scroll Effect ─────────────────────────────────────────
if (header) {
    let lastScroll = 0;
    const onScroll = () => {
        const y = window.scrollY;
        header.classList.toggle('header-scrolled', y > 10);
        lastScroll = y;
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
}

// ── Scroll Reveal ────────────────────────────────────────────────
const revealElements = document.querySelectorAll(
    '.category-card, .job-card, .content-card, .feature-card, .trust-pill, ' +
    '.metric-card-premium, .surface-card, .application-item, .info-item, ' +
    '.pipeline-item, .quick-link-card, .inline-action-card'
);

if ('IntersectionObserver' in window && revealElements.length) {
    const revealObserver = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            entry.target.style.transitionProperty = 'opacity, transform';
            entry.target.style.transitionDuration = '0.6s';
            entry.target.style.transitionTimingFunction = 'cubic-bezier(0.22, 1, 0.36, 1)';
            entry.target.classList.add('is-visible');
            obs.unobserve(entry.target);
        });
    }, { threshold: 0.08, rootMargin: '0px 0px -30px 0px' });

    revealElements.forEach((el, i) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(24px)';
        el.style.transitionDelay = `${Math.min(i * 0.04, 0.24)}s`;
        revealObserver.observe(el);
    });
}

// ── Flash Message Auto-dismiss ───────────────────────────────────
document.querySelectorAll('.flash').forEach(flash => {
    setTimeout(() => {
        flash.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
        flash.style.opacity = '0';
        flash.style.transform = 'translateY(-10px)';
        setTimeout(() => flash.remove(), 400);
    }, 5000);
});

// ── Active Nav Link ──────────────────────────────────────────────
const currentPath = window.location.pathname;
document.querySelectorAll('.nav-link').forEach(link => {
    const href = link.getAttribute('href');
    if (href && currentPath.includes(href.replace('#', '').split('?')[0]) && href !== '/index.php') {
        link.classList.add('active');
    }
});

// ── Number Counter Animation ─────────────────────────────────────
const counters = document.querySelectorAll('.panel-big-number, .metric-copy strong, .stat h3, .pipeline-count');
if ('IntersectionObserver' in window && counters.length) {
    const counterObserver = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            const el = entry.target;
            const text = el.textContent.trim();
            const match = text.match(/^(\d+)/);
            if (!match) return;
            const target = parseInt(match[1], 10);
            const suffix = text.replace(match[1], '');
            const duration = 800;
            const start = performance.now();
            const animate = now => {
                const progress = Math.min((now - start) / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                el.textContent = Math.round(target * eased) + suffix;
                if (progress < 1) requestAnimationFrame(animate);
            };
            requestAnimationFrame(animate);
            obs.unobserve(el);
        });
    }, { threshold: 0.5 });

    counters.forEach(el => counterObserver.observe(el));
}

// ── Tooltip for truncated text ───────────────────────────────────
document.querySelectorAll('[data-tooltip]').forEach(el => {
    el.style.cursor = 'help';
});

// ── Confirm Delete Dialogs ───────────────────────────────────────
document.querySelectorAll('.confirm-delete').forEach(form => {
    form.addEventListener('submit', e => {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ? Cette action est irréversible.')) {
            e.preventDefault();
        }
    });
});
