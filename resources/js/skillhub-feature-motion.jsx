import React, { useRef } from 'react';
import { createRoot } from 'react-dom/client';
import { motion, useReducedMotion, useScroll, useTransform } from 'framer-motion';

const features = [
    { number: '01', icon: '✓', title: 'Siswa terverifikasi', text: 'Setiap jasa berasal dari siswa dalam lingkungan sekolah yang sama.' },
    { number: '02', icon: 'Rp', title: 'Harga bersahabat', text: 'Temukan bantuan yang sesuai kebutuhan dengan harga yang jelas.' },
    { number: '03', icon: '⌁', title: 'Dana aman', text: 'Dana ditahan dalam escrow sampai pekerjaan disetujui.' },
    { number: '04', icon: '↔', title: 'Komunikasi mudah', text: 'Diskusikan kebutuhan dan pantau progres pesananmu.' },
];

function FeatureSection() {
    const sectionRef = useRef(null);
    const prefersReducedMotion = useReducedMotion();
    const { scrollYProgress } = useScroll({ target: sectionRef, offset: ['start end', 'end start'] });
    const titleY = useTransform(scrollYProgress, [0, 0.5, 1], prefersReducedMotion ? [0, 0, 0] : [42, 0, -20]);
    const cardsY = useTransform(scrollYProgress, [0, 0.5, 1], prefersReducedMotion ? [0, 0, 0] : [82, 0, -42]);

    return (
        <section id="keunggulan" ref={sectionRef} className="feature-motion-section">
            <style>{`
                .feature-motion-section { position:relative; overflow:hidden; background:#080808; color:#fff; padding:clamp(5rem, 10vw, 9rem) 1.25rem; }
                .feature-motion-section::before { content:''; position:absolute; width:46rem; height:46rem; border:1px solid rgba(255,255,255,.12); border-radius:999px; right:-26rem; top:-23rem; }
                .feature-motion-wrap { position:relative; max-width:1200px; margin:0 auto; }
                .feature-motion-eyebrow { margin:0; font-size:.72rem; letter-spacing:.18em; font-weight:700; text-transform:uppercase; color:#bcbcbc; }
                .feature-motion-title { max-width:760px; margin:1rem 0 0; font-size:clamp(2.45rem, 6vw, 5.5rem); line-height:.92; letter-spacing:-.075em; font-weight:700; }
                .feature-motion-title em { font-style:normal; color:#a9a9a9; }
                .feature-motion-rule { height:1px; margin:clamp(3rem, 7vw, 6rem) 0 1.2rem; background:rgba(255,255,255,.25); }
                .feature-motion-grid { display:grid; grid-template-columns:repeat(4, minmax(0,1fr)); gap:1px; background:rgba(255,255,255,.2); border:1px solid rgba(255,255,255,.2); }
                .feature-motion-card { min-height:275px; background:#080808; padding:1.5rem; display:flex; flex-direction:column; transition:background .25s ease,color .25s ease; }
                .feature-motion-card:hover { background:#fff; color:#080808; }
                .feature-motion-top { display:flex; justify-content:space-between; align-items:center; }
                .feature-motion-icon { display:flex; align-items:center; justify-content:center; width:3rem; height:3rem; border:1px solid currentColor; border-radius:50%; font-weight:700; font-size:1.1rem; }
                .feature-motion-number { font-size:.7rem; letter-spacing:.12em; font-weight:700; color:#a9a9a9; }
                .feature-motion-card h3 { margin:3.3rem 0 0; font-size:1.25rem; line-height:1; letter-spacing:-.04em; }
                .feature-motion-card p { margin:1rem 0 0; max-width:15rem; font-size:.9rem; line-height:1.65; color:#b8b8b8; }
                .feature-motion-card:hover p { color:#4b4b4b; }
                .feature-motion-foot { display:flex; justify-content:space-between; gap:1rem; margin-top:2.2rem; color:#aaa; font-size:.8rem; }
                @media (max-width:900px) { .feature-motion-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } }
                @media (max-width:520px) { .feature-motion-section { padding-inline:1rem; } .feature-motion-grid { grid-template-columns:1fr; } .feature-motion-card { min-height:220px; } .feature-motion-card h3 { margin-top:2.5rem; } }
            `}</style>
            <div className="feature-motion-wrap">
                <motion.div style={{ y: titleY }}>
                    <p className="feature-motion-eyebrow">Kenapa SkillHub?</p>
                    <h2 className="feature-motion-title">Satu tempat untuk <em>berkarya</em> dan berkolaborasi.</h2>
                </motion.div>
                <div className="feature-motion-rule" />
                <motion.div className="feature-motion-grid" style={{ y: cardsY }}>
                    {features.map((feature, index) => (
                        <motion.article key={feature.number} className="feature-motion-card" initial={{ opacity: 0, y: prefersReducedMotion ? 0 : 42 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true, amount: 0.25 }} transition={{ duration: 0.55, delay: index * 0.09, ease: [0.16, 1, 0.3, 1] }}>
                            <div className="feature-motion-top"><span className="feature-motion-icon">{feature.icon}</span><span className="feature-motion-number">{feature.number}</span></div>
                            <h3>{feature.title}</h3><p>{feature.text}</p>
                        </motion.article>
                    ))}
                </motion.div>
                <div className="feature-motion-foot"><span>SKILLHUB / 2026</span><span>SCROLL UNTUK MENJELAJAHI ↓</span></div>
            </div>
        </section>
    );
}

const mountPoint = document.getElementById('skillhub-feature-motion');
if (mountPoint) createRoot(mountPoint).render(<FeatureSection />);
