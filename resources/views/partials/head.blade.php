<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="theme-color" content="#7b4397">
<title>{{ isset($titulo) && $titulo ? $titulo.' | ' : '' }}KuroCute</title>
<meta name="description" content="KuroCute, tu tiendita favorita de Kuromi: peluches, figuras, láminas y stickers. Hecho con mucho cariño en Tantoyuca.">
<link rel="icon" type="image/jpeg" href="{{ asset('images/ima6.jpg') }}">
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
