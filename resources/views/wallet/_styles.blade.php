{{-- Scoped wallet UI. Prefix wh- so marketplace/admin pages are untouched. --}}
<style>
.wh-page,
.wh-notice-host,
.wh-dialog {
    --wh-ink: #111111;
    --wh-ink-2: #555555;
    --wh-ink-3: #767676;
    --wh-line: #e5e5e5;
    --wh-line-strong: #111111;
    --wh-bg: #ffffff;
    --wh-soft: #f5f5f5;
    --wh-mute: #f0f0f0;
    --wh-dur: 220ms;
    --wh-dur-fast: 140ms;
    --wh-ease: cubic-bezier(0.16, 1, 0.3, 1);
    --wh-font-display: var(--font-heading, "Montserrat", "Helvetica Neue", Arial, sans-serif);
    --wh-font-text: var(--font-sans, "Inter", ui-sans-serif, system-ui, sans-serif);
    --wh-notice-top: 5.6rem;
}
.wh-page {
    color: var(--wh-ink);
    background: var(--wh-bg);
    width: 100vw;
    margin: -1.5rem calc(50% - 50vw) -1.5rem;
    min-height: calc(100dvh - 4rem);
    padding-bottom: 4.5rem;
}
.wh-page * { box-sizing: border-box; }
.wh-inner {
    max-width: 1180px;
    margin: 0 auto;
    padding: 2.5rem 1.25rem 0;
}
@media (min-width: 768px) {
    .wh-inner { padding: 3.5rem 2rem 0; }
}

.wh-kicker {
    margin: 0;
    font-family: var(--wh-font-display);
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .16em;
    text-transform: uppercase;
    color: var(--wh-ink-3);
}
.wh-rule {
    display: block;
    width: 2.5rem;
    height: 1px;
    margin: .65rem 0 1rem;
    background: var(--wh-line-strong);
    border: 0;
}
.wh-display {
    margin: 0;
    font-family: var(--wh-font-display);
    font-weight: 800;
    letter-spacing: -.06em;
    line-height: .92;
    text-transform: uppercase;
    color: var(--wh-ink);
}
.wh-lede {
    margin: 1rem 0 0;
    max-width: 36rem;
    font-size: .95rem;
    line-height: 1.55;
    color: var(--wh-ink-2);
}

.wh-hero {
    display: grid;
    gap: 2.5rem;
    padding-bottom: 2.75rem;
    border-bottom: 1px solid var(--wh-line);
}
@media (min-width: 900px) {
    .wh-hero {
        grid-template-columns: minmax(0, 1.4fr) minmax(16rem, .7fr);
        align-items: end;
        gap: 4rem;
    }
}
.wh-balance-label {
    margin: 0 0 .35rem;
    font-family: var(--wh-font-display);
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .16em;
    text-transform: uppercase;
    color: var(--wh-ink-3);
}
.wh-balance-value {
    margin: 0;
    font-family: var(--wh-font-display);
    font-size: clamp(2.6rem, 7vw, 5.25rem);
    font-weight: 800;
    letter-spacing: -.07em;
    line-height: .9;
    color: var(--wh-ink);
}
.wh-balance-currency {
    display: block;
    font-size: .42em;
    letter-spacing: .08em;
    font-weight: 700;
    color: var(--wh-ink-3);
    margin-bottom: .15em;
}
.wh-hero-aside {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
    padding-top: .25rem;
}
.wh-facts {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
    border-top: 1px solid var(--wh-line);
}
.wh-fact {
    padding: .85rem 1rem .85rem 0;
    border-bottom: 1px solid var(--wh-line);
}
.wh-fact:nth-child(even) { padding-left: 1rem; border-left: 1px solid var(--wh-line); }
.wh-fact dt {
    margin: 0;
    font-family: var(--wh-font-display);
    font-size: .62rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--wh-ink-3);
}
.wh-fact dd {
    margin: .3rem 0 0;
    font-family: var(--wh-font-display);
    font-size: 1rem;
    font-weight: 700;
    letter-spacing: -.02em;
}

