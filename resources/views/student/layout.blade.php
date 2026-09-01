<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
@php
    $siteName = config('app.name', env('APP_NAME', 'Teacher'));
    $pageTitle = View::hasSection('title') ? trim(View::getSection('title') . ' — ' . $siteName) : $siteName;
    $pageDescription = View::hasSection('meta_description')
        ? View::getSection('meta_description')
        : (isset($course) && !empty($course->description) ? Str::limit(strip_tags($course->description), 155)
        : trim('
        منصة تعليمية تقدم أفضل الكورسات والدروس التفاعلية لبناء مهاراتك.
        An educational platform offering top courses and interactive lessons to build your skills.
        '));
    $pageKeywords = View::hasSection('meta_keywords') ? trim(View::getSection('meta_keywords'))
        : trim('
        كورسات, تعليم أونلاين, دورات تدريبية, منصة تعليمية
        Courses, Online Education, Training Courses, E-Learning Platform
        ');
    $pageImage = View::hasSection('meta_image') ?
    (trim(View::getSection('meta_image')) !== '' ?
        asset(trim(View::getSection('meta_image')))
        : asset('icon.png'))
    : asset('icon.png');
    $canonicalUrl = View::hasSection('canonical') ? trim(View::getSection('canonical')) : url()->current();
    $metaRobots = View::hasSection('meta_robots') ? trim(View::getSection('meta_robots')) : 'index, follow';
@endphp
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="keywords" content="{{ $pageKeywords }}">
    <meta name="robots" content="{{ $metaRobots }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $pageImage }}">
    <meta property="og:locale" content="ar_EG">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ $pageImage }}">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.0/css/all.min.css" rel="stylesheet">
    <style>
        /* =====================================================================
           GROVE — Design System
           Warm, natural, earthy palette. Rounded panels, pill controls,
           soft depth instead of heavy shadows. Built for a learning platform
           that should feel calm, trustworthy and human — not corporate.
           ===================================================================== */

        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=DM+Sans:wght@500;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap');

        :root {
            color-scheme: light;

            /* ---- Natural warm palette ---- */
            --color-surface: #F6F4EF;          /* warm sand page background */
            --color-surface-raised: #FFFFFF;    /* cards / panels */
            --color-accent-band: #EFEBE0;       /* hero / footer cream band */

            --color-primary: #2F3B2E;           /* deep forest ink — headings */
            --color-brand: #7A6B4F;             /* muted olive-gold — primary actions */
            --color-brand-strong: #5F5339;      /* hover / pressed */
            --color-brand-tint: rgba(122, 107, 79, 0.10);
            --color-brand-tint-strong: rgba(122, 107, 79, 0.18);

            --color-secondary: #3E6B5A;         /* soft pine green — links, highlights */
            --color-secondary-tint: rgba(62, 107, 90, 0.10);

            --color-text: #2A2620;
            --color-muted: #6B6459;
            --color-faint: #8C8577;

            --color-border: #E5E0D4;
            --color-border-strong: #D6CFBE;

            --color-success: #3E7A4F;
            --color-warning: #A8712A;
            --color-danger: #B0483C;
            --color-success-tint: rgba(62, 122, 79, 0.10);
            --color-warning-tint: rgba(168, 113, 42, 0.10);
            --color-danger-tint: rgba(176, 72, 60, 0.10);

            --color-on-primary: #FBFAF6;
            --color-nav-bg: rgba(246, 244, 239, 0.90);

            /* ---- Type tokens ---- */
            --font-display: 'DM Sans', 'Inter', -apple-system, sans-serif;
            --font-body: 'Inter', -apple-system, sans-serif;
            --font-mono: 'JetBrains Mono', 'Courier New', monospace;

            /* ---- Spacing scale (4px base) ---- */
            --sp-1: 4px;  --sp-2: 8px;  --sp-3: 12px; --sp-4: 16px;
            --sp-6: 24px; --sp-8: 32px; --sp-12: 48px; --sp-16: 64px; --sp-24: 96px;

            --radius-sm: 10px;
            --radius-md: 20px;
            --radius-lg: 28px;
            --radius-pill: 999px;

            --shadow-card: 0 1px 2px rgba(42,38,32,0.05), 0 10px 28px -16px rgba(42,38,32,0.14);
            --shadow-raised: 0 6px 24px -6px rgba(42,38,32,0.20);
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        body {
            margin: 0;
            background: var(--color-surface);
            color: var(--color-text);
            font-family: var(--font-body);
            font-size: 16px;
            line-height: 1.65;
            -webkit-font-smoothing: antialiased;
        }

        img { max-width: 100%; display: block; }
        a { color: inherit; text-decoration: none; }

        :focus-visible {
            outline: 2px solid var(--color-secondary);
            outline-offset: 2px;
            border-radius: 4px;
        }

        ::selection { background: var(--color-brand-tint-strong); color: var(--color-primary); }

        h1, h2, h3, h4 {
            font-family: var(--font-display);
            color: var(--color-primary);
            margin: 0;
            letter-spacing: -0.015em;
        }

        h1 { font-size: 52px; font-weight: 800; line-height: 1.08; }
        h2 { font-size: 34px; font-weight: 700; line-height: 1.18; }
        h3 { font-size: 22px; font-weight: 700; line-height: 1.28; }
        h4 { font-size: 17px; font-weight: 700; line-height: 1.35; }

        @media (max-width: 700px) {
            h1 { font-size: 36px; }
            h2 { font-size: 26px; }
        }

        p { margin: 0; }
        .lede { font-size: 18px; color: var(--color-muted); max-width: 60ch; }
        .text-muted { color: var(--color-muted); }
        .text-faint { color: var(--color-faint); }
        .mono { font-family: var(--font-mono); }

        .wrap { max-width: 1280px; margin: 0 auto; padding: 0 var(--sp-6); }
        @media (max-width: 700px) { .wrap { padding: 0 var(--sp-4); } }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: var(--sp-2);
            font-family: var(--font-mono);
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--color-brand-strong);
        }
        .eyebrow::before {
            content: '';
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--color-brand);
        }

        /* ---------------------------------------------------------------------
           Nav
           --------------------------------------------------------------------- */
        .nav {
            position: sticky;
            top: 0;
            z-index: 40;
            background: var(--color-nav-bg);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--color-border);
        }
        .nav__inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 76px;
        }
        .nav__brand {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 19px;
            color: var(--color-primary);
            display: flex;
            align-items: center;
            gap: var(--sp-2);
        }
        .nav__brand .dot { width: 9px; height: 9px; border-radius: 50%; background: var(--color-brand); }
        .nav__links { display: flex; align-items: center; gap: var(--sp-8); }
        .nav__link {
            font-size: 14.5px;
            font-weight: 600;
            color: var(--color-muted);
            padding: var(--sp-2) 0;
            border-bottom: 2px solid transparent;
            transition: color .15s ease, border-color .15s ease;
        }
        .nav__link:hover { color: var(--color-primary); }
        .nav__link.is-active { color: var(--color-primary); border-bottom-color: var(--color-brand); }
        .nav__actions { display: flex; align-items: center; gap: var(--sp-4); }
        .nav__balance {
            font-family: var(--font-mono);
            font-weight: 700;
            font-size: 13px;
            background: var(--color-brand-tint);
            color: var(--color-brand-strong);
            padding: var(--sp-2) var(--sp-4);
            border-radius: var(--radius-pill);
        }
        .nav__burger {
            display: none;
            flex-direction: column;
            justify-content: space-between;
            width: 24px;
            height: 18px;
            cursor: pointer;
            z-index: 50;
        }
        .nav__burger span {
            display: block;
            width: 100%;
            height: 2px;
            background-color: var(--color-primary);
            border-radius: 2px;
            transition: transform 0.25s ease, opacity 0.25s ease;
        }
        @media (max-width: 700px) {
            .nav__burger {
                display: flex;
                margin: 0 auto;
            }
            .nav__links {
                position: absolute;
                top: 76px;
                left: 0;
                right: 0;
                background: var(--color-nav-bg);
                backdrop-filter: blur(10px);
                border-bottom: 1px solid var(--color-border);
                flex-direction: column;
                align-items: stretch;
                gap: 0;
                padding: var(--sp-4) var(--sp-6);
                opacity: 0;
                visibility: hidden;
                transform: translateY(-10px);
                transition: opacity 0.25s ease, transform 0.25s ease, visibility 0.25s ease;
            }
            .nav__link {
                padding: var(--sp-3) 0;
                border-bottom: 1px solid var(--color-border);
            }
            .nav__link:last-child { border-bottom: none; }
            .nav__actions {
                margin-left: auto;
                margin-right: var(--sp-4);
            }
            .nav__toggle:checked ~ .nav__links {
                opacity: 1;
                visibility: visible;
                transform: translateY(0);
            }
            .nav__toggle:checked ~ .nav__burger span:nth-child(1) { transform: translateY(8px) rotate(45deg); }
            .nav__toggle:checked ~ .nav__burger span:nth-child(2) { opacity: 0; }
            .nav__toggle:checked ~ .nav__burger span:nth-child(3) { transform: translateY(-8px) rotate(-45deg); }
        }

        /* ---------------------------------------------------------------------
           Buttons — pill-shaped
           --------------------------------------------------------------------- */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: var(--sp-2);
            font-family: var(--font-body);
            font-weight: 700;
            font-size: 14.5px;
            padding: var(--sp-3) var(--sp-6);
            border-radius: var(--radius-pill);
            border: 1px solid transparent;
            cursor: pointer;
            transition: transform .12s ease, box-shadow .15s ease, background .15s ease, border-color .15s ease, color .15s ease;
        }
        .btn:active { transform: translateY(1px); }
        .btn--block { width: 100%; }

        .btn--primary {
            background: linear-gradient(180deg, var(--color-brand) 0%, var(--color-brand-strong) 100%);
            color: var(--color-on-primary);
            box-shadow: 0 8px 20px -8px rgba(122,107,79,0.55);
        }
        .btn--primary:hover { box-shadow: 0 10px 26px -8px rgba(122,107,79,0.65); }

        .btn--secondary {
            background: var(--color-secondary);
            color: #FBFAF6;
        }
        .btn--secondary:hover { background: #345A4B; }

        .btn--success { background: var(--color-success); color: #fff; }
        .btn--success:hover { filter: brightness(0.94); }

        .btn--outline {
            background: transparent;
            border-color: var(--color-border-strong);
            color: var(--color-primary);
        }
        .btn--outline:hover { border-color: var(--color-brand); color: var(--color-brand-strong); }

        /* ---------------------------------------------------------------------
           Cards
           --------------------------------------------------------------------- */
        .card {
            background: var(--color-surface-raised);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-card);
        }

        .course-card {
            display: block;
            background: var(--color-surface-raised);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            overflow: hidden;
            box-shadow: var(--shadow-card);
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }
        .course-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-raised);
            border-color: var(--color-border-strong);
        }
        .course-card__media { position: relative; aspect-ratio: 16/10; background: var(--color-accent-band); overflow: hidden; }
        .course-card__media img { width: 100%; height: 100%; object-fit: cover; }
        .course-card__no-thumb {
            width: 100%; height: 100%;
            display: flex; align-items: center; justify-content: center;
            color: var(--color-faint);
            font-family: var(--font-mono);
            font-size: 12px;
        }
        .course-card__price-tag {
            position: absolute;
            top: var(--sp-3); right: var(--sp-3);
            background: var(--color-primary);
            color: #fff;
            font-family: var(--font-mono);
            font-weight: 700;
            font-size: 12.5px;
            padding: var(--sp-1) var(--sp-3);
            border-radius: var(--radius-pill);
        }
        .course-card__price-tag.is-free { background: var(--color-success); }
        .course-card__body { padding: var(--sp-6); }
        .course-card__title { font-family: var(--font-display); font-weight: 700; font-size: 18px; color: var(--color-primary); margin-bottom: var(--sp-2); }
        .course-card__desc {
            color: var(--color-muted);
            font-size: 14px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: var(--sp-4);
        }
        .course-card__meta { border-top: 1px solid var(--color-border); padding-top: var(--sp-3); }

        /* ---------------------------------------------------------------------
           Leader row (label ... value with dotted fill)
           --------------------------------------------------------------------- */
        .leader-row { display: flex; align-items: baseline; gap: var(--sp-2); padding: var(--sp-2) 0; font-size: 14px; }
        .leader-row__label { color: var(--color-muted); font-weight: 600; white-space: nowrap; }
        .leader-row__fill { flex: 1; border-bottom: 1px dotted var(--color-border-strong); margin-bottom: 4px; }
        .leader-row__value { font-family: var(--font-mono); color: var(--color-primary); font-weight: 600; white-space: nowrap; }

        /* ---------------------------------------------------------------------
           Badges / alerts — pill
           --------------------------------------------------------------------- */
        .badge {
            display: inline-flex; align-items: center; gap: var(--sp-2);
            font-size: 12.5px; font-weight: 700;
            padding: var(--sp-1) var(--sp-4);
            border-radius: var(--radius-pill);
        }
        .badge--secondary { background: var(--color-secondary-tint); color: var(--color-secondary); }
        .badge__dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }

        .alert {
            padding: var(--sp-3) var(--sp-6);
            border-radius: var(--radius-pill);
            font-size: 14px;
            font-weight: 600;
            margin-bottom: var(--sp-4);
        }
        .alert-success { background: var(--color-success-tint); color: var(--color-success); }
        .alert-info { background: var(--color-secondary-tint); color: var(--color-secondary); }
        .alert-danger { background: var(--color-danger-tint); color: var(--color-danger); }

        /* ---------------------------------------------------------------------
           Progress bar
           --------------------------------------------------------------------- */
        .progress { height: 8px; background: var(--color-border); border-radius: var(--radius-pill); overflow: hidden; }
        .progress__fill { height: 100%; background: linear-gradient(90deg, var(--color-brand), var(--color-secondary)); border-radius: var(--radius-pill); }

        /* ---------------------------------------------------------------------
           Sections
           --------------------------------------------------------------------- */
        .section { padding: var(--sp-24) 0; }
        .section--tight { padding: var(--sp-12) 0; }
        .section--band { background: var(--color-accent-band); }

        .hero {
            background: var(--color-accent-band);
            padding: var(--sp-24) 0 var(--sp-16);
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute; inset: 0;
            background-image: repeating-linear-gradient(115deg, rgba(122,107,79,0.05) 0 1px, transparent 1px 64px);
            pointer-events: none;
        }
        .hero__inner { position: relative; text-align: center; max-width: 780px; margin: 0 auto; }
        .hero__actions { display: flex; align-items: center; justify-content: center; gap: var(--sp-4); margin-top: var(--sp-8); flex-wrap: wrap; }

        .stats-row { display: flex; justify-content: center; gap: var(--sp-16); margin-top: var(--sp-16); flex-wrap: wrap; }
        .stats-row__item { text-align: center; }
        .stats-row__num { font-family: var(--font-display); font-weight: 800; font-size: 40px; color: var(--color-primary); }
        .stats-row__lbl { font-family: var(--font-mono); font-size: 12px; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-muted); margin-top: var(--sp-1); }

        /* ---------------------------------------------------------------------
           Grid utilities
           --------------------------------------------------------------------- */
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .justify-center { justify-content: center; }
        .gap-2 { gap: var(--sp-2); } .gap-3 { gap: var(--sp-3); } .gap-4 { gap: var(--sp-4); } .gap-6 { gap: var(--sp-6); }
        .mt-2{margin-top:var(--sp-2);} .mt-3{margin-top:var(--sp-3);} .mt-4{margin-top:var(--sp-4);}
        .mt-6{margin-top:var(--sp-6);} .mt-8{margin-top:var(--sp-8);} .mt-12{margin-top:var(--sp-12);}
        .mt-16{margin-top:var(--sp-16);} .mt-24{margin-top:var(--sp-24);}
        .mb-2{margin-bottom:var(--sp-2);} .mb-3{margin-bottom:var(--sp-3);} .mb-4{margin-bottom:var(--sp-4);} .mb-6{margin-bottom:var(--sp-6);}
        .grid { display: grid; }

        /* ---------------------------------------------------------------------
           Footer
           --------------------------------------------------------------------- */
        .footer { background: var(--color-accent-band); padding: var(--sp-16) 0 var(--sp-8); margin-top: var(--sp-24); }
        .footer__top {
            display: grid;
            grid-template-columns: 1.4fr 1fr 1fr;
            gap: var(--sp-12);
            padding-bottom: var(--sp-12);
        }
        .social-links {
            display: flex;
            align-items: center;
            gap: var(--sp-3);
        }
        .social-links__item {
            width: 38px;
            height: 38px;
            border-radius: var(--radius-pill);
            background: var(--color-surface-raised);
            border: 1px solid var(--color-border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-muted);
            font-size: 15px;
            transition: color .15s ease, border-color .15s ease, transform .15s ease;
        }
        .social-links__item:hover {
            color: var(--color-brand-strong);
            border-color: var(--color-brand);
            transform: translateY(-2px);
        }
        @media (max-width: 700px) { .footer__top { grid-template-columns: 1fr; gap: var(--sp-8); } }
        .footer__desc { color: var(--color-muted); font-size: 14px; max-width: 34ch; margin-top: var(--sp-3); }
        .footer__heading { font-family: var(--font-mono); font-size: 12px; text-transform: uppercase; letter-spacing: 0.08em; color: var(--color-faint); margin-bottom: var(--sp-3); }
        .footer__links { display: flex; flex-direction: column; gap: var(--sp-2); }
        .footer__links a { color: var(--color-muted); font-size: 14.5px; transition: color .15s ease; }
        .footer__links a:hover { color: var(--color-brand-strong); }
        .footer__bottom {
            border-top: 1px solid var(--color-border-strong);
            padding-top: var(--sp-6);
            display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: var(--sp-3);
        }

        @media (prefers-reduced-motion: reduce) {
            * { animation: none !important; transition: none !important; }
        }
    </style>
    <style>@yield('style')</style>
