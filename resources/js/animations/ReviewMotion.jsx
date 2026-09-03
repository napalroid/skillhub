import React from 'react';
import { createRoot } from 'react-dom/client';
import { motion, useReducedMotion } from 'framer-motion';

const root = document.getElementById('skillhub-review-motion');

const reviews = [
    { name: 'Messi', role: 'Pembeli jasa desain', text: 'Cari desain untuk acara kelas jadi cepat. Saya bisa melihat jasa, harga, dan ulasannya sebelum memesan.' },
    { name: 'Lalo', role: 'Penyedia jasa', text: 'Platformnya rapi dan mudah dipahami. Pesanan masuk terasa lebih teratur dari awal sampai selesai.' },
    { name: 'Willy Wonka', role: 'Pembeli jasa video', text: 'Komunikasinya enak, pembayarannya jelas, dan hasil video untuk tugas kelompok kami selesai tepat waktu.' },
    { name: 'Walter White', role: 'Penyedia jasa', text: 'SkillHub membantu saya membangun portofolio dari pekerjaan kecil yang benar-benar dibutuhkan teman sekolah.' },
    { name: 'KBNG29', role: 'Pembeli jasa presentasi', text: 'Saya menemukan bantuan presentasi dalam satu hari. Hasilnya sesuai arahan dan revisinya juga responsif.' },
    { name: 'Saul', role: 'Pembeli jasa tulisan', text: 'Harga dan kesepakatan di awal membuat saya lebih tenang. Tidak ada proses yang membingungkan.' },
    { name: 'Kang Mi-na', role: 'Penyedia jasa ilustrasi', text: 'Saya suka karena jasa saya bisa ditemukan teman sekolah tanpa harus promosi terus-menerus di banyak tempat.' },
    { name: 'Skyler', role: 'Pembeli jasa desain', text: 'Tampilannya sederhana, jadi saya langsung paham cara mencari jasa yang sesuai kebutuhan.' },
    { name: 'Kim Wexler', role: 'Penyedia jasa', text: 'Catatan pesanan dan percakapan tersimpan rapi. Saya jadi lebih fokus mengerjakan hasil terbaik.' },
    { name: 'naoplroiddd', role: 'Pembeli jasa editing', text: 'Editor yang saya pilih komunikatif dan hasilnya bersih. Pengalaman pertama pakai SkillHub sangat memuaskan.' },
    { name: 'RuangSore', role: 'Pembeli jasa poster', text: 'Pemesanan poster untuk lomba sekolah terasa aman dan tidak perlu menunggu lama untuk mendapat penyedia jasa.' },
    { name: 'BintangKecil', role: 'Penyedia jasa', text: 'Saya mendapat kesempatan mengubah kemampuan edit foto menjadi pengalaman kerja yang nyata.' },
];

function ReviewCard({ review, index }) {
    const initials = review.name.replace(/[^a-zA-Z]/g, '').slice(0, 2).toUpperCase() || 'SH';

    return <article className="review-motion-card">
        <div className="review-motion-card-top">
            <span className="review-motion-avatar" aria-hidden="true">{initials}</span>
            <div><h3>{review.name}</h3><p>{review.role}</p></div>
            <span className="review-motion-index" aria-hidden="true">{String(index + 1).padStart(2, '0')}</span>
        </div>
        <p className="review-motion-copy">{review.text}</p>
        <div className="review-motion-rating" aria-label="Rating lima dari lima">★★★★★</div>
    </article>;
}

function ReviewLane({ items, duration, delay }) {
    const reducedMotion = useReducedMotion();

    return <div className="review-motion-lane">
        <motion.div className="review-motion-track" animate={reducedMotion ? { y: 0 } : { y: ['0%', '-50%'] }} transition={reducedMotion ? { duration: 0 } : { duration, delay, ease: 'linear', repeat: Infinity }}>
            {[0, 1].map((set) => <div className="review-motion-set" key={set} aria-hidden={set === 1 ? true : undefined}>
                {items.map((review, index) => <ReviewCard key={`${review.name}-${set}`} review={review} index={index} />)}
            </div>)}
        </motion.div>
    </div>;
}

