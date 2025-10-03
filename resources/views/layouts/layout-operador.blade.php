<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Painel do Operador') | WMS 4.0</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Sistema WMS" name="description" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app-creative.min.css') }}" rel="stylesheet" type="text/css" id="light-style" />
    <link href="{{ asset('assets/css/app-creative-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style" />
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <meta name="theme-color" content="#111827">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">


    <style>
        body {
            background-color: #1e1e2f;
            color: #fff;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .btn-voltar {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1000;
        }

        footer {
            background-color: #111827;
            padding: 0.75rem 1rem;
            text-align: center;
            color: #ccc;
            font-size: 0.9rem;
            margin-top: auto;
        }

        .content-wrapper {
            padding: 80px 20px 40px;
        }
    </style>

    @yield('head')
</head>
<body data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false,"leftSidebarCondensed":false,"darkMode":true}'>


    {{-- Botão Voltar --}}
   <div class="top-bar-operador d-flex justify-content-between align-items-center px-3 py-2 shadow-sm bg-dark">
       <div>
       <button onclick="history.back()" class="btn btn-outline-light btn-sm d-flex align-items-center" title="Voltar para a página anterior">
            <i class="mdi mdi-arrow-left me-1 fs-5"></i> Voltar
        </button>
    </div>
    <div>
        <a href="{{ route('painel.operador') }}" class="btn btn-outline-light btn-sm d-flex align-items-center" title="Voltar ao Menu">
            <i class="mdi mdi-home-outline me-1 fs-5"></i> Menu
        </a>
    </div>

    <div>
       
            @csrf
            <a href="{{ route('logout') }}" class="btn btn-outline-light btn-sm d-flex align-items-center" title="Sair do sistema">
                <i class="mdi mdi-logout me-1 fs-5"></i> Sair
            </a>
      
    </div>
</div>



    <div class="content-wrapper container-fluid">
        @yield('content')
    </div>

    <footer>
        @auth
            <div><b>Usuário Logado:</b> {{ Auth::user()->nome }} | {{ ucfirst(Auth::user()->tipo) }}</div>
            <div class="text-muted mt-2 small">
                Versão App: {{ config('app.app_version') }}
            </div>
            
        @endauth
        <div class="mt-2" style="font-size: 0.8rem; color: #888;">
            Desenvolvido por <strong>Systex</strong>
        </div>
    </footer>

    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    <script>
        // Força o modo escuro mesmo que o usuário tenha preferência salva
        localStorage.setItem('data-layout-config', JSON.stringify({
            leftSideBarTheme: 'dark',
            layoutBoxed: false,
            leftSidebarCondensed: false,
            darkMode: true
        }));
    </script>
    
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('{{ asset('sw.js') }}').then(() => {
                console.log("Service Worker registrado com sucesso");
            }).catch((err) => {
                console.warn("Erro ao registrar Service Worker", err);
            });
        });
    }
</script>



    @yield('scripts')
    
</body>
</html>
