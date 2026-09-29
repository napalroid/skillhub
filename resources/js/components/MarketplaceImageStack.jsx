import { useCallback, useEffect, useRef, useState } from 'react';
import { createRoot } from 'react-dom/client';
import { useReducedMotion } from 'framer-motion';

function MarketplaceImageStack({ slides }) {
    const [activeIndex, setActiveIndex] = useState(0);
    const [isPaused, setIsPaused] = useState(false);
    const reduceMotion = useReducedMotion();
    const previousActiveIndex = useRef(0);
    const advance = useCallback(() => setActiveIndex((current) => (current + 1) % slides.length), [slides.length]);

    useEffect(() => {
        if (reduceMotion || isPaused || slides.length < 2) return undefined;
        const timer = window.setInterval(advance, 8000);
        return () => window.clearInterval(timer);
    }, [advance, isPaused, reduceMotion, slides.length]);

    useEffect(() => {
        previousActiveIndex.current = activeIndex;
    }, [activeIndex]);

    const queue = Array.from({ length: slides.length }, (_, position) => {
        const index = (activeIndex + position) % slides.length;
        return { ...slides[index], position, index };
    });

    return <div className="marketplace-image-stack" aria-label="Sorotan karya SkillHub" onFocusCapture={(event) => {
        if (!event.target.classList.contains('marketplace-image-stack__pause')) setIsPaused(true);
    }}>
        <div
            className="marketplace-image-stack__frame"
            role="button"
            tabIndex="0"
            aria-label="Lihat sorotan berikutnya"
            onClick={() => { setIsPaused(true); advance(); }}
            onKeyDown={(event) => {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    setIsPaused(true);
                    advance();
                }
            }}
        >
            {queue.slice().reverse().map((slide) => {
                const depth = Math.min(slide.position, 3);
                const previousPosition = (slide.index - previousActiveIndex.current + slides.length) % slides.length;
                const direction = slide.position < previousPosition ? 'forward' : 'backward';
                const style = {
                    opacity: slide.position === 0 ? 1 : 0.62 - depth * 0.1,
                    transform: reduceMotion ? 'none' : `translate3d(0, ${depth * 20}px, 0) scale(${1 - depth * 0.028})`,
                    zIndex: slides.length - depth,
                    transitionDuration: reduceMotion ? '200ms' : '700ms',
                };

                return <figure key={slide.image} className={`marketplace-image-stack__card marketplace-image-stack__card--depth-${depth} marketplace-image-stack__card--${direction}`} style={style}>
                    <img src={slide.image} alt={slide.title} decoding="async" fetchPriority={slide.position === 0 ? 'high' : 'auto'} loading={slide.position === 0 ? 'eager' : 'lazy'} />
                    <figcaption>{slide.tag}</figcaption>
                </figure>;
            })}
        </div>
        <div className="marketplace-image-stack__controls">
            <button type="button" className="marketplace-image-stack__pause" onClick={() => setIsPaused((paused) => !paused)} aria-label={isPaused ? 'Lanjutkan putar sorotan' : 'Jeda putar sorotan'} aria-pressed={isPaused}>{isPaused ? 'Lanjutkan' : 'Jeda'}</button>
            <button type="button" className="marketplace-image-stack__next" onClick={() => { setIsPaused(true); advance(); }} aria-label="Lihat sorotan berikutnya">
                <svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><path d="m9 18 6-6-6-6" /></svg>
            </button>
        </div>
    </div>;
}

const mount = document.getElementById('marketplace-image-stack');
if (mount) {
    try { createRoot(mount).render(<MarketplaceImageStack slides={JSON.parse(mount.dataset.slides || '[]')} />); }
    catch (error) { console.error('Marketplace image stack could not be initialized.', error); }
}
