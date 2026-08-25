import React, { useState } from 'react';
import { createRoot } from 'react-dom/client';
import { motion, useReducedMotion } from 'framer-motion';
import { IconCashBanknote, IconMessages, IconTools, IconWallet } from '@tabler/icons-react';

const root = document.getElementById('skillhub-how-we-work');

const steps = [
    { title: 'Diskusi Harga', text: 'Pembeli dan penyedia jasa menyepakati kebutuhan, hasil kerja, serta harga yang sesuai.', icon: IconMessages },
    { title: 'Bayar', text: 'Pembayaran dilakukan melalui sistem escrow agar dana tersimpan aman sebelum pekerjaan dimulai.', icon: IconWallet },
    { title: 'Proses', text: 'Penyedia jasa mengerjakan pesanan. Komunikasi dan perkembangan pekerjaan dapat dipantau bersama.', icon: IconTools },
    { title: 'Cair', text: 'Setelah pekerjaan disetujui, dana diteruskan kepada penyedia jasa dengan aman.', icon: IconCashBanknote },
];

function HowWeWork() {
    const [activeIndex, setActiveIndex] = useState(0);
    const reduceMotion = useReducedMotion();

    return <section id="how-we-work" className="how-we-work-section">
        <style>{`
            .how-we-work-section{padding:clamp(5rem,8vw,8rem) clamp(1rem,4vw,5rem);background:#f6f6f6;color:#080808}.how-we-work-wrap{max-width:1540px;margin:auto}.how-we-work-heading{max-width:770px}.how-we-work-heading p{margin:0;color:#62625f;font-size:.73rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase}.how-we-work-heading h2{margin:.8rem 0 0;font-size:clamp(3rem,6vw,6.6rem);line-height:.86;letter-spacing:-.09em;font-weight:700}.how-we-work-heading h2 span{color:#777773}.how-we-work-heading>div{max-width:525px;margin:1.35rem 0 0;color:#555552;font-size:1rem;line-height:1.65}.how-we-work-gallery{display:flex;gap:2px;min-height:430px;margin-top:clamp(3rem,6vw,5.5rem);background:#080808}.how-we-work-panel{position:relative;display:flex;min-width:0;flex:1;overflow:hidden;border:0;background:#e9ebeb;color:#080808;text-align:left;cursor:pointer;will-change:flex;transition:flex .72s cubic-bezier(.16,1,.3,1),background .42s ease,color .42s ease}.how-we-work-panel.is-active{flex:2.8;background:#080808;color:#f6f6f6}.how-we-work-panel:focus-visible{z-index:1;outline:3px solid #f6f6f6;outline-offset:-5px}.how-we-work-panel-inner{display:flex;flex-direction:column;justify-content:space-between;width:100%;padding:1.45rem;white-space:nowrap}.how-we-work-step{font-size:.72rem;font-weight:700;letter-spacing:.13em;color:#5e5e5b;transition:color .32s ease}.is-active .how-we-work-step{color:#bcbcb7}.how-we-work-icon{display:grid;place-items:center;width:3.1rem;height:3.1rem;border:1px solid currentColor;margin-top:auto;transition:transform .48s cubic-bezier(.16,1,.3,1)}.is-active .how-we-work-icon{transform:translateY(-.2rem) rotate(-4deg)}.how-we-work-icon svg{width:1.55rem;height:1.55rem;stroke-width:1.6}.how-we-work-title{margin:1.4rem 0 0;font-size:clamp(1.25rem,2.1vw,2.15rem);line-height:.95;letter-spacing:-.06em;font-weight:700;writing-mode:vertical-rl;transform:rotate(180deg);transition:opacity .28s ease}.is-active .how-we-work-title{writing-mode:horizontal-tb;transform:none}.how-we-work-description{max-width:300px;margin:1rem 0 0;color:#d0d0ca;font-size:.92rem;line-height:1.6;white-space:normal}.how-we-work-panel:not(.is-active) .how-we-work-description{display:none}.how-we-work-hint{margin:1.1rem 0 0;color:#696966;font-size:.78rem;font-weight:700}@media(max-width:760px){.how-we-work-section{padding-inline:1rem}.how-we-work-gallery{display:grid;grid-template-columns:1fr;min-height:0}.how-we-work-panel,.how-we-work-panel.is-active{min-height:116px;flex:auto}.how-we-work-panel.is-active{min-height:230px}.how-we-work-panel-inner{padding:1.15rem}.how-we-work-panel:not(.is-active){display:grid;grid-template-columns:auto auto 1fr;align-items:center;gap:.9rem}.how-we-work-panel:not(.is-active) .how-we-work-icon{margin:0}.how-we-work-title,.is-active .how-we-work-title{margin:0;writing-mode:horizontal-tb;transform:none}.how-we-work-panel:not(.is-active) .how-we-work-title{font-size:1.25rem}.how-we-work-panel.is-active .how-we-work-icon{margin-top:auto}.how-we-work-hint{display:none}}@media(prefers-reduced-motion:reduce){.how-we-work-panel,.how-we-work-icon,.how-we-work-step,.how-we-work-title{transition:none}}
        `}</style>
        <div className="how-we-work-wrap">
            <motion.div className="how-we-work-heading" initial={reduceMotion ? false : { opacity: 0, y: 22 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true, amount: .25 }} transition={{ duration: .5, ease: [0.16, 1, 0.3, 1] }}><p>How we work</p><h2>Empat langkah, <span>satu proses aman.</span></h2><div>Mulai dari kesepakatan hingga dana dicairkan, setiap pesanan dibuat jelas untuk pembeli dan penyedia jasa.</div></motion.div>
            <div className="how-we-work-gallery" role="tablist" aria-label="Tahapan transaksi SkillHub">{steps.map((step, index) => { const Icon = step.icon; const isActive = activeIndex === index; return <button key={step.title} type="button" role="tab" aria-selected={isActive} className={`how-we-work-panel ${isActive ? 'is-active' : ''}`} onClick={() => setActiveIndex(index)} onMouseEnter={() => setActiveIndex(index)} onFocus={() => setActiveIndex(index)}><span className="how-we-work-panel-inner"><span className="how-we-work-step">LANGKAH 0{index + 1}</span><span className="how-we-work-icon"><Icon aria-hidden="true" /></span><strong className="how-we-work-title">{step.title}</strong>{isActive && <span className="how-we-work-description">{step.text}</span>}</span></button>; })}</div>
            <p className="how-we-work-hint">Pilih setiap langkah untuk melihat prosesnya.</p>
        </div>
    </section>;
}

if (root) createRoot(root).render(<HowWeWork />);
