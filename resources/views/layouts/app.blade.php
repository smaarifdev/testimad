<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'testimad') }} — @yield('title', 'Bistro')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;0,9..144,800;1,9..144,400&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['Fraunces', 'serif'],
                        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
                    },
                    colors: {
                        cream: '#f6efe2',
                        parchment: '#ebe0c8',
                        ink: '#15110d',
                        coal: '#1f1a14',
                        ember: '#d97706',
                        copper: '#b8753d',
                        wine: '#7a1f2b',
                    },
                    boxShadow: {
                        'glow-amber': '0 0 0 4px rgba(217,119,6,0.15), 0 8px 24px -8px rgba(217,119,6,0.4)',
                        'glow-emerald': '0 0 0 4px rgba(16,185,129,0.15), 0 8px 24px -8px rgba(16,185,129,0.4)',
                    },
                    animation: {
                        'pulse-slow': 'pulse 2.5s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                },
            },
        };
    </script>
    <style>
        body {
            background-color: #15110d;
            background-image:
                radial-gradient(circle at 20% 0%, rgba(184, 117, 61, 0.08), transparent 40%),
                radial-gradient(circle at 80% 100%, rgba(122, 31, 43, 0.08), transparent 40%);
            background-attachment: fixed;
            color: #f6efe2;
        }
        .grain {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            opacity: 0.04;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
        }
        .ticket {
            background-color: #f6efe2;
            color: #15110d;
            background-image: repeating-linear-gradient(0deg, transparent, transparent 28px, rgba(21,17,13,0.06) 28px, rgba(21,17,13,0.06) 29px);
        }
        .ticket-edge {
            mask-image: radial-gradient(circle at 8px 50%, transparent 6px, black 7px);
            -webkit-mask-image: radial-gradient(circle at 8px 50%, transparent 6px, black 7px);
        }
    </style>
</head>
<body class="font-sans min-h-screen relative">
    <div class="grain"></div>

    <header class="relative z-10 border-b border-white/5 backdrop-blur bg-coal/80">
        <div class="max-w-7xl mx-auto px-8 py-5 flex items-center justify-between">
            <a href="{{ route('tables.index') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-ember to-wine flex items-center justify-center shadow-lg ring-1 ring-white/10">
                    <svg class="w-5 h-5 text-cream" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 11h18M5 11a7 7 0 1114 0M12 18v3M9 21h6"/>
                    </svg>
                </div>
                <div class="leading-tight">
                    <div class="font-display font-bold text-xl tracking-tight">testimad</div>
                    <div class="text-[10px] uppercase tracking-[0.2em] text-copper">Bistro · Est. 2026</div>
                </div>
            </a>

            <nav class="flex items-center gap-1 bg-black/30 rounded-full p-1 ring-1 ring-white/5">
                <a href="{{ route('tables.index') }}"
                   class="px-5 py-2 text-sm rounded-full transition {{ request()->routeIs('tables.*') ? 'bg-cream text-ink font-semibold shadow-md' : 'text-cream/70 hover:text-cream' }}">
                    Floor
                </a>
                <a href="{{ route('products.index') }}"
                   class="px-5 py-2 text-sm rounded-full transition {{ request()->routeIs('products.*') ? 'bg-cream text-ink font-semibold shadow-md' : 'text-cream/70 hover:text-cream' }}">
                    Menu
                </a>
            </nav>

            <div class="text-right">
                <div class="font-display text-lg leading-none">{{ now()->format('H:i') }}</div>
                <div class="text-[10px] uppercase tracking-[0.2em] text-cream/40 mt-1">{{ now()->format('l · M j') }}</div>
            </div>
        </div>
    </header>

    @if (session('status'))
        <div class="relative z-10 max-w-7xl mx-auto px-8 mt-6">
            <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 px-5 py-3 rounded-lg text-sm flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('status') }}
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="relative z-10 max-w-7xl mx-auto px-8 mt-6">
            <div class="bg-rose-500/10 border border-rose-500/30 text-rose-300 px-5 py-3 rounded-lg text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <main class="relative z-10 max-w-7xl mx-auto px-8 py-10">
        @yield('content')
    </main>

    <footer class="relative z-10 mt-16 border-t border-white/5 py-8 text-center text-xs text-cream/30 tracking-[0.2em] uppercase">
        ✦ Testimad Bistro ✦
    </footer>
</body>
</html>
