import React, { useEffect, useRef } from 'react';

export default function SiteFooter({ children }) {
    const footerRef = useRef(null);
    const scarfaceRef = useRef(null);

    useEffect(() => {
        const footer = footerRef.current;
        if (!footer) return;

        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (prefersReducedMotion || !('IntersectionObserver' in window)) {
            return;
        }

        footer.classList.add('js-anim');
        const items = Array.from(footer.querySelectorAll('[data-reveal]'));
        const scarfaceSection = scarfaceRef.current;

        // Parallax scroll animation observer - use scroll event for smoother effect
        let ticking = false;
        
        const updateParallax = () => {
            const rect = footer.getBoundingClientRect();
            const windowHeight = window.innerHeight;
            
            if (rect.top < windowHeight && rect.bottom > 0) {
                const scrollProgress = Math.min(1, Math.max(0, 1 - (rect.top / windowHeight)));
                const translateY = scrollProgress * 40;
                footer.style.transform = `translateY(-${translateY}px)`;
            }
            ticking = false;
        };

        const onScroll = () => {
            if (!ticking) {
                window.requestAnimationFrame(updateParallax);
                ticking = true;
            }
        };

        window.addEventListener('scroll', onScroll, { passive: true });
        updateParallax(); // Initial call

        // Create separate observers for footer items
        const footerObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                    } else {
                        entry.target.classList.remove('is-visible');
                    }
                });
            },
            { threshold: 0.15 }
        );

        items.forEach((item) => footerObserver.observe(item));

        // Scarface section observer (reset animation on exit)
        const scarfaceObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                    } else {
                        entry.target.classList.remove('is-visible');
                    }
                });
            },
            { threshold: 0.1 }
        );

        if (scarfaceSection) {
            scarfaceObserver.observe(scarfaceSection);
        }

        // Scroll to top handler
        const topBtn = footer.querySelector('.footer-top');
        if (topBtn) {
            topBtn.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: prefersReducedMotion ? 'auto' : 'smooth',
                });
            });
        }

        return () => {
            window.removeEventListener('scroll', onScroll);
            items.forEach((item) => footerObserver.unobserve(item));
            if (scarfaceSection) scarfaceObserver.unobserve(scarfaceSection);
        };
    }, []);

    return (
        <footer ref={footerRef} className="site-footer bg-black text-white" style={{ transition: 'transform 0.1s linear', willChange: 'transform' }}>
            {children}
        </footer>
    );
}