</head>
<body>
<nav class="nav">
    <div class="wrap nav__inner">
        <a href="{{ route('home') }}" class="nav__brand">
            <span><img width="16" height="16" src="{{ asset('logo.png') }}" alt="Logo Image"></span>
            <span>{{ config('app.name', env('APP_NAME', 'Teacher')) }}</span>
        </a>
        <input type="checkbox" id="nav-toggle" class="nav__toggle" hidden>
        <label for="nav-toggle" class="nav__burger" aria-label="Toggle Menu">
            <span></span>
            <span></span>
            <span></span>
        </label>
        <div class="nav__links">
            <a class="nav__link @if(request()->routeIs('home')) is-active @endif" href="{{ route('home') }}">Home</a>
            <a class="nav__link @if(request()->routeIs('courses.*')) is-active @endif" href="{{ route('courses.index') }}">Courses</a>
            <a class="nav__link @if(request()->routeIs('about')) is-active @endif" href="{{ route('about') }}">About</a>
        </div>
        <div class="nav__actions">
            @auth
                @if(auth()->user()->student)
                    <span class="nav__balance">$ {{ auth()->user()->student?->balance ?? 0 }}</span>
                @endif
                    <a href="{{ route('dashboard') }}" class="btn btn--primary" style="padding: var(--sp-2) var(--sp-6); font-size:13.5px;">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn--primary" style="padding: var(--sp-2) var(--sp-6); font-size:13.5px;">Login</a>
            @endauth
        </div>
    </div>
