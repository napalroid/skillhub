import React, { useRef } from 'react';
import { createRoot } from 'react-dom/client';
import { motion, useReducedMotion, useScroll, useTransform } from 'framer-motion';

const root = document.getElementById('skillhub-orbit-stats');

function StackText({ stat, index }) {
    const itemRef = useRef(null);
    const reducedMotion = useReducedMotion();
    const { scrollYProgress } = useScroll({ target: itemRef, offset: ['start start', 'end start'] });
    const scale = useTransform(scrollYProgress, [0, 1], reducedMotion ? [1, 1] : [1, 0.88]);
    const opacity = useTransform(scrollYProgress, [0.7, 1], reducedMotion ? [1, 1] : [1, 0.28]);

    return <motion.article ref={itemRef} className="text-stack-item" style={{ scale, opacity, zIndex: index + 1 }}>
        <p className="text-stack-number">0{index + 1}</p>
        <p className="text-stack-value">{stat.value}</p>
        <h2>{stat.label}</h2>
        <p className="text-stack-description">{stat.description}</p>
    </motion.article>;
}

function TextScrollStack({ serviceCount, categoryCount }) {
    const stats = [
        { value: `${serviceCount}+`, label: 'Jasa pilihan', description: 'Keahlian siswa untuk kebutuhan nyata.' },
        { value: `${categoryCount}+`, label: 'Kategori keahlian', description: 'Beragam bidang dalam satu ruang kolaborasi.' },
        { value: '100%', label: 'Transaksi lebih aman', description: 'Dana ditahan hingga pekerjaan disetujui.' },
    ];

    return <section className="text-stack-section" aria-label="Statistik SkillHub">
        <style>{`
            .text-stack-section{background:#fff;color:#080808;padding:clamp(5rem,9vw,8rem) 1.25rem 13rem}.text-stack-intro{max-width:1120px;margin:0 auto 5rem}.text-stack-intro p{margin:0;font-size:.7rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:#666}.text-stack-intro h2{max-width:760px;margin:1rem 0 0;font-size:clamp(2.5rem,6vw,5rem);line-height:.92;letter-spacing:-.08em}.text-stack-wrap{max-width:1000px;margin:auto}.text-stack-item{position:sticky;top:clamp(5rem,10vh,7rem);min-height:min(56vh,440px);margin:0 0 20vh;padding:clamp(1.5rem,4vw,3rem) 0;background:#fff;transform-origin:center top}.text-stack-item:not(:first-child){border-top:1px solid #111}.text-stack-number{margin:0;font-size:.7rem;font-weight:700;letter-spacing:.14em;color:#777}.text-stack-value{margin:1.2rem 0 0;font-size:clamp(5.5rem,17vw,12rem);line-height:.72;letter-spacing:-.13em;font-weight:700}.text-stack-item h2{margin:1rem 0 0;font-size:clamp(1.8rem,4.5vw,3.5rem);line-height:.95;letter-spacing:-.08em}.text-stack-description{margin:1.25rem 0 0;color:#666;font-size:.95rem;line-height:1.6}@media(max-width:640px){.text-stack-section{padding-inline:1rem;padding-bottom:8rem}.text-stack-intro{margin-bottom:3.5rem}.text-stack-item{top:4.5rem;min-height:390px;margin-bottom:15vh;padding:1.5rem 0}.text-stack-value{font-size:5.6rem}}@media(prefers-reduced-motion:reduce){.text-stack-item{transform:none!important}}
        `}</style>
        <div className="text-stack-intro"><p>SkillHub dalam angka</p><h2>Angka yang <span style={{ color: '#777' }}>terus bergerak.</span></h2></div>
        <div className="text-stack-wrap">{stats.map((stat, index) => <StackText key={stat.label} stat={stat} index={index} />)}</div>
    </section>;
}

if (root) {
    const categoryCount = root.querySelectorAll('[data-category-name]').length;
    createRoot(root).render(<TextScrollStack serviceCount={Number(root.dataset.serviceCount) || 0} categoryCount={categoryCount} />);
}
