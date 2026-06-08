<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard Admin - ' . config('app.name', 'Sistem Sekolah') }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
@php
    $adminIcon = function (string $name, string $class = 'h-6 w-6') {
        $paths = [
            'school' => '<path d="m3 10 9-6 9 6"/><path d="M5 10v9"/><path d="M19 10v9"/><path d="M3 19h18"/><path d="M8 14h8"/>',
            'home' => '<path d="m3 10.5 9-7 9 7"/><path d="M5 10v10h5v-6h4v6h5V10"/>',
            'grid' => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>',
            'check' => '<path d="M9 11.5 11.2 14 16 8.5"/><path d="M8 3h8l1 3h3v15H4V6h3l1-3Z"/>',
            'star' => '<path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2-5.6-3-5.6 3 1.1-6.2L3 9.6l6.2-.9L12 3Z"/>',
            'file' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6"/><path d="M8 13h8"/><path d="M8 17h5"/>',
            'calendar' => '<path d="M8 2v4"/><path d="M16 2v4"/><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/>',
            'bell' => '<path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/><path d="M10 21h4"/>',
            'settings' => '<path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1-1.5 1.7 1.7 0 0 0-1.9.3l-.1.1A2 2 0 1 1 4.2 17l.1-.1a1.7 1.7 0 0 0 .3-1.9 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.5-1 1.7 1.7 0 0 0-.3-1.9L4.2 7A2 2 0 1 1 7 4.2l.1.1a1.7 1.7 0 0 0 1.9.3h.1a1.7 1.7 0 0 0 .9-1.5V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.9-.3l.1-.1A2 2 0 1 1 19.8 7l-.1.1a1.7 1.7 0 0 0-.3 1.9v.1a1.7 1.7 0 0 0 1.5.9h.1a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1Z"/>',
            'user' => '<path d="M20 21a8 8 0 0 0-16 0"/><circle cx="12" cy="7" r="4"/>',
            'arrow-up' => '<path d="M12 19V5"/><path d="m5 12 7-7 7 7"/><path d="M5 21h14"/>',
            'x' => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
        ];

        return '<svg class="'.$class.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.($paths[$name] ?? $paths['home']).'</svg>';
    };

    $adminMenus = [
        ['label' => 'Dashboard Admin', 'icon' => 'home', 'href' => route('dashboard'), 'active' => request()->routeIs('dashboard')],
        ['label' => 'Data Siswa', 'icon' => 'grid', 'href' => route('masters.index', 'siswa'), 'active' => request()->routeIs('masters.*') && request()->route('type') !== 'jadwal'],
        ['label' => 'Laporan', 'icon' => 'file', 'href' => route('rekap.absensi'), 'active' => request()->routeIs('rekap.*')],
        ['label' => 'Jadwal', 'icon' => 'calendar', 'href' => route('masters.index', 'jadwal'), 'active' => request()->routeIs('masters.*') && request()->route('type') === 'jadwal'],
        ['label' => 'Kenaikan Kelas', 'icon' => 'arrow-up', 'href' => route('kenaikan-kelas.index'), 'active' => request()->routeIs('kenaikan-kelas.*')],
        ['label' => 'Data Master', 'icon' => 'settings', 'href' => route('users.index'), 'active' => request()->routeIs('users.*')],
    ];
