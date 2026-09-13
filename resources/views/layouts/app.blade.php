<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Sistem Sekolah') }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            :root {
                color-scheme: dark;
                --bg: #081120;
                --bg2: #0f1d36;
                --card: rgba(255, 255, 255, 0.08);
                --line: rgba(255, 255, 255, 0.12);
                --text: #e2e8f0;
                --muted: #94a3b8;
                --accent: #5eead4;
            }

            * { box-sizing: border-box; }
            body {
                margin: 0;
                font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
                color: var(--text);
                background:
                    radial-gradient(circle at top left, rgba(94,234,212,.16), transparent 30%),
                    radial-gradient(circle at top right, rgba(251,191,36,.14), transparent 28%),
                    linear-gradient(180deg, var(--bg), var(--bg2));
                min-height: 100vh;
            }

            a { color: inherit; }
            input, button { font: inherit; }
            button { border: 0; cursor: pointer; }
            main { padding-top: 2rem; padding-bottom: 2rem; }
        </style>
    @endif
</head>
@php
    $user = auth()->user();
    $role = $user?->role;

    $appIcon = function (string $name, string $class = 'h-6 w-6') {
        $paths = [
            'home' => '<path d="m3 10.5 9-7 9 7"/><path d="M5 10v10h14V10"/><path d="M9 20v-6h6v6"/>',
            'portal' => '<path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v15H7a3 3 0 0 0-3 3V5.5Z"/><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>',
            'check' => '<path d="M20 6 9 17l-5-5"/>',
            'star' => '<path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2-5.6-3-5.6 3 1.1-6.2L3 9.6l6.2-.9L12 3Z"/>',
            'chart' => '<path d="M3 3v18h18"/><path d="M7 16V9"/><path d="M12 16V5"/><path d="M17 16v-3"/>',
            'calendar' => '<path d="M8 2v4"/><path d="M16 2v4"/><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/>',
            'file' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6"/><path d="M8 13h8"/><path d="M8 17h5"/>',
            'book' => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M4 4.5A2.5 2.5 0 0 1 6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5z"/>',
            'users' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.9"/><path d="M16 3.1a4 4 0 0 1 0 7.8"/>',
            'settings' => '<path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1-1.5 1.7 1.7 0 0 0-1.9.3l-.1.1A2 2 0 1 1 4.2 17l.1-.1a1.7 1.7 0 0 0 .3-1.9 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.5-1 1.7 1.7 0 0 0-.3-1.9L4.2 7A2 2 0 1 1 7 4.2l.1.1a1.7 1.7 0 0 0 1.9.3h.1a1.7 1.7 0 0 0 .9-1.5V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.9-.3l.1-.1A2 2 0 1 1 19.8 7l-.1.1a1.7 1.7 0 0 0-.3 1.9v.1a1.7 1.7 0 0 0 1.5.9h.1a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1Z"/>',
            'logout' => '<path d="M10 17l5-5-5-5"/><path d="M15 12H3"/><path d="M21 3v18"/>',
            'user' => '<path d="M20 21a8 8 0 0 0-16 0"/><circle cx="12" cy="7" r="4"/>',
        ];

        return '<svg class="'.$class.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.($paths[$name] ?? $paths['home']).'</svg>';
    };

    $isRoleShell = auth()->check() && in_array($role, ['guru', 'siswa'], true);

    $roleMenus = [];
    if ($role === 'guru') {
        $roleMenus = [
            ['label' => 'Dashboard', 'icon' => 'home', 'href' => route('dashboard'), 'active' => request()->routeIs('dashboard')],
            ['label' => 'Absensi', 'icon' => 'check', 'href' => route('absensi.index'), 'active' => request()->routeIs('absensi.*')],
            ['label' => 'Nilai', 'icon' => 'star', 'href' => route('nilai.index'), 'active' => request()->routeIs('nilai.*')],
            ['label' => 'Rekap Absensi', 'icon' => 'chart', 'href' => route('rekap.absensi'), 'active' => request()->routeIs('rekap.absensi')],
            ['label' => 'Rekap Nilai', 'icon' => 'file', 'href' => route('rekap.nilai'), 'active' => request()->routeIs('rekap.nilai')],
        ];
    } elseif ($role === 'siswa') {
        $roleMenus = [
            ['label' => 'Dashboard', 'icon' => 'home', 'href' => route('dashboard'), 'active' => request()->routeIs('dashboard')],
            ['label' => 'Nilai', 'icon' => 'star', 'href' => route('siswa.nilai.index'), 'active' => request()->routeIs('siswa.nilai.*')],
        ];
    }

    $activeMenu = null;
    foreach ($roleMenus as $item) {
        if ($item['active']) {
            $activeMenu = $item;
            break;
        }
    }

    $pageTitle = $activeMenu['label'] ?? ($role === 'guru' ? 'Dashboard Guru' : 'Dashboard Siswa');
