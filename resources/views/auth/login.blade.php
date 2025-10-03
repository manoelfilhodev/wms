@extends('layouts.auth')

@section('content')

<div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xxl-4 col-lg-5">
                <div class="card">

                    <!-- Logo -->
                    <div class="card-header pt-1 pb-1 text-center bg-light">
                        <a href="{{ url('/') }}">
                            <span><img src="{{ asset('images/logo-sem-nome.png') }}" alt="" height="180"></span>
                        </a>
                    </div>

                    <div class="card-body p-4">
                        <div class="text-center w-75 m-auto">
                            <h4 class="text-dark-50 text-center mt-0 fw-bold">Acesso ao Sistema</h4>
                            <small class="text-muted mb-4">Informe suas credenciais para acessar o painel.</small><br>
                        </div>

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3 mt-2">
                                <label for="email" class="form-label">Usuário</label>
                                <input class="form-control" type="text" id="email" name="email" value="{{ old('email') }}" required placeholder="Digite seu usuário">
                            </div>

                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Digite sua senha" required>
                                    <div class="input-group-text" data-password="false">
                                        <span class="password-eye"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 text-center">
                                <button class="btn btn-outline-primary w-100" type="submit">Login</button>
                            </div>
                            <a href="{{ route('login.microsoft') }}" class="btn btn-light border d-flex align-items-center justify-content-center gap-2 w-100" style="padding: 6px;">
    <img src="https://upload.wikimedia.org/wikipedia/commons/4/44/Microsoft_logo.svg" alt="Microsoft" width="20" height="20">
    <span>Entrar com Microsoft</span>
</a>

<div class="text-center">
  <i class="mdi mdi-shield-check-outline text-success fs-2"></i>
  <p class="text-muted small">Site protegido com certificado SSL e infraestrutura Azure</p>
</div>

                        </form>
                        
                        <!-- Botão para Download do APK -->
<a href="https://systex.com.br/wms/public/app-download/app.apk" 
   class="btn btn-success d-flex align-items-center justify-content-center gap-2 w-100 mt-2" 
   download>
    <i class="mdi mdi-android"></i>
    <span>Baixar App Android (.APK)</span>
</a>
                    </div>
                    <div class="text-center mt-1">
  <img src="https://upload.wikimedia.org/wikipedia/commons/5/59/SAP_2011_logo.svg" alt="SAP S/4HANA Integration" style="max-width:50px;">
  <p class="text-muted small mt-1">Compatível com integração via API SAP S/4HANA</p>
</div><!-- end card-body -->
                </div>
                <button id="btn-install" style="display: none;" class="btn btn-outline-light mt-3">
    <i class="mdi mdi-download"></i> Instalar Aplicativo
</button>

                <!-- end card -->

                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <p class="text-muted">© {{ date('Y') }} SYSTEX Sistemas Inteligentes</p>
                    </div>
                </div>

            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    
    <!-- end container -->
</div>

<button id="btn-install" style="display: none;">Instalar App</button>

<script>
    let deferredPrompt;

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;

        const installBtn = document.getElementById('btn-install');
        installBtn.style.display = 'inline-block';

        installBtn.addEventListener('click', () => {
            installBtn.style.display = 'none';
            deferredPrompt.prompt();
        });
    });
</script>


<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('{{ asset('sw.js') }}').then(() => {
            console.log("Service Worker registrado na tela de login");
        });
    }
</script>


<!-- end page -->
@endsection