function ReviewMotion() {
    const reducedMotion = useReducedMotion();
    const lanes = [reviews.slice(0, 4), reviews.slice(4, 8), reviews.slice(8, 12)];

    return <section id="review" className="review-motion-section" aria-labelledby="review-motion-title">
        <style>{`
            .review-motion-section{position:relative;overflow:hidden;background:#f5f5f3;color:#111;padding:clamp(5rem,9vw,9rem) clamp(1rem,4vw,4.5rem)}.review-motion-wrap{max-width:1540px;margin:0 auto;display:grid;grid-template-columns:minmax(260px,.78fr) minmax(0,1.7fr);gap:clamp(3rem,8vw,10rem);align-items:center}.review-motion-heading{max-width:420px}.review-motion-kicker{margin:0;font-size:.72rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:#62625f}.review-motion-title{margin:1rem 0 0;font-size:clamp(3rem,6vw,6.4rem);line-height:.84;letter-spacing:-.095em;font-weight:700}.review-motion-title span{color:#777773}.review-motion-description{max-width:330px;margin:1.7rem 0 0;color:#5d5d59;font-size:.98rem;line-height:1.7}.review-motion-note{display:flex;align-items:center;gap:.75rem;margin:2.2rem 0 0;font-size:.78rem;font-weight:700;color:#30302e}.review-motion-note::before{content:'';display:block;width:2.6rem;height:1px;background:#111}.review-motion-window{position:relative;height:570px;overflow:hidden;mask-image:linear-gradient(to bottom,transparent 0%,black 8%,black 92%,transparent 100%)}.review-motion-window::before,.review-motion-window::after{content:'';position:absolute;z-index:2;left:0;right:0;height:2.5rem;pointer-events:none}.review-motion-window::before{top:0;background:linear-gradient(#f5f5f3,transparent)}.review-motion-window::after{bottom:0;background:linear-gradient(transparent,#f5f5f3)}.review-motion-lanes{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:1px;background:#bdbdb8}.review-motion-lane{height:570px;overflow:hidden;background:#f5f5f3;padding:0 .75rem}.review-motion-track{will-change:transform}.review-motion-set{display:grid;gap:.75rem;padding:.75rem 0}.review-motion-card{min-height:190px;display:flex;flex-direction:column;justify-content:space-between;background:#fff;padding:1.25rem;box-shadow:0 10px 25px rgba(17,17,17,.045)}.review-motion-card-top{display:flex;align-items:center;gap:.7rem}.review-motion-avatar{display:grid;place-items:center;width:2.35rem;height:2.35rem;background:#111;color:#fff;font-size:.66rem;font-weight:700;letter-spacing:.04em}.review-motion-card h3{margin:0;font-size:.88rem;line-height:1.1;font-weight:700}.review-motion-card-top p{margin:.23rem 0 0;color:#73736e;font-size:.7rem;line-height:1.3}.review-motion-index{margin-left:auto;align-self:flex-start;color:#92928d;font-size:.65rem;font-weight:700;letter-spacing:.1em}.review-motion-copy{margin:1.45rem 0;color:#343432;font-size:.83rem;line-height:1.65}.review-motion-rating{color:#111;font-size:.7rem;letter-spacing:.12em}@media(max-width:1050px){.review-motion-wrap{grid-template-columns:1fr;gap:3rem}.review-motion-heading{max-width:620px}.review-motion-description{max-width:520px}.review-motion-window,.review-motion-lane{height:500px}}@media(max-width:680px){.review-motion-section{padding-inline:1rem}.review-motion-title{font-size:clamp(3rem,15vw,4.8rem)}.review-motion-window,.review-motion-lane{height:470px}.review-motion-lanes{grid-template-columns:minmax(0,1fr)}.review-motion-lane:not(:first-child){display:none}.review-motion-card{min-height:177px}.review-motion-note{margin-top:1.7rem}}@media(prefers-reduced-motion:reduce){.review-motion-window,.review-motion-lane{height:auto;overflow:visible;mask-image:none}.review-motion-window::before,.review-motion-window::after{display:none}.review-motion-lanes{grid-template-columns:1fr}.review-motion-lane:not(:first-child){display:none}.review-motion-set{padding:0}.review-motion-set:nth-child(2){display:none}}
        `}</style>
        <div className="review-motion-wrap"><motion.div className="review-motion-heading" initial={reducedMotion ? false : { opacity: 0, y: 24 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true, amount: .4 }} transition={{ duration: .55, ease: [0.16, 1, 0.3, 1] }}><p className="review-motion-kicker">Ulasan komunitas</p><h2 id="review-motion-title" className="review-motion-title">Apa kata <span>pengguna kami.</span></h2><p className="review-motion-description">Cerita singkat dari siswa yang mencari bantuan dan berkarya bersama melalui SkillHub.</p><p className="review-motion-note">Ulasan baru terus bergerak</p></motion.div><motion.div className="review-motion-window" initial={reducedMotion ? false : { opacity: 0, y: 32 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true, amount: .2 }} transition={{ duration: .65, ease: [0.16, 1, 0.3, 1] }}><div className="review-motion-lanes"><ReviewLane items={lanes[0]} duration={18} delay={0} /><ReviewLane items={lanes[1]} duration={22} delay={-8} /><ReviewLane items={lanes[2]} duration={20} delay={-13} /></div></motion.div></div>
    </section>;
}

if (root) createRoot(root).render(<ReviewMotion />);