@endphp
<body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
    @if ($isRoleShell)
        <div class="fixed inset-0 -z-10 bg-[radial-gradient(circle_at_20%_0%,rgba(94,234,212,.16),transparent_30%),radial-gradient(circle_at_85%_15%,rgba(99,102,241,.18),transparent_28%),linear-gradient(135deg,#071426_0%,#0a1730_44%,#08111f_100%)]"></div>
        <div class="fixed inset-0 -z-10 bg-[linear-gradient(rgba(255,255,255,0.025)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.025)_1px,transparent_1px)] bg-[size:42px_42px] opacity-30"></div>

        <div id="app-sidebar-overlay" class="fixed inset-0 z-30 hidden bg-slate-950/70 backdrop-blur-sm lg:hidden"></div>

        <aside id="app-sidebar" class="fixed inset-y-0 left-0 z-40 flex w-[280px] -translate-x-full flex-col border-r border-white/10 bg-[#08172c]/90 shadow-2xl shadow-black/40 backdrop-blur-2xl transition-transform duration-300 lg:translate-x-0">
            <div class="absolute inset-0 -z-10 bg-gradient-to-b from-indigo-500/10 via-sky-500/5 to-transparent"></div>
            <div class="flex items-center justify-between px-8 py-8">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-4">
                    <span class="grid h-12 w-12 place-items-center rounded-2xl border border-white/10 bg-white/5 text-slate-200 shadow-lg shadow-indigo-950/30">
                        {!! $appIcon('book', 'h-8 w-8') !!}
                    </span>
                    <span>
                        <span class="block text-lg font-bold leading-tight text-white">Sistem Sekolah</span>
                        <span class="mt-1 block text-base font-semibold text-slate-200">{{ $role === 'guru' ? 'Guru' : 'Siswa' }}</span>
                    </span>
                </a>
                <button id="app-sidebar-close" type="button" class="grid h-10 w-10 place-items-center rounded-xl border border-white/10 bg-white/5 text-slate-300 lg:hidden">
                    {!! $appIcon('logout', 'h-5 w-5 rotate-180') !!}
                </button>
            </div>

            <nav class="flex-1 space-y-2 overflow-y-auto px-5 py-4">
                @foreach ($roleMenus as $item)
                    <a href="{{ $item['href'] }}" class="group flex items-center gap-4 rounded-xl border px-4 py-4 text-[15px] font-semibold transition duration-200 {{ $item['active'] ? 'border-indigo-300/20 bg-indigo-500/20 text-white shadow-lg shadow-indigo-500/10' : 'border-transparent text-slate-300 hover:border-white/10 hover:bg-white/[0.07] hover:text-white' }}">
                        <span class="{{ $item['active'] ? 'text-indigo-200' : 'text-slate-400 group-hover:text-sky-200' }}">
                            {!! $appIcon($item['icon']) !!}
                        </span>
                        <span class="flex-1">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>

            <div class="p-5">
                <div class="flex items-center gap-3 rounded-xl border border-white/10 bg-white/5 p-4 shadow-xl shadow-black/20">
                    <span class="grid h-12 w-12 shrink-0 place-items-center rounded-full bg-slate-700/70 text-slate-200 ring-1 ring-white/10">
                        {!! $appIcon('user', 'h-6 w-6') !!}
                    </span>
                    <span class="min-w-0 flex-1">
                        <span class="block truncate font-bold text-white">{{ $user?->name ?? 'Pengguna' }}</span>
                        <span class="mt-1 block truncate text-sm text-slate-400">{{ strtoupper($role ?? 'guest') }}</span>
                    </span>
                </div>
            </div>
        </aside>

        <div class="min-h-screen lg:pl-[280px]">
            <header class="sticky top-0 z-20 border-b border-white/10 bg-[#071426]/75 backdrop-blur-2xl">
                <div class="mx-auto flex max-w-[1540px] items-center justify-between gap-4 px-4 py-5 sm:px-6 lg:px-8">
                    <div class="flex items-center gap-4">
                        <button id="app-sidebar-open" type="button" class="grid h-11 w-11 place-items-center rounded-xl border border-white/10 bg-white/5 text-slate-200 shadow-lg shadow-black/20 lg:hidden">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16"/><path d="M4 12h16"/><path d="M4 17h16"/></svg>
                        </button>
                        <h1 class="text-2xl font-bold text-white sm:text-3xl">@yield('admin_title', $pageTitle)</h1>
                    </div>

                    <div class="flex items-center gap-3 sm:gap-5">
                        <div class="hidden h-12 items-center gap-3 rounded-full sm:flex">
                            <span class="grid h-12 w-12 place-items-center rounded-full bg-slate-700/70 text-slate-200 ring-1 ring-white/10">
                                {!! $appIcon('user', 'h-7 w-7') !!}
                            </span>
                            <span class="font-bold text-white">{{ $user?->name ?? 'Pengguna' }}</span>
                            <svg class="h-4 w-4 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex h-11 items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 text-sm font-bold text-slate-100 shadow-lg shadow-black/10 transition hover:-translate-y-0.5 hover:border-rose-300/30 hover:bg-rose-500/10 hover:text-white">
                                {!! $appIcon('logout', 'h-4 w-4') !!}
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
                const sidebar = document.getElementById('app-sidebar');
                const overlay = document.getElementById('app-sidebar-overlay');
                const openButton = document.getElementById('app-sidebar-open');
                const closeButton = document.getElementById('app-sidebar-close');

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
    @else
        <div class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(94,234,212,.16),_transparent_30%),radial-gradient(circle_at_top_right,_rgba(251,191,36,.14),_transparent_28%),linear-gradient(180deg,#081120,#0f1d36)]">
            <header class="border-b border-white/10 bg-white/5 backdrop-blur-xl">
                <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-teal-200/70">{{ config('app.name', 'Sistem Sekolah') }}</p>
                    </div>
                    <div class="flex flex-wrap items-center justify-end gap-3">
                        @auth
                            <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-sm text-slate-200">
                                {{ $user?->name }} · {{ strtoupper($role === 'tu' ? 'admin' : $role) }}
                            </span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="rounded-xl border border-white/10 bg-white/10 px-4 py-2 text-sm font-medium hover:bg-white/15">
                                    Keluar
                                </button>
                            </form>
                        @endauth
                    </div>
                </div>
            </header>

            <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                @yield('content')
            </main>
        </div>
    @endif
    <!-- Logout confirmation modal -->
    <div id="logout-modal" class="fixed inset-0 z-50 hidden items-center justify-center">
        <div class="absolute inset-0 bg-black/60"></div>
        <div class="relative w-full max-w-lg rounded-2xl border border-white/10 bg-[#071426]/95 p-6 shadow-2xl">
            <h3 class="text-lg font-semibold">Konfirmasi Logout</h3>
            <p class="mt-2 text-sm text-slate-300">Apakah Anda yakin ingin keluar dari sistem?</p>
            <div class="mt-4 flex justify-end gap-3">
                <button id="logout-cancel" type="button" class="rounded-2xl border border-white/10 bg-white/5 px-4 py-2">Batal</button>
                <button id="logout-confirm" type="button" class="rounded-2xl bg-rose-500 px-4 py-2 text-white">Logout</button>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const logoutRoute = '{{ route('logout') }}';
            const modal = document.getElementById('logout-modal');
            const openModal = () => { modal.classList.remove('hidden'); modal.classList.add('flex'); };
            const closeModal = () => { modal.classList.remove('flex'); modal.classList.add('hidden'); };
            let pendingForm = null;

            document.addEventListener('submit', function (e) {
                const form = e.target;
                if (form && form.tagName === 'FORM' && form.getAttribute('action') === logoutRoute) {
                    e.preventDefault();
                    pendingForm = form;
                    openModal();
                }
            }, true);

            document.getElementById('logout-cancel')?.addEventListener('click', function () {
                pendingForm = null;
                closeModal();
            });
            document.getElementById('logout-confirm')?.addEventListener('click', function () {
                if (pendingForm) {
                    closeModal();
                    pendingForm.submit();
                }
            });
        })();
    </script>

    <!-- Delete confirmation modal -->
    <div id="confirm-delete-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center">
        <div id="confirm-delete-overlay" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div id="confirm-delete-box" class="relative z-10 w-full max-w-md transform-gpu rounded-2xl border border-white/10 bg-[#071426]/95 p-6 shadow-2xl opacity-0 scale-95 transition duration-200">
            <div class="flex flex-col items-center text-center">
                <div class="rounded-full bg-rose-500/10 p-3">
                    <svg class="h-8 w-8 text-rose-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                </div>
                <h3 class="mt-4 text-lg font-semibold">Konfirmasi Hapus</h3>
                <p id="confirm-delete-message" class="mt-2 text-sm text-slate-300">Data yang dihapus tidak dapat dikembalikan. Apakah Anda yakin ingin melanjutkan?</p>
                <div class="mt-6 flex w-full justify-end gap-3">
                    <button id="confirm-delete-cancel" type="button" class="rounded-2xl border border-white/10 bg-white/5 px-4 py-2">Batal</button>
                    <button id="confirm-delete-confirm" type="button" class="rounded-2xl bg-rose-500 px-4 py-2 text-white">Hapus Data</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const modal = document.getElementById('confirm-delete-modal');
            const overlay = document.getElementById('confirm-delete-overlay');
            const box = document.getElementById('confirm-delete-box');
            const messageEl = document.getElementById('confirm-delete-message');
            const btnCancel = document.getElementById('confirm-delete-cancel');
            const btnConfirm = document.getElementById('confirm-delete-confirm');
            let pendingForm = null;

            const open = (msg) => {
                if (msg) messageEl.textContent = msg;
                modal.classList.remove('hidden');
                // trigger animation
                requestAnimationFrame(() => {
                    box.classList.remove('opacity-0','scale-95');
                });
                box.classList.add('opacity-100','scale-100');
            };

            const close = () => {
                box.classList.add('opacity-0','scale-95');
                box.classList.remove('opacity-100','scale-100');
                setTimeout(() => modal.classList.add('hidden'), 200);
                pendingForm = null;
            };

            document.addEventListener('submit', function (e) {
                const form = e.target;
                if (form && form.tagName === 'FORM' && form.dataset.confirm) {
                    e.preventDefault();
                    pendingForm = form;
                    open(form.dataset.confirm);
                }
            }, true);

            btnCancel?.addEventListener('click', function () { close(); });
            overlay?.addEventListener('click', function () { close(); });
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });
            btnConfirm?.addEventListener('click', function () {
                if (pendingForm) {
                    close();
                    pendingForm.submit();
                }
            });
        })();
    </script>

</body>
</html>
