{{-- Ilustraciones originales de producto (6 variantes). Se dibujan una vez y se reutilizan con <use>. --}}
<svg width="0" height="0" class="absolute" aria-hidden="true" focusable="false">
    <defs>
        <linearGradient id="kc-g0" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#2a1740"/><stop offset="1" stop-color="#4c2a78"/></linearGradient>
        <linearGradient id="kc-g1" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#fdeef5"/><stop offset="1" stop-color="#f7c3dc"/></linearGradient>
        <linearGradient id="kc-g2" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#f3edfb"/><stop offset="1" stop-color="#d6c3f3"/></linearGradient>
        <linearGradient id="kc-g3" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#1b1124"/><stop offset="1" stop-color="#3a2452"/></linearGradient>
        <linearGradient id="kc-g4" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#e9e0f8"/><stop offset="1" stop-color="#c7b0ee"/></linearGradient>
        <linearGradient id="kc-g5" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#fbe6f0"/><stop offset="1" stop-color="#e7dcf8"/></linearGradient>
        <linearGradient id="kc-planeta" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#b18cf0"/><stop offset="1" stop-color="#6b3fa0"/></linearGradient>
        <linearGradient id="kc-corazon" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#f06aa8"/><stop offset="1" stop-color="#c93479"/></linearGradient>
        <mask id="kc-luna-mask"><rect width="400" height="400" fill="#fff"/><circle cx="248" cy="168" r="92" fill="#000"/></mask>
        <symbol id="kc-estrellita" viewBox="-10 -10 20 20"><path d="M0-10 2.4-3.2 9.5-3.1 3.8 1.2 5.9 8.1 0 4-5.9 8.1-3.8 1.2-9.5-3.1-2.4-3.2z"/></symbol>
        <symbol id="kc-brillito" viewBox="-10 -10 20 20"><path d="M0-10C.8-2.5 2.5-.8 10 0 2.5.8.8 2.5 0 10-.8 2.5-2.5.8-10 0-2.5-.8-.8-2.5 0-10z"/></symbol>
    </defs>

    {{-- 0: Luna dormilona --}}
    <symbol id="kc-arte-0" viewBox="0 0 400 400">
        <rect width="400" height="400" fill="url(#kc-g0)"/>
        <circle cx="200" cy="210" r="150" fill="#fff" opacity=".05"/>
        <g fill="#f06aa8"><use href="#kc-brillito" x="60" y="70" width="26" height="26"/><use href="#kc-brillito" x="310" y="300" width="18" height="18"/></g>
        <g fill="#c9b2f5" opacity=".8"><use href="#kc-estrellita" x="320" y="80" width="16" height="16"/><use href="#kc-estrellita" x="80" y="300" width="12" height="12"/><circle cx="110" cy="120" r="2.5"/><circle cx="300" cy="200" r="2"/></g>
        <circle cx="190" cy="215" r="112" fill="#f3e8ff" mask="url(#kc-luna-mask)"/>
        <g stroke="#2a1740" stroke-width="5" fill="none" stroke-linecap="round">
            <path d="M108 226q8 7 16 0"/>
            <path d="M146 250q8 7 16 0"/>
            <path d="M120 270q10 8 20 2" stroke-width="4.5"/>
        </g>
        <circle cx="104" cy="252" r="10" fill="#f06aa8" opacity=".45"/>
    </symbol>

    {{-- 1: Corazón con alitas --}}
    <symbol id="kc-arte-1" viewBox="0 0 400 400">
        <rect width="400" height="400" fill="url(#kc-g1)"/>
        <g fill="#fff" opacity=".9">
            <path d="M108 190c-40-18-62 6-58 26 22-6 38 0 56 14z"/>
            <path d="M292 190c40-18 62 6 58 26-22-6-38 0-56 14z"/>
        </g>
        <path d="M200 304C120 254 95 209 112 169c16-37 66-40 88-5 22-35 72-32 88 5 17 40-8 85-88 135z" fill="url(#kc-corazon)"/>
        <path d="M140 170c6-14 20-20 32-16" stroke="#fff" stroke-opacity=".55" stroke-width="7" stroke-linecap="round" fill="none"/>
        <circle cx="175" cy="214" r="7" fill="#2a1740"/><circle cx="225" cy="214" r="7" fill="#2a1740"/>
        <path d="M192 232q8 7 16 0" stroke="#2a1740" stroke-width="4.5" fill="none" stroke-linecap="round"/>
        <g fill="#6b3fa0"><use href="#kc-brillito" x="300" y="80" width="28" height="28"/><use href="#kc-estrellita" x="70" y="300" width="16" height="16"/></g>
    </symbol>

    {{-- 2: Estrella fugaz --}}
    <symbol id="kc-arte-2" viewBox="0 0 400 400">
        <rect width="400" height="400" fill="url(#kc-g2)"/>
        <path d="M40 320 140 240" stroke="#f06aa8" stroke-width="10" stroke-linecap="round" opacity=".5"/>
        <path d="M60 350 150 275" stroke="#9b74e8" stroke-width="6" stroke-linecap="round" opacity=".5"/>
        <polygon points="200,107 228.2,173.2 299.9,179.6 245.7,226.8 261.7,296.9 200,260 138.3,296.9 154.3,226.8 100.1,179.6 171.8,173.2" fill="#6b3fa0" stroke="#6b3fa0" stroke-width="18" stroke-linejoin="round"/>
        <circle cx="180" cy="210" r="6.5" fill="#fff"/><circle cx="220" cy="210" r="6.5" fill="#fff"/>
        <circle cx="166" cy="228" r="8" fill="#f06aa8" opacity=".6"/><circle cx="234" cy="228" r="8" fill="#f06aa8" opacity=".6"/>
        <g fill="#e64c95"><use href="#kc-brillito" x="300" y="70" width="30" height="30"/><use href="#kc-brillito" x="320" y="300" width="18" height="18"/></g>
    </symbol>

    {{-- 3: Lazo de terciopelo --}}
    <symbol id="kc-arte-3" viewBox="0 0 400 400">
        <rect width="400" height="400" fill="url(#kc-g3)"/>
        <g fill="#c9b2f5" opacity=".5"><circle cx="70" cy="90" r="3"/><circle cx="330" cy="110" r="2.5"/><circle cx="90" cy="320" r="2"/><circle cx="320" cy="320" r="3"/></g>
        <path d="M196 200c-40-60-120-70-124-20-4 46 70 54 124 20z" fill="#f06aa8"/>
        <path d="M204 200c40-60 120-70 124-20 4 46-70 54-124 20z" fill="#f06aa8"/>
        <path d="M190 212c-20 40-44 80-60 96l34 6 34-80zM210 212c20 40 44 80 60 96l-34 6-34-80z" fill="#c93479"/>
        <rect x="176" y="178" width="48" height="46" rx="18" fill="#ff8cc0"/>
        <path d="M110 176c8-18 26-26 44-22" stroke="#fff" stroke-opacity=".5" stroke-width="6" stroke-linecap="round" fill="none"/>
        <g fill="#c9b2f5"><use href="#kc-brillito" x="300" y="60" width="26" height="26"/></g>
    </symbol>

    {{-- 4: Nube con lluvia de estrellas --}}
    <symbol id="kc-arte-4" viewBox="0 0 400 400">
        <rect width="400" height="400" fill="url(#kc-g4)"/>
        <path d="M110 250a52 52 0 0 1 8-103 72 72 0 0 1 136-14 58 58 0 0 1 34 117z" fill="#fff"/>
        <circle cx="178" cy="198" r="6.5" fill="#2a1740"/><circle cx="224" cy="198" r="6.5" fill="#2a1740"/>
        <path d="M193 214q8 7 16 0" stroke="#2a1740" stroke-width="4.5" fill="none" stroke-linecap="round"/>
        <circle cx="160" cy="214" r="9" fill="#f06aa8" opacity=".45"/><circle cx="242" cy="214" r="9" fill="#f06aa8" opacity=".45"/>
        <g fill="#6b3fa0"><use href="#kc-estrellita" x="132" y="278" width="20" height="20"/><use href="#kc-estrellita" x="196" y="300" width="16" height="16"/><use href="#kc-estrellita" x="252" y="276" width="20" height="20"/></g>
        <g fill="#e64c95"><use href="#kc-brillito" x="60" y="60" width="24" height="24"/><use href="#kc-brillito" x="320" y="320" width="20" height="20"/></g>
    </symbol>

    {{-- 5: Planeta con anillo --}}
    <symbol id="kc-arte-5" viewBox="0 0 400 400">
        <rect width="400" height="400" fill="url(#kc-g5)"/>
        <ellipse cx="200" cy="210" rx="150" ry="42" fill="none" stroke="#e64c95" stroke-width="14" transform="rotate(-18 200 210)" opacity=".9"/>
        <circle cx="200" cy="205" r="92" fill="url(#kc-planeta)"/>
        <path d="M118 245c50 12 130-8 168-48" fill="none" stroke="#e64c95" stroke-width="14" stroke-linecap="round"/>
        <circle cx="168" cy="170" r="14" fill="#fff" opacity=".18"/><circle cx="235" cy="235" r="9" fill="#fff" opacity=".14"/>
        <g fill="#6b3fa0"><use href="#kc-brillito" x="60" y="60" width="26" height="26"/><use href="#kc-estrellita" x="320" y="90" width="18" height="18"/><use href="#kc-estrellita" x="80" y="320" width="14" height="14"/></g>
    </symbol>
</svg>