.wh-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 2.85rem;
    padding: .75rem 1.35rem;
    border: 1px solid var(--wh-line-strong);
    background: var(--wh-ink);
    color: #fff;
    font-family: var(--wh-font-display);
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    text-decoration: none;
    cursor: pointer;
    transition: background-color var(--wh-dur-fast) ease, color var(--wh-dur-fast) ease;
}
.wh-btn:hover { background: #fff; color: var(--wh-ink); }
.wh-btn:active { transform: translateY(1px); }
.wh-btn:disabled { opacity: .45; cursor: not-allowed; }
.wh-btn-ghost {
    background: #fff;
    color: var(--wh-ink);
}
.wh-btn-ghost:hover { background: var(--wh-ink); color: #fff; }
.wh-btn-block { width: 100%; }

.wh-split {
    display: grid;
    gap: 3rem;
    padding-top: 2.75rem;
}
@media (min-width: 960px) {
    .wh-split {
        grid-template-columns: minmax(0, 1fr) 17rem;
        gap: 4.5rem;
        align-items: start;
    }
}
.wh-split-form {
    display: grid;
    gap: 3rem;
    padding-top: 2.5rem;
}
@media (min-width: 900px) {
    .wh-split-form {
        grid-template-columns: minmax(16rem, .85fr) minmax(0, 1.15fr);
        gap: 4.5rem;
        align-items: start;
    }
}

.wh-section-head {
    display: flex;
    flex-wrap: wrap;
    align-items: baseline;
    justify-content: space-between;
    gap: .75rem 1.5rem;
    margin-bottom: 1.25rem;
}
.wh-section-head h2 {
    margin: 0;
    font-family: var(--wh-font-display);
    font-size: clamp(1.35rem, 3vw, 1.85rem);
    font-weight: 800;
    letter-spacing: -.04em;
    text-transform: uppercase;
}
.wh-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 0;
    border: 1px solid var(--wh-line-strong);
}
.wh-filters a {
    min-height: 2.4rem;
    padding: .45rem .9rem;
    display: inline-flex;
    align-items: center;
    font-family: var(--wh-font-display);
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--wh-ink);
    text-decoration: none;
    border-right: 1px solid var(--wh-line-strong);
    background: #fff;
}
.wh-filters a:last-child { border-right: 0; }
.wh-filters a.is-on { background: var(--wh-ink); color: #fff; }

.wh-tx {
    list-style: none;
    margin: 0;
    padding: 0;
    border-top: 1px solid var(--wh-line-strong);
}
.wh-tx li {
    display: grid;
    grid-template-columns: 3.2rem minmax(0, 1fr) auto;
    gap: .85rem;
    align-items: start;
    padding: 1.15rem 0;
    border-bottom: 1px solid var(--wh-line);
}
@media (min-width: 640px) {
    .wh-tx li {
        grid-template-columns: 3.6rem minmax(0, 1fr) 8.5rem auto;
        align-items: center;
    }
}
.wh-dir {
    font-family: var(--wh-font-display);
    font-size: .62rem;
    font-weight: 800;
    letter-spacing: .12em;
    text-transform: uppercase;
    padding-top: .2rem;
}
.wh-dir-in { color: var(--wh-ink); }
.wh-dir-out { color: var(--wh-ink-3); }
.wh-tx-title {
    margin: 0;
    font-size: .95rem;
    font-weight: 600;
    letter-spacing: -.01em;
}
.wh-tx-meta {
    margin: .25rem 0 0;
    font-size: .75rem;
    color: var(--wh-ink-3);
}
.wh-tx-amount {
    margin: 0;
    font-family: var(--wh-font-display);
    font-size: 1rem;
    font-weight: 800;
    letter-spacing: -.03em;
    text-align: right;
    white-space: nowrap;
}
.wh-tx-amount.is-out { color: var(--wh-ink-2); }
.wh-status {
    justify-self: end;
    font-family: var(--wh-font-display);
    font-size: .6rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    padding: .28rem .45rem;
    border: 1px solid var(--wh-line-strong);
    color: var(--wh-ink);
    background: #fff;
    white-space: nowrap;
}
.wh-status.is-pending { border-style: dashed; color: var(--wh-ink-2); }
.wh-status.is-failed { background: var(--wh-ink); color: #fff; }

.wh-empty {
    padding: 3rem 0 2rem;
    border-top: 1px solid var(--wh-line-strong);
    border-bottom: 1px solid var(--wh-line);
}
.wh-empty h3 {
    margin: 0;
    font-family: var(--wh-font-display);
    font-size: 1.15rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: -.03em;
}
.wh-empty p { margin: .5rem 0 0; color: var(--wh-ink-2); font-size: .9rem; max-width: 28rem; }

.wh-pager { margin-top: 1.5rem; }

.wh-rail h2 {
    margin: 0 0 1rem;
    font-family: var(--wh-font-display);
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .14em;
    text-transform: uppercase;
}
.wh-rail p { margin: 0 0 1.25rem; font-size: .85rem; line-height: 1.5; color: var(--wh-ink-2); }
.wh-wd {
    list-style: none;
    margin: 0;
    padding: 0;
    border-top: 1px solid var(--wh-line);
}
.wh-wd li {
    padding: .85rem 0;
    border-bottom: 1px solid var(--wh-line);
}
.wh-wd strong {
    display: block;
    font-family: var(--wh-font-display);
    font-size: .9rem;
    font-weight: 800;
    letter-spacing: -.02em;
}
.wh-wd span { display: block; margin-top: .2rem; font-size: .72rem; color: var(--wh-ink-3); }

.wh-note-list {
    margin: 1.5rem 0 0;
    padding: 0;
    list-style: none;
    border-top: 1px solid var(--wh-line);
}
.wh-note-list li {
    padding: .8rem 0;
    border-bottom: 1px solid var(--wh-line);
    font-size: .85rem;
    line-height: 1.45;
    color: var(--wh-ink-2);
}
.wh-note-list b { color: var(--wh-ink); font-weight: 600; }

.wh-form { display: flex; flex-direction: column; gap: 1.5rem; }
.wh-field { display: flex; flex-direction: column; gap: .4rem; }
.wh-field label {
    font-family: var(--wh-font-display);
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--wh-ink);
}
.wh-field .hint { margin: 0; font-size: .75rem; color: var(--wh-ink-3); }
.wh-field .err { margin: 0; font-size: .75rem; color: var(--wh-ink); font-weight: 600; }
.wh-input,
.wh-select {
    width: 100%;
    min-height: 3rem;
    padding: .7rem .85rem;
    border: 1px solid var(--wh-line-strong);
    border-radius: 0;
    background: #fff;
    color: var(--wh-ink);
    font-size: 1rem;
    font-family: var(--wh-font-text);
}
.wh-input:focus,
.wh-select:focus {
    outline: 2px solid var(--wh-ink);
    outline-offset: 2px;
}
.wh-input.is-invalid { border-width: 2px; }
.wh-amount-wrap { position: relative; }
.wh-amount-wrap span {
    position: absolute;
    left: .85rem;
    top: 50%;
    transform: translateY(-50%);
    font-family: var(--wh-font-display);
    font-weight: 700;
    font-size: .85rem;
    pointer-events: none;
}
.wh-amount-wrap .wh-input { padding-left: 2.5rem; font-family: var(--wh-font-display); font-weight: 700; font-size: 1.2rem; letter-spacing: -.03em; }
.wh-summary {
    display: grid;
    gap: .55rem;
    padding: 1rem 0;
    border-top: 1px solid var(--wh-line);
    border-bottom: 1px solid var(--wh-line);
    font-size: .85rem;
}
.wh-summary div { display: flex; justify-content: space-between; gap: 1rem; }
.wh-summary dt { color: var(--wh-ink-3); }
.wh-summary dd { margin: 0; font-family: var(--wh-font-display); font-weight: 700; }
.wh-back {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    margin-bottom: 1.25rem;
    font-family: var(--wh-font-display);
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--wh-ink-2);
    text-decoration: none;
}
.wh-back:hover { color: var(--wh-ink); }

