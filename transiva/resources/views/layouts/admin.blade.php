<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — @yield('title', 'Transiva')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --black: #0a0a0a; --white: #f5f3ee; --accent: #e8c547;
            --gray: #141414; --gray2: #1e1e1e; --muted: #666;
            --danger: #e85a4f; --success: #4caf82; --radius: 6px;
            --sidebar: 220px;
        }
        body { font-family: 'DM Sans', sans-serif; background: var(--black); color: var(--white); display: flex; min-height: 100vh; }

        /* SIDEBAR */
        aside {
            width: var(--sidebar);
            min-height: 100vh;
            background: var(--gray);
            border-right: 1px solid #1e1e1e;
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            padding: 1.5rem 0;
        }
        .sidebar-logo {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1.3rem;
            color: var(--accent);
            padding: 0 1.5rem 1.5rem;
            border-bottom: 1px solid #222;
            text-decoration: none;
        }
        .sidebar-logo span { color: var(--white); }
        .sidebar-section { padding: .75rem 1rem .25rem; font-size: .68rem; text-transform: uppercase; letter-spacing: .1em; color: #444; margin-top: .5rem; }
        .sidebar-nav { list-style: none; padding: .25rem 0; flex: 1; }
        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: .65rem;
            padding: .65rem 1.5rem;
            color: var(--muted);
            text-decoration: none;
            font-size: .88rem;
            font-weight: 500;
            transition: color .15s, background .15s;
            border-left: 2px solid transparent;
        }
        .sidebar-nav a:hover { color: var(--white); background: rgba(255,255,255,.03); }
        .sidebar-nav a.active { color: var(--white); border-left-color: var(--accent); background: rgba(232,197,71,.06); }
        .sidebar-nav .icon { font-size: 1rem; }
        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #1e1e1e;
            font-size: .78rem;
            color: #444;
        }
        .sidebar-footer a { color: var(--muted); text-decoration: none; }
        .sidebar-footer a:hover { color: var(--white); }

        /* MAIN */
        .admin-main { margin-left: var(--sidebar); flex: 1; padding: 2rem 2.5rem; }
        .page-header { margin-bottom: 2rem; }
        .page-title { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.75rem; }
        .page-sub { color: var(--muted); font-size: .9rem; margin-top: .25rem; }

        /* CARDS */
        .card { background: var(--gray2); border: 1px solid #252525; border-radius: 12px; padding: 1.5rem; }
        .stat-card { background: var(--gray2); border: 1px solid #252525; border-radius: 12px; padding: 1.25rem 1.5rem; }
        .stat-label { font-size: .75rem; text-transform: uppercase; letter-spacing: .06em; color: var(--muted); margin-bottom: .4rem; }
        .stat-value { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 2rem; line-height: 1; }
        .stat-value.accent { color: var(--accent); }

        /* TABLE */
        table { width: 100%; border-collapse: collapse; font-size: .88rem; }
        th { text-align: left; padding: .7rem 1rem; font-size: .72rem; text-transform: uppercase; letter-spacing: .06em; color: var(--muted); border-bottom: 1px solid #252525; }
        td { padding: .8rem 1rem; border-bottom: 1px solid #1a1a1a; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #161616; }

        /* FORMS */
        .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1rem; }
        .form-group { display: flex; flex-direction: column; gap: .35rem; }
        label { font-size: .75rem; font-weight: 500; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; }
        input, select, textarea {
            background: #0d0d0d;
            border: 1px solid #2a2a2a;
            color: var(--white);
            padding: .65rem .9rem;
            border-radius: var(--radius);
            font-family: inherit;
            font-size: .9rem;
            transition: border-color .2s;
        }
        input:focus, select:focus { outline: none; border-color: var(--accent); }

        /* BUTTONS */
        .btn { display: inline-flex; align-items: center; gap: .4rem; padding: .6rem 1.25rem; border-radius: var(--radius); font-weight: 600; font-size: .85rem; cursor: pointer; border: none; text-decoration: none; transition: opacity .2s, transform .1s; }
        .btn:hover { opacity: .85; transform: translateY(-1px); }
        .btn-primary { background: var(--accent); color: var(--black); }
        .btn-outline { background: transparent; border: 1px solid #333; color: var(--white); }
        .btn-danger  { background: var(--danger); color: #fff; }
        .btn-sm { padding: .35rem .75rem; font-size: .78rem; }

        /* BADGE */
        .badge { display: inline-block; padding: .22rem .65rem; border-radius: 20px; font-size: .72rem; font-weight: 600; }
        .badge-warning { background: rgba(232,197,71,.12); color: var(--accent); }
        .badge-success { background: rgba(76,175,130,.12); color: #4caf82; }
        .badge-danger  { background: rgba(232,90,79,.12);  color: var(--danger); }

        /* ALERT */
        .alert { padding: .8rem 1.1rem; border-radius: var(--radius); margin-bottom: 1.25rem; font-size: .88rem; }
        .alert-success { background: rgba(76,175,130,.1); border: 1px solid rgba(76,175,130,.25); color: #7dd9a8; }
        .alert-error   { background: rgba(232,90,79,.1);  border: 1px solid rgba(232,90,79,.25);  color: #f08b84; }

        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; }
        .flex-between { display: flex; align-items: center; justify-content: space-between; }
        .mt-2 { margin-top: 1.25rem; }
        .gap-1 { gap: .5rem; }
        .text-muted { color: var(--muted); }

        @media (max-width: 900px) {
            aside { display: none; }
            .admin-main { margin-left: 0; padding: 1.25rem; }
            .grid-4 { grid-template-columns: 1fr 1fr; }
        }
    </style>
    @yield('styles')
</head>
<body>
<aside>
    <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">Trans<span>iva</span></a>

    <div style="padding: 0.5rem 1rem;">
        <div style="background: rgba(232,197,71,.08); border: 1px solid rgba(232,197,71,0.25); border-radius: 6px; padding: 0.4rem; font-size: 0.72rem; display: flex; flex-direction: column; gap: 0.25rem;">
            <span style="color: var(--accent); font-weight: 600;">Simuler le Rôle :</span>
            <select onchange="window.location.href='/switch-user/' + this.value" style="background: #111; border: 1px solid #333; color: var(--white); font-size: 0.72rem; cursor: pointer; padding: 0.2rem; width: 100%;">
                <option value="4" {{ auth()->id() == 4 ? 'selected' : '' }}>👤 Voyageur: Fatoumata</option>
                <option value="5" {{ auth()->id() == 5 ? 'selected' : '' }}>👤 Voyageur: Ousmane</option>
                <option value="2" {{ auth()->id() == 2 ? 'selected' : '' }}>🚌 Opérateur: Sama Transport</option>
                <option value="3" {{ auth()->id() == 3 ? 'selected' : '' }}>🚌 Opérateur: Africa Express</option>
                <option value="1" {{ auth()->id() == 1 ? 'selected' : '' }}>⚙️ Admin: Directeur Transiva</option>
            </select>
        </div>
    </div>

    <p class="sidebar-section">Principal</p>
    <ul class="sidebar-nav">
        <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="icon">📊</span> Dashboard
        </a></li>
    </ul>

    <p class="sidebar-section">Gestion Marché</p>
    <ul class="sidebar-nav">
        <li><a href="{{ route('admin.operators') }}" class="{{ request()->routeIs('admin.operators*') ? 'active' : '' }}">
            <span class="icon">🚌</span> Opérateurs
        </a></li>
        <li><a href="{{ route('admin.transactions') }}" class="{{ request()->routeIs('admin.transactions*') ? 'active' : '' }}">
            <span class="icon">💰</span> Transactions
        </a></li>
        <li><a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <span class="icon">👥</span> Utilisateurs
        </a></li>
    </ul>

    <div class="sidebar-footer">
        <a href="{{ route('home') }}">← Site public</a>
    </div>
</aside>

<div class="admin-main">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    @yield('content')
</div>
</body>
</html>