</nav>
{{-- Flash Alerts & Toasts Container --}}
@if (session('toast') || session('success') || session('error') || session('status') || $errors->any())
    <div class="wrap" style="margin-top: var(--sp-6);">
        @if (session('toast'))
            @php
                $toast = session('toast');
                $type = is_array($toast) ? ($toast['type'] ?? 'info') : 'info';
                $message = is_array($toast) ? ($toast['message'] ?? '') : $toast;

                $alertClass = match($type) {
                    'success' => 'alert-success',
                    'error', 'danger' => 'alert-danger',
                    default => 'alert-info',
                };
            @endphp
            <div class="alert {{ $alertClass }} flex items-center justify-between" role="alert">
                <div class="flex items-center gap-3">
                    <span class="badge badge--secondary"><span class="badge__dot"></span> {{ ucfirst($type) }}</span>
                    <span>{{ $message }}</span>
                </div>
                <button onclick="this.parentElement.remove()" style="background:none; border:none; cursor:pointer; color:inherit; font-weight:700;">&times;</button>
            </div>
        @endif

        {{-- Success Alert --}}
        @if (session('success'))
            <div class="alert alert-success flex items-center justify-between" role="alert">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" style="background:none; border:none; cursor:pointer; color:inherit; font-weight:700;">&times;</button>
            </div>
        @endif

        {{-- Status Alert (مثل إعادة تعيين كلمة المرور) --}}
        @if (session('status'))
            <div class="alert alert-info flex items-center justify-between" role="alert">
                <span>{{ session('status') }}</span>
                <button onclick="this.parentElement.remove()" style="background:none; border:none; cursor:pointer; color:inherit; font-weight:700;">&times;</button>
            </div>
        @endif

        {{-- Error Alert --}}
        @if (session('error'))
            <div class="alert alert-danger flex items-center justify-between" role="alert">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" style="background:none; border:none; cursor:pointer; color:inherit; font-weight:700;">&times;</button>
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger" role="alert" style="border-radius: var(--radius-md); padding: var(--sp-4) var(--sp-6);">
                <div style="font-weight: 700; margin-bottom: var(--sp-2);">يرجى التصحيح بناءً على الأخطاء التالية:</div>
                <ul style="margin: 0; padding-right: var(--sp-4);">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div>
