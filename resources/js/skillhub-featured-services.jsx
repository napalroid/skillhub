import React, { useState } from 'react';
import { createRoot } from 'react-dom/client';
import { AnimatePresence, motion, useReducedMotion } from 'framer-motion';

const mountPoint = document.getElementById('skillhub-featured-services');
const dataNode = document.getElementById('skillhub-featured-services-data');

function getServices() {
    try {
        return dataNode?.textContent ? JSON.parse(dataNode.textContent) : [];
    } catch {
        return [];
    }
}

function ServiceProduct({ service, index }) {
    const [isHovered, setIsHovered] = useState(false);
    const reduceMotion = useReducedMotion();
    const hasPortfolio = service.portfolios.length > 0;
    const previewImage = isHovered && hasPortfolio ? service.portfolios[0] : service.image;

    return <motion.article
        className={`service-product ${isHovered ? 'is-hovered' : ''}`}
        initial={reduceMotion ? false : { opacity: 0, y: 24 }}
        whileInView={{ opacity: 1, y: 0 }}
        viewport={{ once: true, amount: .16 }}
        transition={{ duration: .45, delay: reduceMotion ? 0 : index * .05, ease: [0.16, 1, 0.3, 1] }}
        onMouseEnter={() => setIsHovered(true)}
        onMouseLeave={() => setIsHovered(false)}
        onFocus={() => setIsHovered(true)}
        onBlur={() => setIsHovered(false)}
    >
        <a href={service.url} className="service-product-link">
            <div className="service-product-media">
                <AnimatePresence mode="wait">
                    <motion.img
                        key={previewImage}
                        src={previewImage}
                        alt={`${isHovered && hasPortfolio ? 'Portofolio' : 'Gambar jasa'} ${service.title}`}
                        className="service-product-image"
                        initial={reduceMotion ? false : { opacity: 0, scale: 1.025 }}
                        animate={{ opacity: 1, scale: 1 }}
                        exit={reduceMotion ? undefined : { opacity: 0, scale: .99 }}
                        transition={{ duration: .28, ease: [0.16, 1, 0.3, 1] }}
                    />
                </AnimatePresence>
                <span className="service-product-save" aria-hidden="true">♡</span>
            </div>
            {hasPortfolio && <div className="service-product-portfolio" aria-label="Portofolio jasa">
                {service.portfolios.slice(0, 3).map((image, portfolioIndex) => <img key={image} src={image} alt="" className={isHovered && portfolioIndex === 0 ? 'is-active' : ''} />)}
            </div>}
            <div className="service-product-copy">
                <p className="service-product-price">{service.price}</p>
                <h3>{service.title}</h3>
                <p>{service.category}</p>
                <small>{service.seller}</small>
                {hasPortfolio && <span className="service-product-portfolio-label">Portofolio tersedia</span>}
            </div>
        </a>
    </motion.article>;
}

