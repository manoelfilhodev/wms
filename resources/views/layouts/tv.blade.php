<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel TV</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Chart.js + Datalabels -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <!-- Estilo básico para fullscreen -->
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f4f6f8;
            font-family: sans-serif;
        }
    </style>
</head>
<body>
    @yield('content')

    <!-- Script de fullscreen -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const elem = document.documentElement;
            if (elem.requestFullscreen) elem.requestFullscreen();
        });
    </script>

    <!-- 🔥 Isso aqui estava faltando -->
    @yield('scripts')
</body>
</html>
