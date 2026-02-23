@extends('layouts.auth')

@section('content')
<div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xxl-4 col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-header pt-2 pb-2 text-center bg-light border-0">
                        <a href="{{ url('/') }}" aria-label="Pagina inicial">
                            <span><img src="{{ asset('images/logo-sem-nome.png') }}" alt="Logo Systex" height="160"></span>
                        </a>
                    </div>

                    <div class="card-body p-4">
                        <div class="text-center w-75 m-auto mb-3">
                            <h4 class="text-dark text-center mt-0 fw-bold">Acesso ao sistema</h4>
                            <small class="text-muted">Informe suas credenciais para acessar o painel.</small>
                        </div>

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" novalidate>
                            @csrf

                            <div class="mb-3 mt-2">
                                <label for="email" class="form-label">Usuario</label>
                                <input class="form-control" type="text" id="email" name="email" value="{{ old('email') }}" required placeholder="Digite seu usuario">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Senha</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Digite sua senha" required>
                                    <div class="input-group-text" data-password="false">
                                        <span class="password-eye"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 text-center">
                                <button class="btn btn-primary w-100" type="submit">Entrar</button>
                            </div>

                            <a href="{{ route('login.microsoft') }}" class="btn btn-light border d-flex align-items-center justify-content-center gap-2 w-100" style="padding: 8px;">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/4/44/Microsoft_logo.svg" alt="Microsoft" width="20" height="20">
                                <span>Entrar com Microsoft</span>
                            </a>
                        </form>

                        <a href="https://systex.com.br/wms/public/app-download/app.apk" class="btn btn-success d-flex align-items-center justify-content-center gap-2 w-100 mt-2" download>
                            <i class="mdi mdi-android"></i>
                            <span>Baixar app Android (.APK)</span>
                        </a>

                        <div class="text-center mt-3">
                            <i class="mdi mdi-shield-check-outline text-success fs-2"></i>
                            <p class="text-muted small mb-1">Site protegido com SSL e infraestrutura Azure</p>
                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/59/SAP_2011_logo.svg" alt="Integracao SAP" style="max-width: 52px;">
                            <p class="text-muted small mt-1 mb-0">Compativel com integracao via API SAP S/4HANA</p>
                        </div>
                    </div>
                </div>

                <button id="btn-install" style="display: none;" class="btn btn-outline-secondary mt-3 w-100">
                    <i class="mdi mdi-download"></i> Instalar aplicativo
                </button>

                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <p class="text-muted">&copy; {{ date('Y') }} SYSTEX Sistemas Inteligentes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let deferredPrompt;

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;

        const installBtn = document.getElementById('btn-install');
        installBtn.style.display = 'inline-block';

        installBtn.addEventListener('click', () => {
            installBtn.style.display = 'none';
            if (deferredPrompt) {
                deferredPrompt.prompt();
            }
        }, { once: true });
    });
</script>

<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register("{{ asset('sw.js') }}").catch(() => {});
    }
</script>
@endsection