<div id="sh-loading" class="sh-loading" role="status" aria-label="Memuat SkillHub">
    <div class="sh-loading__inner">
        <div class="sh-loading__box" aria-hidden="true">
            <div class="sh-loading__cube"><div class="sh-loading__cube__inner"></div></div>
            <div class="sh-loading__cube"><div class="sh-loading__cube__inner"></div></div>
            <div class="sh-loading__cube"><div class="sh-loading__cube__inner"></div></div>
        </div>
        <span class="sh-loading__brand">SkillHub</span>
    </div>
</div>

<style>
    .sh-loading {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.45);
        -webkit-backdrop-filter: blur(8px);
        backdrop-filter: blur(8px);
        opacity: 0;
        transition: opacity .3s ease, visibility .3s ease;
    }

    .sh-loading.is-visible { opacity: 1; }

    .sh-loading.is-hidden {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
    }

    .sh-loading__inner {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1.25rem;
    }

    .sh-loading__box {
        --uib-size: 30px;
        --uib-color: #0a0a0a;
        --uib-speed: 1.75s;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        width: var(--uib-size);
        height: calc(var(--uib-size) * 0.6);
    }

    .sh-loading__cube {
        flex-shrink: 0;
        width: calc(var(--uib-size) * 0.2);
        height: calc(var(--uib-size) * 0.2);
        animation: sh-jump var(--uib-speed) ease-in-out infinite;
    }

    .sh-loading__cube__inner {
        display: block;
        height: 100%;
        width: 100%;
        border-radius: 25%;
        background-color: var(--uib-color);
        transform-origin: center bottom;
        animation: sh-morph var(--uib-speed) ease-in-out infinite;
        transition: background-color .3s ease;
    }

    .sh-loading__cube:nth-child(2),
    .sh-loading__cube:nth-child(2) .sh-loading__cube__inner {
        animation-delay: calc(var(--uib-speed) * -0.36);
    }

    .sh-loading__cube:nth-child(3),
    .sh-loading__cube:nth-child(3) .sh-loading__cube__inner {
        animation-delay: calc(var(--uib-speed) * -0.2);
    }

    .sh-loading__brand {
        font-family: 'Montserrat', 'Helvetica Neue', Arial, sans-serif;
        font-weight: 800;
        font-size: .7rem;
        letter-spacing: .22em;
        text-transform: uppercase;
        color: #0a0a0a;
    }

    @keyframes sh-jump {
        0%   { transform: translateY(0px); }
        30%  { transform: translateY(0px); animation-timing-function: ease-out; }
        50%  { transform: translateY(-200%); animation-timing-function: ease-in; }
        75%  { transform: translateY(0px); animation-timing-function: ease-in; }
    }

    @keyframes sh-morph {
        0%   { transform: scaleY(1); }
        10%  { transform: scaleY(1); }
        20%, 25% { transform: scaleY(0.6) scaleX(1.3); animation-timing-function: ease-in-out; }
        30%  { transform: scaleY(1.15) scaleX(0.9); animation-timing-function: ease-in-out; }
        40%  { transform: scaleY(1); }
        70%, 85%, 100% { transform: scaleY(1); }
        75%  { transform: scaleY(0.8) scaleX(1.2); }
    }

    /* Gentle full-page fade-in on every load — no jarring swap. */
    body:not([data-sh-no-loader]) { animation: sh-page-in .5s cubic-bezier(0.16, 1, 0.3, 1) both; }

    @keyframes sh-page-in {
        from { opacity: 0; }
        to   { opacity: 1; }
    }

    @media (prefers-reduced-motion: reduce) {
        body { animation: none; }
        .sh-loading { transition: none; }
        .sh-loading__cube,
        .sh-loading__cube__inner { animation: none; }
    }
</style>

<script>
    (function () {
        if (document.body && document.body.hasAttribute('data-sh-no-loader')) return;

        var MARKUP = '<div id="sh-loading" class="sh-loading" role="status" aria-label="Memuat SkillHub">'
            + '<div class="sh-loading__inner">'
            + '<div class="sh-loading__box" aria-hidden="true">'
            + '<div class="sh-loading__cube"><div class="sh-loading__cube__inner"></div></div>'
            + '<div class="sh-loading__cube"><div class="sh-loading__cube__inner"></div></div>'
            + '<div class="sh-loading__cube"><div class="sh-loading__cube__inner"></div></div>'
            + '</div>'
            + '<span class="sh-loading__brand">SkillHub</span>'
            + '</div></div>';

        function show() {
            if (document.getElementById('sh-loading')) return;
            document.body.insertAdjacentHTML('afterbegin', MARKUP);
            requestAnimationFrame(function () {
                var el = document.getElementById('sh-loading');
                if (el) el.classList.add('is-visible');
            });
        }

        function hide() {
            var el = document.getElementById('sh-loading');
            if (!el || el.classList.contains('is-hidden')) return;
            el.classList.add('is-hidden');
            setTimeout(function () { if (el && el.parentNode) el.remove(); }, 400);
        }

        // Reveal + dismiss the server-rendered loader once this page finishes loading.
        var existing = document.getElementById('sh-loading');
        if (existing) existing.classList.add('is-visible');

        if (document.readyState === 'complete') {
            setTimeout(hide, 400);
        } else {
            window.addEventListener('load', hide);
            setTimeout(hide, 2500);
        }

        // Show instantly on internal navigation so it never feels late.
        function internalLink(a) {
            if (!a || a.target === '_blank' || a.hasAttribute('download')) return false;
            var href = a.getAttribute('href');
            if (!href || href.charAt(0) === '#' || href.indexOf('javascript:') === 0) return false;
            return a.origin === location.origin;
        }

        document.addEventListener('click', function (e) {
            if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
            var a = e.target.closest('a');
            if (a && internalLink(a)) show();
        }, true);

        document.addEventListener('submit', function (e) {
            if (e.defaultPrevented) return;
            var form = e.target;
            if (form && form.tagName === 'FORM') show();
        }, true);
    })();
</script>
