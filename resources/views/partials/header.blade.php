<div class="navbar-custom">
    <button class="button-menu-mobile open-left">
        <i class="mdi mdi-menu"></i>
    </button>
    <ul class="list-unstyled topbar-menu float-end mb-0">
        <li class="dropdown notification-list">
            <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <i class="dripicons-bell noti-icon"></i>
                <span class="noti-icon-badge"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated dropdown-lg">

                <!-- item-->
                <div class="dropdown-item noti-title">
                    <h5 class="m-0">
                        <span class="float-end">
                            <a href="javascript: void(0);" class="text-dark">
                                <small>Clear All</small>
                            </a>
                        </span>Notificação
                    </h5>
                </div>
                <!-- 
                <div style="max-height: 230px;" data-simplebar>
                    
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <div class="notify-icon bg-primary">
                            <i class="mdi mdi-comment-account-outline"></i>
                        </div>
                        <p class="notify-details">Caleb Flakelar commented on Admin
                            <small class="text-muted">1 min ago</small>
                        </p>
                    </a>
                </div>
                item-->

                <!-- All-->
                <a href="javascript:void(0);" class="dropdown-item text-center text-primary notify-item notify-all">
                    Ver todas notificações
                </a>

            </div>
        </li>

        <li class="notification-list">
            <a class="nav-link end-bar-toggle" href="javascript: void(0);">
                <i class="dripicons-gear noti-icon"></i>
            </a>
        </li>
        <li class="notification-list" id="tooltip-container">
            <a class="nav-link" href="{{ route('convites.index') }}">
                <i class="dripicons-user-group noti-icon" data-bs-container="#tooltip-container" data-bs-placement="bottom" data-bs-toggle="tooltip" title="Gerar Link de Cadastro"></i>
            </a>
        </li>
        <li class="notification-list" id="tooltip-container">
            <a class="nav-link" href="javascript:void(0);" id="toggle-dark-mode">
                <i class="dripicons-brightness-max noti-icon" data-bs-container="#tooltip-container" data-bs-placement="bottom" data-bs-toggle="tooltip" title="Alterar Tema"></i>
            </a>
        </li>
        <!-- <li class="notification-list">
            <a class="nav-link" href="javascript:void(0);" id="toggle-fullscreen" data-bs-toggle="tooltip" title="Tela cheia">
                <i class="dripicons-monitor noti-icon"></i>
            </a>
        </li> -->
        <li class="notification-list" id="tooltip-container">
            <a class="nav-link" href="{{ route('logout') }}">
                <i class="dripicons-power noti-icon text-danger" data-bs-container="#tooltip-container" data-bs-placement="bottom" data-bs-toggle="tooltip" title="Sair do Sistema"></i>
            </a>
        </li>

        <li class="dropdown notification-list">
            <a class="nav-link dropdown-toggle nav-user arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                aria-expanded="false">
                <span class="account-user-avatar">
                    <img src="https://vmxi.com.br/img/login.png" alt="user-image" class="rounded-circle">
                </span>
                <span>
                    <span class="account-user-name">{{ session('nome') }}</span>
                    <span class="account-position">{{ session('tipo') }}</span>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu profile-dropdown">
                <!-- item-->
                <div class=" dropdown-header noti-title">
                    <h6 class="text-overflow m-0">Bem vindo !</h6>
                </div>

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <i class="mdi mdi-account-circle me-1"></i>
                    <span>Meus Dados</span>
                </a>

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <i class="mdi mdi-account-edit me-1"></i>
                    <span>Configurações</span>
                </a>

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <i class="mdi mdi-lifebuoy me-1"></i>
                    <span>Suporte</span>
                </a>

                <!-- item-->
                <a href="{{ route('logout') }}" class="dropdown-item notify-item">
                    <i class="mdi mdi-logout me-1"></i>
                    <span>Sair</span>
                </a>

            </div>
        </li>

    </ul>
</div>