@endphp
<body class="min-h-screen bg-[#071426] font-sans text-slate-100 antialiased">
    <div class="fixed inset-0 -z-10 bg-[radial-gradient(circle_at_20%_0%,rgba(99,102,241,0.22),transparent_30%),radial-gradient(circle_at_85%_15%,rgba(14,165,233,0.14),transparent_28%),linear-gradient(135deg,#071426_0%,#0a1730_44%,#08111f_100%)]"></div>
    <div class="fixed inset-0 -z-10 bg-[linear-gradient(rgba(255,255,255,0.025)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.025)_1px,transparent_1px)] bg-[size:42px_42px] opacity-30"></div>

    <div id="admin-sidebar-overlay" class="fixed inset-0 z-30 hidden bg-slate-950/70 backdrop-blur-sm lg:hidden"></div>

    <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-40 flex w-[280px] -translate-x-full flex-col border-r border-white/10 bg-[#08172c]/90 shadow-2xl shadow-black/40 backdrop-blur-2xl transition-transform duration-300 lg:translate-x-0">
        <div class="absolute inset-0 -z-10 bg-gradient-to-b from-indigo-500/10 via-sky-500/5 to-transparent"></div>
        <div class="flex items-center justify-between px-8 py-8">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-4">
                <span class="grid h-12 w-12 place-items-center rounded-2xl border border-white/10 bg-white/5 text-slate-200 shadow-lg shadow-indigo-950/30">
                    {!! $adminIcon('school', 'h-8 w-8') !!}
                </span>
                <span>
                    <span class="block text-lg font-bold leading-tight text-white">Sistem Sekolah</span>
                    <span class="mt-1 block text-base font-semibold text-slate-200">SMTK</span>
                </span>
            </a>
            <button id="admin-sidebar-close" type="button" class="grid h-10 w-10 place-items-center rounded-xl border border-white/10 bg-white/5 text-slate-300 lg:hidden">
                {!! $adminIcon('x', 'h-5 w-5') !!}
            </button>
        </div>

        <nav class="flex-1 space-y-2 overflow-y-auto px-5 py-4">
            @foreach ($adminMenus as $item)
                <a href="{{ $item['href'] }}" class="group flex items-center gap-4 rounded-xl border px-4 py-4 text-[15px] font-semibold transition duration-200 {{ $item['active'] ? 'border-indigo-300/20 bg-indigo-500/20 text-white shadow-lg shadow-indigo-500/10' : 'border-transparent text-slate-300 hover:border-white/10 hover:bg-white/[0.07] hover:text-white' }}">
                    <span class="{{ $item['active'] ? 'text-indigo-200' : 'text-slate-400 group-hover:text-sky-200' }}">
                        {!! $adminIcon($item['icon']) !!}
                    </span>
                    <span class="flex-1">{{ $item['label'] }}</span>
                    @if (in_array($item['label'], ['Data Siswa', 'Laporan'], true))
                        <span class="text-slate-400 transition group-hover:translate-x-0.5 group-hover:text-white">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                        </span>
                    @endif
                </a>
            @endforeach
        </nav>

        <div class="p-5">
            <div class="flex items-center gap-3 rounded-xl border border-white/10 bg-white/5 p-4 shadow-xl shadow-black/20">
                <span class="grid h-12 w-12 shrink-0 place-items-center rounded-full bg-slate-700/70 text-slate-200 ring-1 ring-white/10">
                    {!! $adminIcon('user', 'h-6 w-6') !!}
                </span>
                <span class="min-w-0 flex-1">
                    <span class="block truncate font-bold text-white">Admin</span>
                    <span class="mt-1 block truncate text-sm text-slate-400">admin@mail.com</span>
                </span>
                <svg class="h-4 w-4 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
            </div>
        </div>
    </aside>

    <div class="min-h-screen lg:pl-[280px]">
        <header class="sticky top-0 z-20 border-b border-white/10 bg-[#071426]/75 backdrop-blur-2xl">
            <div class="mx-auto flex max-w-[1540px] items-center justify-between gap-4 px-4 py-5 sm:px-6 lg:px-8">
                <div class="flex items-center gap-4">
                    <button id="admin-sidebar-open" type="button" class="grid h-11 w-11 place-items-center rounded-xl border border-white/10 bg-white/5 text-slate-200 shadow-lg shadow-black/20 lg:hidden">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16"/><path d="M4 12h16"/><path d="M4 17h16"/></svg>
                    </button>
                    <h1 class="text-2xl font-bold text-white sm:text-3xl">@yield('admin_title', 'Dashboard Admin')</h1>
                </div>

                <div class="flex items-center gap-3 sm:gap-5">
                    <div class="hidden h-12 items-center gap-3 rounded-full sm:flex">
                        <span class="grid h-12 w-12 place-items-center rounded-full bg-slate-700/70 text-slate-200 ring-1 ring-white/10">
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21a8 8 0 0 0-16 0"/><circle cx="12" cy="7" r="4"/></svg>
                        </span>
                        <span class="font-bold text-white">Admin</span>
                        <svg class="h-4 w-4 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex h-11 items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 text-sm font-bold text-slate-100 shadow-lg shadow-black/10 transition hover:-translate-y-0.5 hover:border-rose-300/30 hover:bg-rose-500/10 hover:text-white">
                            <svg class="h-4 w-4 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/></svg>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
            <div class="mx-auto max-w-[1540px]">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        (() => {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('admin-sidebar-overlay');
            const openButton = document.getElementById('admin-sidebar-open');
            const closeButton = document.getElementById('admin-sidebar-close');

            const openSidebar = () => {
                sidebar?.classList.remove('-translate-x-full');
                overlay?.classList.remove('hidden');
            };

            const closeSidebar = () => {
                sidebar?.classList.add('-translate-x-full');
                overlay?.classList.add('hidden');
            };

            openButton?.addEventListener('click', openSidebar);
            closeButton?.addEventListener('click', closeSidebar);
            overlay?.addEventListener('click', closeSidebar);
        })();

        (() => {
            const normalize = (value) => value.trim().toLowerCase();

            document.querySelectorAll('[data-table-filter]').forEach((section) => {
                if (section.dataset.filterBound === '1') {
                    return;
                }

                section.dataset.filterBound = '1';

                const input = section.querySelector('[data-table-filter-input]');
                const rows = Array.from(section.querySelectorAll('[data-table-filter-row]'));
                const emptyState = section.querySelector('[data-table-filter-empty]');

                if (!input || rows.length === 0) {
                    return;
                }

                const applyFilter = () => {
                    const query = normalize(input.value);
                    let visibleCount = 0;

                    rows.forEach((row) => {
                        const match = query === '' || normalize(row.textContent || '').includes(query);
                        row.hidden = !match;
                        if (match) {
                            visibleCount += 1;
                        }
                    });

                    if (emptyState) {
                        emptyState.hidden = visibleCount > 0;
                    }
                };

                input.addEventListener('input', applyFilter);
                applyFilter();
            });
        })();
    </script>
</body>
</html>
