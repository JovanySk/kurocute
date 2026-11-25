<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="theme-color" content="#140d1c">
<title>{{ isset($titulo) && $titulo ? $titulo.' | ' : '' }}KuroCute</title>
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 40 40'%3E%3Crect width='40' height='40' rx='12' fill='%232a1740'/%3E%3Cpath d='M24.6 10.2a10 10 0 1 0 6.1 15.6A8.2 8.2 0 0 1 24.6 10.2z' fill='%23c9b2f5'/%3E%3Cpath d='M27.5 9.5c.4 2.6 1.4 3.6 4 4-2.6.4-3.6 1.4-4 4-.4-2.6-1.4-3.6-4-4 2.6-.4 3.6-1.4 4-4z' fill='%23f06aa8'/%3E%3C/svg%3E">
<script>
    (function () {
        var tema = null;
        try { tema = localStorage.getItem('kc-tema'); } catch (e) {}
        if (!tema) { tema = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'; }
        document.documentElement.dataset.theme = tema;
    })();
</script>
@fonts
@vite(['resources/css/app.css', 'resources/js/app.js'])