@endif
<main>
@yield('main')
</main>
<footer class="footer">
    <div class="wrap">
        <div class="footer__top">
            <div>
                <div class="nav__brand">
                    <span><img width="16" height="16" src="{{ asset('logo.png') }}" alt="Logo Image"></span>
                    <span>{{ config('app.name', env('APP_NAME', 'Teacher')) }}</span>
                </div>
                <p class="footer__desc">A calm, structured place to learn — courses, lessons and assessments organized so you always know where you left off.</p>
            </div>
            <div>
                <div class="footer__heading">Explore</div>
                <div class="footer__links">
                    <a href="{{ route('home') }}">Home</a>
                    <a href="{{ route('courses.index') }}">Courses</a>
                    <a href="{{ route('about') }}">About</a>
                </div>
            </div>
            <div>
                <div class="footer__heading">Account</div>
                <div class="footer__links">
                    @auth
                        <a href="{{ route('courses.index') }}">My courses</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Create account</a>
                    @endauth
                </div>
            </div>
        </div>
        <div class="footer__bottom">
                <div class="social-links">
                    <a href="https://facebook.com/" target="_blank" rel="noopener" class="social-links__item" aria-label="Facebook">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="https://twitter.com/" target="_blank" rel="noopener" class="social-links__item" aria-label="Twitter">
                        <i class="fa-brands fa-x-twitter"></i>
                    </a>
                    <a href="https://instagram.com/" target="_blank" rel="noopener" class="social-links__item" aria-label="Instagram">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    <a href="https://youtube.com/" target="_blank" rel="noopener" class="social-links__item" aria-label="Youtube">
                        <i class="fa-brands fa-youtube"></i>
                    </a>
                </div>
            <span class="mono text-faint" style="font-size:12px;">© {{ now()->timezone('Africa/Cairo')->format('Y') }} {{ config('app.name', env('APP_NAME', 'Teacher')) }}. All rights reserved.</span>
            <span class="mono text-faint" style="font-size:12px;">Made by <a href="https://yosef-ayman.github.io"><b><u>Yosef Ayman</u></b></a>.</span>
        </div>
    </div>
</footer>
<script>
@yield('script')
</script>
</body>
</html>