.wh-dialog {
    width: calc(100% - 2rem);
    max-width: 28rem;
    margin: auto;
    padding: 0;
    border: 1px solid #111;
    background: #fff;
    color: #111;
    box-shadow: none;
    border-radius: 0;
}
.wh-dialog:not([open]) { display: none; }
.wh-dialog[open] { animation: wh-dialog-in 280ms var(--wh-ease) both; }
.wh-dialog::backdrop {
    background: rgba(17, 17, 17, .48);
    animation: wh-fade-in 220ms ease both;
}
.wh-dialog-body { padding: 1.5rem 1.35rem 1.35rem; }
@media (min-width: 480px) {
    .wh-dialog-body { padding: 1.85rem 1.75rem 1.5rem; }
}
.wh-dialog h2 {
    margin: 0;
    font-family: var(--wh-font-display);
    font-size: 1.45rem;
    font-weight: 800;
    letter-spacing: -.04em;
    text-transform: uppercase;
}
.wh-dialog .wh-dialog-copy {
    margin: .55rem 0 1.25rem;
    font-size: .85rem;
    color: var(--wh-ink-2);
    line-height: 1.45;
}
.wh-rows { border-top: 1px solid var(--wh-line); }
.wh-rows div {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    padding: .7rem 0;
    border-bottom: 1px solid var(--wh-line);
    font-size: .85rem;
}
.wh-rows dt { color: var(--wh-ink-3); }
.wh-rows dd { margin: 0; font-weight: 600; text-align: right; }
.wh-rows .is-amount dd {
    font-family: var(--wh-font-display);
    font-size: 1.15rem;
    font-weight: 800;
    letter-spacing: -.03em;
}
.wh-dialog-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: .5rem;
    margin-top: 1.25rem;
}
.wh-progress {
    margin: 1rem 0 0;
}
.wh-progress-track {
    height: 2px;
    background: var(--wh-line);
    overflow: hidden;
}
.wh-progress-bar {
    height: 100%;
    width: 0;
    background: #111;
    transform-origin: left;
}
.wh-progress p {
    margin: .6rem 0 0;
    font-size: .75rem;
    color: var(--wh-ink-3);
}

