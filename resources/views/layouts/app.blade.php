<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'testimad') }} — @yield('title', 'POS')</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen text-slate-800">
    <nav class="bg-slate-900 text-white shadow">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ route('tables.index') }}" class="text-xl font-bold tracking-tight">
                    🍽️ testimad
                </a>
                <div class="flex gap-6 text-sm">
                    <a href="{{ route('tables.index') }}"
                       class="{{ request()->routeIs('tables.*') ? 'text-amber-400' : 'text-slate-300 hover:text-white' }}">
                        Tables
                    </a>
                    <a href="{{ route('products.index') }}"
                       class="{{ request()->routeIs('products.*') ? 'text-amber-400' : 'text-slate-300 hover:text-white' }}">
                        Menu
                    </a>
                </div>
            </div>
            <div class="text-xs text-slate-400">{{ now()->format('D, M j · H:i') }}</div>
        </div>
    </nav>

    @if (session('status'))
        <div class="max-w-7xl mx-auto px-6 mt-4">
            <div class="bg-emerald-100 border border-emerald-300 text-emerald-800 px-4 py-2 rounded text-sm">
                {{ session('status') }}
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="max-w-7xl mx-auto px-6 mt-4">
            <div class="bg-rose-100 border border-rose-300 text-rose-800 px-4 py-2 rounded text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <main class="max-w-7xl mx-auto px-6 py-8">
        @yield('content')
    </main>
</body>
</html>
