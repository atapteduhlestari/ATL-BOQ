<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Bill Of Quantity') }}</title>

    @fonts

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body {
            height: 100%;
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            background: #ffffff;
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
        }

        #splash {
            position: fixed;
            inset: 0;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            transition: opacity 0.9s ease, visibility 0.9s ease;
            overflow: hidden;
        }

        #splash.hide {
            opacity: 0;
            visibility: hidden;
        }

        /* ===== BACKGROUND: floating orbs ===== */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.35;
            animation: floatOrb 12s ease-in-out infinite;
            pointer-events: none;
        }
        .orb.o1 {
            width: 320px; height: 320px;
            background: radial-gradient(circle, #e0e7ff, transparent 70%);
            top: -80px; left: -60px;
            animation-delay: 0s;
        }
        .orb.o2 {
            width: 260px; height: 260px;
            background: radial-gradient(circle, #fef3c7, transparent 70%);
            bottom: -60px; right: -40px;
            animation-delay: -4s;
        }
        .orb.o3 {
            width: 200px; height: 200px;
            background: radial-gradient(circle, #fce7f3, transparent 70%);
            top: 50%; right: 15%;
            animation-delay: -8s;
        }

        @keyframes floatOrb {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33%      { transform: translate(30px, -20px) scale(1.08); }
            66%      { transform: translate(-20px, 25px) scale(0.95); }
        }

        /* subtle center glow berdenyut */
        #splash::before {
            content: '';
            position: absolute;
            width: 620px;
            height: 620px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(180, 160, 120, 0.08), transparent 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
            animation: pulseGlow 3.5s ease-in-out infinite;
        }

        @keyframes pulseGlow {
            0%, 100% { transform: translate(-50%, -50%) scale(1);   opacity: 0.7; }
            50%      { transform: translate(-50%, -50%) scale(1.1); opacity: 1; }
        }

        /* ===== CONTENT ===== */
        #splash .content {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 36px;
            z-index: 2;
        }

        /* fade bertahap tiap elemen */
        #splash .logo {
            width: 140px;
            height: auto;
            object-fit: contain;
            filter: drop-shadow(0 6px 20px rgba(26, 26, 46, 0.06));
            opacity: 0;
            animation: fadeUp 0.9s cubic-bezier(0.25, 1, 0.5, 1) 0.1s forwards;
        }

        #splash .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 200px;
            opacity: 0;
            animation: fadeUp 0.9s cubic-bezier(0.25, 1, 0.5, 1) 0.5s forwards;
        }
        #splash .divider .line {
            flex: 1; height: 1px;
            background: linear-gradient(90deg, transparent, #cbd5e1);
        }
        #splash .divider .line.right {
            background: linear-gradient(90deg, #cbd5e1, transparent);
        }
        #splash .divider .dot {
            width: 4px; height: 4px;
            border-radius: 50%;
            background: #94a3b8;
        }

        #splash .text-wrap {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            opacity: 0;
            animation: fadeUp 0.9s cubic-bezier(0.25, 1, 0.5, 1) 0.8s forwards;
        }

        #splash .eyebrow {
            font-family: 'Inter', sans-serif;
            font-size: 10px;
            font-weight: 600;
            color: #94a3b8;
            letter-spacing: 8px;
            text-transform: uppercase;
            margin-bottom: 16px;
        }

        #splash .title {
            font-family: 'DM Serif Display', serif;
            font-size: 64px;
            font-weight: 400;
            color: #1a1a2e;
            letter-spacing: -1.5px;
            line-height: 1;
            margin-bottom: 4px;
        }
        #splash .title em {
            font-style: italic;
            color: #4c4c6b;
        }

        #splash .company {
            font-family: 'Inter', sans-serif;
            font-size: 11px;
            font-weight: 500;
            color: #64748b;
            letter-spacing: 6px;
            text-transform: uppercase;
            margin-top: 22px;
        }

        /* ===== PROGRESS BAR ===== */
        #splash .progress {
            width: 220px;
            opacity: 0;
            animation: fadeUp 0.9s cubic-bezier(0.25, 1, 0.5, 1) 1.1s forwards;
            margin-top: 8px;
        }

        #splash .bar {
            width: 100%;
            height: 2px;
            background: #f1f5f9;
            border-radius: 999px;
            overflow: hidden;
            position: relative;
        }

        #splash .bar-fill {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #94a3b8, #1a1a2e);
            border-radius: 999px;
            transition: width 0.3s linear;
        }

        /* shimmer melintas di bar */
        #splash .bar::after {
            content: '';
            position: absolute;
            top: 0; left: -40%;
            width: 40%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.8), transparent);
            animation: shimmer 1.8s ease-in-out infinite;
        }
        @keyframes shimmer {
            0%   { left: -40%; }
            100% { left: 120%; }
        }

        #splash .progress-text {
            font-family: 'Inter', sans-serif;
            font-size: 10px;
            font-weight: 500;
            color: #94a3b8;
            letter-spacing: 2px;
            text-align: center;
            margin-top: 10px;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 480px) {
            #splash .title { font-size: 44px; }
            #splash .logo { width: 110px; }
            #splash .eyebrow { letter-spacing: 5px; font-size: 9px; }
            #splash .company { letter-spacing: 4px; font-size: 10px; }
            #splash .divider { width: 160px; }
        }
    </style>
</head>

<body>
    <div id="splash">
        <!-- floating orbs background -->
        <div class="orb o1"></div>
        <div class="orb o2"></div>
        <div class="orb o3"></div>

        <div class="content">
            <img src="{{ asset('images/atl new logo.png') }}" alt="PT Atap Teduh Lestari" class="logo">

            <div class="divider">
                <span class="line"></span>
                <span class="dot"></span>
                <span class="line right"></span>
            </div>

            <div class="text-wrap">
                <div class="eyebrow">Document</div>
                <div class="title">Bill of <em>Quantity</em></div>
                <div class="company">PT Atap Teduh Lestari</div>
            </div>

            <div class="progress">
                <div class="bar">
                    <div class="bar-fill" id="barFill"></div>
                </div>
                <div class="progress-text" id="progressText">MEMUAT · 0%</div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            var TOTAL_MS = 5000;
            var barFill = document.getElementById('barFill');
            var progressText = document.getElementById('progressText');
            var splash = document.getElementById('splash');

            var start = performance.now();

            function tick(now) {
                var elapsed = now - start;
                var pct = Math.min(100, (elapsed / TOTAL_MS) * 100);

                barFill.style.width = pct + '%';
                progressText.textContent = 'MEMUAT · ' + Math.floor(pct) + '%';

                if (pct < 100) {
                    requestAnimationFrame(tick);
                } else {
                    splash.classList.add('hide');
                    setTimeout(function () {
                        splash.remove();
                        window.location.href = "/atap-standar";
                    }, 900);
                }
            }

            // Prefetch halaman tujuan
            var link = document.createElement('link');
            link.rel = 'prefetch';
            link.href = '/atap-standar';
            document.head.appendChild(link);

            requestAnimationFrame(tick);
        })();
    </script>
</body>
</html>