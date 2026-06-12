<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transiva — @yield('title', 'Transport moderne')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --black:   #0a0a0a;
            --white:   #f5f3ee;
            --accent:  #e8c547;
            --gray:    #1e1e1e;
            --gray2:   #2e2e2e;
            --muted:   #888;
            --danger:  #e85a4f;
            --success: #4caf82;
            --radius:  6px;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--black);
            color: var(--white);
            min-height: 100vh;
        }

        /* NAV */
        nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 2.5rem;
            border-bottom: 1px solid #222;
            position: sticky;
            top: 0;
            background: rgba(10,10,10,0.95);
            backdrop-filter: blur(8px);
            z-index: 100;
        }

        .nav-logo {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--accent);
            text-decoration: none;
            letter-spacing: -0.02em;
        }

        .nav-logo span { color: var(--white); }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            color: var(--muted);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color .2s;
        }

        .nav-links a:hover, .nav-links a.active { color: var(--white); }

        .btn-nav {
            background: var(--accent);
            color: var(--black) !important;
            padding: 0.45rem 1.1rem;
            border-radius: var(--radius);
            font-weight: 600 !important;
        }

        /* MAIN */
        main { min-height: calc(100vh - 64px); }

        /* ALERTS */
        .alert {
            padding: .85rem 1.25rem;
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
            font-size: .9rem;
        }
        .alert-success { background: rgba(76,175,130,.12); border: 1px solid rgba(76,175,130,.3); color: #7dd9a8; }
        .alert-error   { background: rgba(232,90,79,.12);  border: 1px solid rgba(232,90,79,.3);  color: #f08b84; }

        /* CARDS */
        .card {
            background: var(--gray);
            border: 1px solid #2a2a2a;
            border-radius: 12px;
            padding: 1.5rem;
        }

        /* FORMS */
        .form-group { margin-bottom: 1.25rem; }
        label { display: block; font-size: .8rem; font-weight: 500; color: var(--muted); margin-bottom: .4rem; text-transform: uppercase; letter-spacing: .05em; }
        input, select, textarea {
            width: 100%;
            background: #111;
            border: 1px solid #333;
            color: var(--white);
            padding: .75rem 1rem;
            border-radius: var(--radius);
            font-family: inherit;
            font-size: .95rem;
            transition: border-color .2s;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--accent);
        }

        /* BUTTONS */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .7rem 1.5rem;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: .9rem;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: opacity .2s, transform .1s;
        }
        .btn:hover { opacity: .85; transform: translateY(-1px); }
        .btn-primary { background: var(--accent); color: var(--black); }
        .btn-outline { background: transparent; border: 1px solid #444; color: var(--white); }
        .btn-danger  { background: var(--danger); color: var(--white); }
        .btn-sm { padding: .4rem .9rem; font-size: .8rem; }

        /* TABLE */
        table { width: 100%; border-collapse: collapse; font-size: .9rem; }
        th { text-align: left; padding: .75rem 1rem; font-size: .75rem; text-transform: uppercase; letter-spacing: .06em; color: var(--muted); border-bottom: 1px solid #2a2a2a; }
        td { padding: .85rem 1rem; border-bottom: 1px solid #1e1e1e; vertical-align: middle; }
        tr:hover td { background: #141414; }

        /* BADGE */
        .badge {
            display: inline-block;
            padding: .25rem .7rem;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 600;
        }
        .badge-warning { background: rgba(232,197,71,.15); color: var(--accent); }
        .badge-success { background: rgba(76,175,130,.15); color: #4caf82; }
        .badge-danger  { background: rgba(232,90,79,.15);  color: var(--danger); }

        /* UTILITIES */
        .container { max-width: 1100px; margin: 0 auto; padding: 2.5rem 2rem; }
        .page-title { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 2rem; margin-bottom: .4rem; }
        .page-sub   { color: var(--muted); font-size: .95rem; margin-bottom: 2rem; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; }
        .flex { display: flex; align-items: center; }
        .flex-between { display: flex; align-items: center; justify-content: space-between; }
        .gap-1 { gap: .5rem; }
        .gap-2 { gap: 1rem; }
        .mt-1 { margin-top: .5rem; }
        .mt-2 { margin-top: 1rem; }
        .mt-3 { margin-top: 1.5rem; }
        .text-muted { color: var(--muted); font-size: .85rem; }
        .text-accent { color: var(--accent); }

        /* FOOTER */
        footer {
            border-top: 1px solid #1e1e1e;
            padding: 1.5rem 2.5rem;
            text-align: center;
            color: var(--muted);
            font-size: .82rem;
        }

        @media (max-width: 768px) {
            .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
            nav { padding: 1rem 1.25rem; }
            .container { padding: 1.5rem 1rem; }
        }
    </style>
    @yield('styles')
</head>
<body>
    <nav>
        <a href="{{ route('home') }}" class="nav-logo">Trans<span>iva</span></a>
        
        <!-- Simulateur de Rôles -->
        <div style="background: rgba(232,197,71,.08); border: 1px solid rgba(232,197,71,0.25); border-radius: 20px; padding: 0.35rem 0.85rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem;">
            <span style="color: var(--accent); font-weight: 600;">Simuler le Rôle :</span>
            <select onchange="window.location.href='/switch-user/' + this.value" style="background: transparent; border: none; color: var(--white); font-weight: 500; cursor: pointer; padding: 0; width: auto; font-size: 0.8rem;">
                <option value="4" style="background:#111;color:#fff;" {{ auth()->id() == 4 ? 'selected' : '' }}>👤 Voyageur: Fatoumata</option>
                <option value="5" style="background:#111;color:#fff;" {{ auth()->id() == 5 ? 'selected' : '' }}>👤 Voyageur: Ousmane</option>
                <option value="2" style="background:#111;color:#fff;" {{ auth()->id() == 2 ? 'selected' : '' }}>🚌 Opérateur: Sama Transport</option>
                <option value="3" style="background:#111;color:#fff;" {{ auth()->id() == 3 ? 'selected' : '' }}>🚌 Opérateur: Africa Express</option>
                <option value="1" style="background:#111;color:#fff;" {{ auth()->id() == 1 ? 'selected' : '' }}>⚙️ Admin: Directeur Transiva</option>
            </select>
        </div>

        <ul class="nav-links">
            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') || request()->routeIs('trips*') ? 'active' : '' }}">Rechercher</a></li>
            @if(auth()->check())
                @if(auth()->user()->role === 'Traveler')
                    <li><a href="{{ route('voyageur.reservations') }}" class="{{ request()->routeIs('voyageur*') ? 'active' : '' }}">Mon Espace (Billets)</a></li>
                @elseif(auth()->user()->role === 'Operator')
                    <li><a href="{{ route('operator.dashboard') }}" class="btn-nav" style="background: #4caf82; color: #fff !important;">Espace Opérateur</a></li>
                @elseif(auth()->user()->role === 'Admin')
                    <li><a href="{{ route('admin.dashboard') }}" class="btn-nav">Admin</a></li>
                @endif
            @endif
        </ul>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        &copy; {{ date('Y') }} Transiva — Tous droits réservés
    </footer>
</body>
</html>
