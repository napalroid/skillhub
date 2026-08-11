import React, { useEffect, useRef, useState } from 'react';
import { AnimatePresence, motion, useReducedMotion } from 'framer-motion';
import { createRoot } from 'react-dom/client';

const menuRoot = document.getElementById('skillhub-staggered-menu');
const notificationDataNode = document.getElementById('skillhub-account-notifications-data');

function getNotifications() {
    try {
        return notificationDataNode?.textContent ? JSON.parse(notificationDataNode.textContent) : [];
    } catch {
        return [];
    }
}

function AccountControl({ authenticated, userName, profileUrl, loginUrl, registerUrl, logoutUrl, csrfToken, notifications, notificationsUrl, readAllUrl, menuOpen, onOpenMenu }) {
    const [profileOpen, setProfileOpen] = useState(false);
    const accountRef = useRef(null);
    const initial = userName?.trim().charAt(0).toLocaleUpperCase('id-ID');
    const unreadCount = notifications.filter((notification) => !notification.is_read).length;
    const dark = menuOpen ? ' is-dark' : '';

    useEffect(() => { if (menuOpen) setProfileOpen(false); }, [menuOpen]);
    useEffect(() => {
        const closeOnOutsidePress = (event) => {
            if (!accountRef.current?.contains(event.target)) setProfileOpen(false);
        };
        document.addEventListener('pointerdown', closeOnOutsidePress);
        return () => document.removeEventListener('pointerdown', closeOnOutsidePress);
    }, []);

    return <div className={`stagger-account${dark}`} ref={accountRef}>
        <button type="button" className="stagger-account-trigger" onClick={() => { setProfileOpen((current) => !current); onOpenMenu(false); }} aria-expanded={profileOpen} aria-haspopup="menu" title={authenticated ? `Akun ${userName}` : 'Buka pilihan akun'}>
            {authenticated ? <span className="stagger-avatar" aria-hidden="true">{initial}</span> : <span className="stagger-user-icon" aria-hidden="true" />}
            <span className="stagger-account-name">{authenticated ? userName : 'Akun'}</span>
            {authenticated && unreadCount > 0 && <span className="stagger-account-count" aria-label={`${unreadCount} notifikasi belum dibaca`}>{unreadCount}</span>}
        </button>
        <AnimatePresence>{profileOpen && <motion.div className="stagger-account-popover" role="menu" initial={{ opacity: 0, y: -8, scale: .97 }} animate={{ opacity: 1, y: 0, scale: 1 }} exit={{ opacity: 0, y: -8, scale: .97 }} transition={{ duration: .18, ease: [0.16, 1, 0.3, 1] }}>
            {authenticated ? <>
                <div className="stagger-account-popover-head"><div><p>Akun</p><strong>{userName}</strong></div><a href={notificationsUrl} onClick={() => setProfileOpen(false)}>Notifikasi{unreadCount > 0 && <span>{unreadCount}</span>}</a></div>
                <div className="stagger-account-notifications">
                    {notifications.length ? notifications.map((notification) => <form key={notification.id} method="POST" action={notification.read_url}><input type="hidden" name="_token" value={csrfToken} /><button type="submit" className={`stagger-account-notification ${notification.is_read ? '' : 'is-unread'}`} role="menuitem"><strong>{notification.title}</strong><span>{notification.message}</span><small>{notification.date}</small></button></form>) : <p className="stagger-account-empty">Belum ada notifikasi.</p>}
                </div>
                {unreadCount > 0 && <form className="stagger-account-read-all" method="POST" action={readAllUrl}><input type="hidden" name="_token" value={csrfToken} /><button type="submit">Tandai semua dibaca</button></form>}
                <div className="stagger-account-actions"><a href={profileUrl} role="menuitem" onClick={() => setProfileOpen(false)}>Edit profil</a><form method="POST" action={logoutUrl}><input type="hidden" name="_token" value={csrfToken} /><button type="submit" role="menuitem">Log out</button></form></div>
            </> : <div className="stagger-account-actions"><a href={loginUrl} role="menuitem">Login</a><a href={registerUrl} role="menuitem">Registrasi</a></div>}
        </motion.div>}</AnimatePresence>
    </div>;
}