function FeaturedServices({ services, marketplaceUrl }) {
    const reduceMotion = useReducedMotion();

    return <section className="featured-marketplace" aria-label="Jasa unggulan SkillHub">
        <style>{`
            .featured-marketplace{padding:clamp(5rem,8vw,8.5rem) clamp(1rem,4vw,5rem);background:linear-gradient(180deg,#080808 0,#080808 250px,#3d3d3b 450px,#d9dcdd 680px,#f6f6f6 980px);color:#151515}.featured-marketplace-wrap{max-width:1540px;margin:auto}.featured-marketplace-heading{max-width:780px;color:#f6f6f6}.featured-marketplace-kicker{margin:0;color:#c6c6c2;font-size:.72rem;font-weight:700;letter-spacing:.15em;text-transform:uppercase}.featured-marketplace-heading h2{margin:.85rem 0 0;font-size:clamp(3rem,6.7vw,7rem);line-height:.85;letter-spacing:-.095em;font-weight:700}.featured-marketplace-heading h2 span{color:#9c9c98}.featured-marketplace-heading>p:last-child{max-width:530px;margin:1.4rem 0 0;color:#d4d4d0;font-size:1rem;line-height:1.65}.featured-marketplace-toolbar{display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-top:clamp(3rem,5vw,5rem);padding-bottom:1rem;color:#f6f6f6}.featured-marketplace-toolbar p{margin:0;font-size:.9rem;font-weight:700}.featured-marketplace-toolbar a{display:inline-flex;align-items:center;gap:.8rem;border:1px solid currentColor;padding:.8rem 1rem;color:inherit;text-decoration:none;font-size:.8rem;font-weight:700;transition:background .2s ease,color .2s ease,transform .2s ease}.featured-marketplace-toolbar a:hover{background:#f6f6f6;color:#151515;transform:translateY(-2px)}.featured-marketplace-toolbar a:active{transform:translateY(1px) scale(.98)}.featured-marketplace-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:2px;background:rgba(246,246,246,.74)}.service-product{min-width:0;background:#f6f6f6;transition:transform .28s cubic-bezier(.16,1,.3,1),box-shadow .28s ease;position:relative}.service-product.is-hovered{z-index:1;transform:translateY(-6px);box-shadow:0 12px 30px rgba(8,8,8,.2)}.service-product-link{display:block;color:#151515;text-decoration:none;outline:0}.service-product-link:focus-visible{outline:2px solid #151515;outline-offset:-4px}.service-product-media{position:relative;aspect-ratio:1/1;overflow:hidden;background:#e7eaeb}.service-product-image{width:100%;height:100%;object-fit:cover}.service-product-save{position:absolute;top:1rem;right:1rem;color:#151515;font-size:2rem;line-height:1;text-shadow:0 1px 0 rgba(255,255,255,.5)}.service-product-portfolio{display:flex;gap:3px;min-height:0;background:#f6f6f6;overflow:hidden}.service-product-portfolio img{width:3.4rem;height:3.4rem;object-fit:cover;opacity:.56;transition:opacity .2s ease,transform .2s ease}.service-product.is-hovered .service-product-portfolio img.is-active{opacity:1;transform:translateY(-2px)}.service-product-copy{min-height:150px;padding:1rem .9rem 1.15rem;background:#f6f6f6}.service-product-copy p{margin:0}.service-product-price{font-size:.9rem;font-weight:700}.service-product-copy h3{margin:.6rem 0 .25rem;font-size:1rem;line-height:1.25;font-weight:500}.service-product-copy>p:not(.service-product-price){color:#6d6d69;font-size:.88rem}.service-product-copy small{display:block;margin-top:.25rem;color:#373735;font-size:.82rem}.service-product-portfolio-label{display:block;margin-top:.7rem;color:#6d6d69;font-size:.72rem;font-weight:700}.featured-marketplace-empty{padding:4rem 1rem;border:1px solid rgba(246,246,246,.55);color:#f6f6f6;text-align:center}.featured-marketplace-empty h2{margin:0;font-size:2rem;letter-spacing:-.06em}.featured-marketplace-empty p{max-width:430px;margin:1rem auto 0;color:#d4d4d0;line-height:1.6}@media(max-width:1100px){.featured-marketplace-grid{grid-template-columns:repeat(3,minmax(0,1fr))}}@media(max-width:760px){.featured-marketplace-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.featured-marketplace{padding-inline:1rem}.service-product-copy{min-height:140px}.service-product-save{top:.7rem;right:.7rem;font-size:1.65rem}}@media(max-width:430px){.featured-marketplace-grid{grid-template-columns:1fr}.featured-marketplace-toolbar{align-items:flex-start;flex-direction:column}.featured-marketplace-toolbar a{width:100%;justify-content:space-between}}@media(prefers-reduced-motion:reduce){.service-product,.featured-marketplace-toolbar a,.service-product-portfolio img{transition:none}}
        `}</style>
        <style>{`
            .featured-marketplace-wrap{max-width:1320px}.featured-marketplace-grid{gap:6px;background:transparent}.service-product{border:1px solid transparent}.service-product.is-hovered{transform:translateY(-4px);outline:1px solid #080808;outline-offset:-1px;box-shadow:0 9px 20px rgba(8,8,8,.14)}.service-product-media{aspect-ratio:16/10;background:#e7eaeb}.service-product-image{object-fit:contain}.service-product-portfolio{display:none}.service-product.is-hovered .service-product-portfolio{display:flex}.service-product-copy{min-height:132px}.service-product-portfolio-label{display:none}.service-product.is-hovered .service-product-portfolio-label{display:block}@media(max-width:760px){.featured-marketplace-grid{gap:4px}.service-product-copy{min-height:128px}}
        `}</style>
        <div className="featured-marketplace-wrap">
            <motion.div className="featured-marketplace-heading" initial={reduceMotion ? false : { opacity: 0, y: 24 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true, amount: .25 }} transition={{ duration: .5, ease: [0.16, 1, 0.3, 1] }}>
                <p className="featured-marketplace-kicker">Marketplace</p>
                <h2>Jasa unggulan <span>untukmu.</span></h2>
                <p>Arahkan kursor pada produk untuk mengganti foto jasa menjadi hasil portofolio penyedia jasa.</p>
            </motion.div>
            {services.length ? <><div className="featured-marketplace-toolbar"><p>{services.length} jasa pilihan</p><a href={marketplaceUrl}>Lihat semua jasa <span aria-hidden="true">→</span></a></div><div className="featured-marketplace-grid">{services.map((service, index) => <ServiceProduct key={service.url} service={service} index={index} />)}</div></> : <div className="featured-marketplace-empty"><h2>Belum ada jasa unggulan.</h2><p>Jadilah yang pertama menampilkan keahlian dan portofolio di SkillHub.</p></div>}
        </div>
    </section>;
}

if (mountPoint) createRoot(mountPoint).render(<FeaturedServices services={getServices()} marketplaceUrl={mountPoint.dataset.marketplaceUrl} />);
