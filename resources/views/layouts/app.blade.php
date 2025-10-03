@php
    $segments = Request::segments();
    $title = ucfirst(end($segments));
@endphp

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', $title) | WMS 4.0</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Sistema WMS" name="description" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app-creative.min.css') }}" rel="stylesheet" type="text/css" id="light-style" />
    <link href="{{ asset('assets/css/app-creative-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


    <style>
        .content::before {
            content: none !important;
        }

        .content > svg {
            display: none !important;
        }

        .sidebar-toggle, .vertical-menu-toggle, .menu-toggle {
            display: none !important;
        }

        .top-bar-operador {
            background-color: #111827;
            padding: 0.75rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-bar-operador span {
            color: #fff;
            font-weight: bold;
        }

        .top-bar-operador .btn {
            font-size: 0.9rem;
        }
    </style>

    @yield('head')
</head>

<body class="loading" data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false,"leftSidebarCondensed":false,"darkMode":false}'>

    <div class="wrapper">

        @auth
            @if(Auth::user()->tipo !== 'operador')
                @include('partials.sidebar')
            @endif
        @endauth

        <div class="content-page">
            <div class="content">

                @auth
                    @if(Auth::user()->tipo !== 'operador')
                        @include('partials.header')
                    @else
                        {{-- Topo simples para operadores --}}
                        <div class="top-bar-operador">
                            <span>Systex WMS</span>
                            <a href="{{ route('painel.operador') }}" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-arrow-left"></i> Voltar ao Início
                            </a>
                        </div>
                    @endif
                @endauth

                <div class="container-fluid mt-3">
                    @yield('content')
                </div>
            </div>

            @include('partials.footer')
        </div>
    </div>

    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
    <script>
        // Ao carregar a página, aplica o tema salvo
        document.addEventListener("DOMContentLoaded", function() {
            const storedTheme = localStorage.getItem("darkMode") === "true";
            const html = document.documentElement;
            const lightStyle = document.getElementById('light-style');
            const darkStyle = document.getElementById('dark-style');

            const config = {
                darkMode: storedTheme
            };
            html.setAttribute('data-layout-config', JSON.stringify(config));
            lightStyle.disabled = storedTheme;
            darkStyle.disabled = !storedTheme;
        });

        // Alternar e salvar a escolha
        document.getElementById('toggle-dark-mode').addEventListener('click', function() {
            const html = document.documentElement;
            const lightStyle = document.getElementById('light-style');
            const darkStyle = document.getElementById('dark-style');

            const config = JSON.parse(html.getAttribute('data-layout-config') || '{}');
            config.darkMode = !config.darkMode;
            html.setAttribute('data-layout-config', JSON.stringify(config));
            localStorage.setItem("darkMode", config.darkMode); // salva

            lightStyle.disabled = config.darkMode;
            darkStyle.disabled = !config.darkMode;
        });
    </script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('meu-formulario');
    const msg = document.getElementById('mensagem');

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const dados = {
            produto: form.produto.value,
            quantidade: form.quantidade.value,
            criado_em: new Date().toISOString()
        };

        try {
            const res = await fetch('/formulario', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(dados)
            });

            if (!res.ok) throw new Error();

            msg.innerHTML = `<div class="alert alert-success">Enviado com sucesso!</div>`;
            form.reset();
        } catch (err) {
            const db = await openDB();
            const tx = db.transaction('formularios', 'readwrite');
            await tx.store.add(dados);
            await tx.done;

            if ('serviceWorker' in navigator && 'SyncManager' in window) {
                const reg = await navigator.serviceWorker.ready;
                await reg.sync.register('sync-formularios');
            }

            msg.innerHTML = `<div class="alert alert-warning">Você está offline. Dados salvos localmente e serão enviados depois.</div>`;
            form.reset();
        }
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/idb@7/build/umd.js"></script>
<script>
  const openDB = () => idb.openDB('systex-db', 1, {
    upgrade(db) {
      if (!db.objectStoreNames.contains('formularios')) {
        db.createObjectStore('formularios', { autoIncrement: true });
        console.log('[IndexedDB] Object store "formularios" criado.');
      }
    }
  });
</script>
<script>
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
        navigator.serviceWorker.register("/wms/public/service-worker.js").then(function (registration) {
            console.log("[SW] Registrado com sucesso:", registration.scope);
        }, function (err) {
            console.error("[SW] Falha ao registrar:", err);
        });
    });
}
</script>


<script>
    // Solicita permissão para exibir notificações, se ainda não concedida
    if ('Notification' in window && Notification.permission !== 'granted') {
        Notification.requestPermission().then(permission => {
            console.log('[APP] Permissão para notificação:', permission);
        });
    }
</script>



    @yield('scripts')
    @stack('scripts')
</body>
</html>