function StaggeredMenu({ links, account }) {
    const [open, setOpen] = useState(false);
    const reducedMotion = useReducedMotion();

    useEffect(() => {
        const onKeyDown = (event) => { if (event.key === 'Escape') setOpen(false); };
        document.addEventListener('keydown', onKeyDown);
        document.body.style.overflow = open ? 'hidden' : '';
        return () => { document.removeEventListener('keydown', onKeyDown); document.body.style.overflow = ''; };
    }, [open]);

    const itemVariants = { closed: { opacity: 0, y: reducedMotion ? 0 : 36 }, open: (index) => ({ opacity: 1, y: 0, transition: { delay: .16 + index * .075, duration: .52, ease: [0.16, 1, 0.3, 1] } }) };

    return <>
        <style>{`
            .stagger-nav{position:fixed;inset:0 0 auto;z-index:60;padding:1.25rem;pointer-events:none}.stagger-nav-bar{max-width:1200px;margin:auto;display:flex;align-items:center;justify-content:space-between;pointer-events:auto}.stagger-brand{color:#080808;text-decoration:none;display:flex;align-items:center;gap:.65rem;font-size:1.15rem;font-weight:700;letter-spacing:-.05em;transition:color .2s}.stagger-mark{display:grid;place-items:center;width:2.25rem;height:2.25rem;background:#080808;color:#fff;font-size:1.1rem;transition:background .2s,color .2s}.stagger-nav-actions{display:flex;align-items:center;gap:.55rem}.stagger-trigger{width:4.7rem;height:2.6rem;border:1px solid #080808;background:#fff;color:#080808;font:inherit;font-size:.73rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;cursor:pointer;transition:background .2s,color .2s,border-color .2s}.stagger-trigger:hover{background:#080808;color:#fff}.stagger-account{position:relative}.stagger-account-trigger{display:flex;align-items:center;gap:.5rem;min-height:2.6rem;border:1px solid #080808;background:#fff;color:#080808;padding:.2rem .55rem .2rem .25rem;font:inherit;font-size:.75rem;font-weight:700;cursor:pointer;transition:background .2s,color .2s,border-color .2s}.stagger-account-trigger:hover{background:#080808;color:#fff}.stagger-account-name{max-width:9rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.stagger-avatar{display:grid;place-items:center;width:1.85rem;height:1.85rem;border-radius:50%;background:#080808;color:#fff;font-size:.68rem;font-weight:700;transition:background .2s,color .2s}.stagger-user-icon{position:relative;display:block;width:1.85rem;height:1.85rem;border:1px solid currentColor;border-radius:50%}.stagger-user-icon::before{content:'';position:absolute;left:50%;top:.32rem;width:.48rem;height:.48rem;border-radius:50%;background:currentColor;transform:translateX(-50%)}.stagger-user-icon::after{content:'';position:absolute;left:50%;bottom:.28rem;width:.92rem;height:.5rem;border-radius:.65rem .65rem .3rem .3rem;background:currentColor;transform:translateX(-50%)}.stagger-account-count{display:grid;place-items:center;min-width:1.05rem;height:1.05rem;background:#080808;color:#fff;font-size:.6rem;line-height:1}.stagger-account-popover{position:absolute;right:0;top:calc(100% + .55rem);width:min(22rem,calc(100vw - 2rem));background:#fff;border:1px solid #080808;padding:.35rem;box-shadow:6px 6px 0 #080808}.stagger-account-popover-head{display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;padding:.75rem}.stagger-account-popover-head p{margin:0;color:#6f6f6b;font-size:.68rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase}.stagger-account-popover-head strong{display:block;margin-top:.18rem;font-size:.9rem}.stagger-account-popover-head>a{display:inline-flex!important;align-items:center;gap:.35rem;width:auto!important;padding:.45rem!important;border:1px solid currentColor;font-size:.68rem!important}.stagger-account-popover-head>a span{display:grid;place-items:center;min-width:1rem;height:1rem;background:#080808;color:#fff;font-size:.58rem}.stagger-account-notifications{max-height:17rem;overflow-y:auto;border-top:1px solid #080808;border-bottom:1px solid #080808}.stagger-account-notification{display:block;width:100%;border:0;border-bottom:1px solid rgba(8,8,8,.15);background:transparent;padding:.75rem;text-align:left;color:#080808;font:inherit;cursor:pointer}.stagger-account-notification:last-child{border-bottom:0}.stagger-account-notification:hover{background:#f0f0ed}.stagger-account-notification.is-unread{background:#e9e9e5}.stagger-account-notification strong,.stagger-account-notification span,.stagger-account-notification small{display:block}.stagger-account-notification strong{font-size:.76rem;line-height:1.3}.stagger-account-notification span{margin-top:.2rem;color:#595955;font-size:.7rem;line-height:1.4}.stagger-account-notification small{margin-top:.4rem;color:#777772;font-size:.63rem;font-weight:700}.stagger-account-empty{margin:0;padding:1.2rem .75rem;color:#62625f;font-size:.75rem}.stagger-account-read-all{padding:.35rem 0}.stagger-account-read-all button{color:#5d5d59!important;font-size:.7rem!important}.stagger-account-read-all button:hover{color:#fff!important}.stagger-account-actions{display:grid;grid-template-columns:1fr 1fr;gap:.35rem}.stagger-account-popover a,.stagger-account-popover button{display:block;width:100%;border:0;background:transparent;padding:.7rem .75rem;color:#080808;text-align:left;text-decoration:none;font:inherit;font-size:.78rem;font-weight:700;cursor:pointer}.stagger-account-popover a:hover,.stagger-account-popover button:hover{background:#080808;color:#fff}.stagger-nav.is-open .stagger-brand{color:#fff}.stagger-nav.is-open .stagger-mark{background:#fff;color:#080808}.stagger-nav.is-open .stagger-trigger,.stagger-nav.is-open .stagger-account-trigger{border-color:#fff;background:#080808;color:#fff}.stagger-nav.is-open .stagger-avatar{background:#fff;color:#080808}.stagger-nav.is-open .stagger-account-count{background:#fff;color:#080808}.stagger-nav.is-open .stagger-account-popover{background:#080808;border-color:#fff;box-shadow:6px 6px 0 #fff}.stagger-nav.is-open .stagger-account-popover a,.stagger-nav.is-open .stagger-account-popover button{color:#fff}.stagger-nav.is-open .stagger-account-popover a:hover,.stagger-nav.is-open .stagger-account-popover button:hover{background:#fff;color:#080808}.stagger-nav.is-open .stagger-account-popover-head p,.stagger-nav.is-open .stagger-account-notification span,.stagger-nav.is-open .stagger-account-notification small,.stagger-nav.is-open .stagger-account-empty{color:#bcbcb7}.stagger-nav.is-open .stagger-account-popover-head>a span{background:#fff;color:#080808}.stagger-nav.is-open .stagger-account-notifications{border-color:#fff}.stagger-nav.is-open .stagger-account-notification{border-color:rgba(255,255,255,.2);color:#fff}.stagger-nav.is-open .stagger-account-notification.is-unread{background:#252525}.stagger-nav.is-open .stagger-account-notification:hover{background:#fff;color:#080808}.stagger-nav.is-open .stagger-account-read-all button{color:#bcbcb7!important}.stagger-overlay{position:fixed;inset:0;z-index:55;background:#080808;color:#fff;overflow:hidden}.stagger-overlay::before{content:'';position:absolute;right:-13rem;top:-13rem;width:42rem;height:42rem;border:1px solid rgba(255,255,255,.18);border-radius:50%}.stagger-overlay-inner{position:relative;max-width:1200px;min-height:100%;margin:auto;padding:7.5rem 1.25rem 2rem;display:flex;flex-direction:column}.stagger-list{list-style:none;margin:0;padding:0}.stagger-link{display:inline-flex;align-items:baseline;gap:1rem;color:#fff;text-decoration:none;font-size:clamp(2.8rem,8vw,7rem);font-weight:700;letter-spacing:-.09em;line-height:.94;transition:color .2s,transform .2s}.stagger-link:hover{color:#9d9d9d;transform:translateX(.7rem)}.stagger-index{width:2rem;color:#aaa;font-size:.68rem;font-weight:700;letter-spacing:.08em}.stagger-meta{margin-top:auto;padding-top:3rem;display:flex;justify-content:space-between;gap:1rem;color:#aaa;font-size:.72rem;font-weight:700;letter-spacing:.1em}@media(max-width:640px){.stagger-nav{padding:1rem}.stagger-account-name{display:none}.stagger-account-trigger{padding:.2rem}.stagger-account-popover{right:-5.25rem}.stagger-overlay-inner{padding-top:6.5rem}.stagger-link{font-size:clamp(2.65rem,15vw,4.5rem)}.stagger-meta{flex-direction:column}.stagger-account-popover-head{padding:.65rem}.stagger-account-notification{padding:.65rem}}
        `}</style>
        <nav className={`stagger-nav${open ? ' is-open' : ''}`} aria-label="Navigasi utama"><div className="stagger-nav-bar"><a className="stagger-brand" href={links.home}><span className="stagger-mark">✦</span>SkillHub</a><div className="stagger-nav-actions"><AccountControl {...account} menuOpen={open} onOpenMenu={setOpen} /><button className="stagger-trigger" onClick={() => setOpen((current) => !current)} aria-expanded={open} aria-controls="skillhub-menu-panel">{open ? 'Tutup' : 'Menu'}</button></div></div></nav>
        <AnimatePresence>{open && <motion.div id="skillhub-menu-panel" className="stagger-overlay" initial={{ clipPath: reducedMotion ? 'none' : 'circle(0% at calc(100% - 3.6rem) 2.6rem)' }} animate={{ clipPath: 'circle(150% at calc(100% - 3.6rem) 2.6rem)' }} exit={{ clipPath: reducedMotion ? 'none' : 'circle(0% at calc(100% - 3.6rem) 2.6rem)' }} transition={{ duration: reducedMotion ? 0 : .65, ease: [0.76, 0, 0.24, 1] }}><div className="stagger-overlay-inner"><ul className="stagger-list">{links.items.map((item, index) => <motion.li key={item.label} custom={index} variants={itemVariants} initial="closed" animate="open"><a className="stagger-link" href={item.href} onClick={() => setOpen(false)}><span className="stagger-index">0{index + 1}</span>{item.label}</a></motion.li>)}</ul><div className="stagger-meta"><span>SKILLHUB / SISWA BERKARYA</span><span>MENU</span></div></div></motion.div>}</AnimatePresence>
    </>;
}

if (menuRoot) {
    const links = { home: menuRoot.dataset.home, items: [{ label: 'Home', href: menuRoot.dataset.home }, { label: 'Marketplace', href: menuRoot.dataset.marketplace }, { label: 'How we work', href: menuRoot.dataset.how }, { label: 'Why us?', href: menuRoot.dataset.whyUs }, { label: 'Get started', href: menuRoot.dataset.getStarted }] };
    const account = { authenticated: menuRoot.dataset.authenticated === 'true', userName: menuRoot.dataset.userName, profileUrl: menuRoot.dataset.profileUrl, loginUrl: menuRoot.dataset.login, registerUrl: menuRoot.dataset.register, logoutUrl: menuRoot.dataset.logoutUrl, csrfToken: menuRoot.dataset.csrfToken, notifications: getNotifications(), notificationsUrl: menuRoot.dataset.notificationsUrl, readAllUrl: menuRoot.dataset.notificationsReadAllUrl };
    createRoot(menuRoot).render(<StaggeredMenu links={links} account={account} />);
}