.wh-notice-host {
    position: fixed;
    top: var(--wh-notice-top);
    right: 1.15rem;
    z-index: 50;
    display: flex;
    flex-direction: column;
    gap: .5rem;
    width: min(22.5rem, calc(100vw - 1.5rem));
    pointer-events: none;
}
.wh-notice {
    pointer-events: auto;
    background: #fff;
    border: 1px solid #111;
    padding: .85rem 1rem .9rem;
    transform: translate3d(12px, -8px, 0);
    opacity: 0;
    transition: transform 240ms var(--wh-ease), opacity 200ms ease;
}
.wh-notice.is-in {
    transform: translate3d(0, 0, 0);
    opacity: 1;
}
.wh-notice.is-out {
    transform: translate3d(8px, -6px, 0);
    opacity: 0;
}
.wh-notice-kicker {
    margin: 0;
    font-family: var(--wh-font-display);
    font-size: .62rem;
    font-weight: 800;
    letter-spacing: .14em;
    text-transform: uppercase;
}
.wh-notice-title {
    margin: .2rem 0 0;
    font-family: var(--wh-font-display);
    font-size: .92rem;
    font-weight: 800;
    letter-spacing: -.02em;
    text-transform: uppercase;
}
.wh-notice-msg {
    margin: .3rem 0 0;
    font-size: .78rem;
    line-height: 1.4;
    color: var(--wh-ink-2);
}
.wh-notice-close {
    position: absolute;
    top: .45rem;
    right: .45rem;
    width: 1.75rem;
    height: 1.75rem;
    border: 0;
    background: transparent;
    color: #111;
    font-size: 1.1rem;
    line-height: 1;
    cursor: pointer;
}
.wh-notice { position: relative; padding-right: 2.2rem; }

@keyframes wh-dialog-in {
    from { opacity: 0; transform: translateY(10px) scale(.985); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}
@keyframes wh-fade-in {
    from { opacity: 0; }
    to { opacity: 1; }
}
@media (prefers-reduced-motion: reduce) {
    .wh-dialog[open], .wh-dialog::backdrop, .wh-notice { animation: none; transition: none; }
}
@media (max-width: 640px) {
    .wh-notice-host {
        right: .75rem;
        left: .75rem;
        width: auto;
    }
    .wh-dialog-actions { grid-template-columns: 1fr; }
    .wh-tx li { grid-template-columns: 2.6rem minmax(0, 1fr); }
    .wh-tx-amount, .wh-status { grid-column: 2; justify-self: start; text-align: left; }
}
</style>
