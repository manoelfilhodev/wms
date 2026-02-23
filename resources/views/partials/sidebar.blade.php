<div class="leftside-menu">
    <!-- LOGO -->
    <a href="{{ route('dashboard') }}" class="logo text-center logo-light wms-sidebar-brand">
        <span class="logo-lg">
            <img src="{{ asset('images/logo-sem-nome.png') }}" alt="" height="80">
        </span>
        <span class="logo-sm">
            <img src="{{ asset('images/logo-sem-nome.png') }}" alt="" height="50">
        </span>
    </a>

    <div class="h-100" data-simplebar>
        <div class="leftside-menu-container">
            <ul class="side-nav">
                <li class="side-nav-title">Navegacao</li>

                <li class="side-nav-item">
                    <a href="{{ route('dashboard') }}" class="side-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="uil-home-alt"></i>
                        <span> Dashboard </span>
                    </a>
                </li>

                @php
                $nivel = strtolower(session('tipo', ''));
                @endphp

                @if($nivel === 'admin' || $nivel === 'gestor')

                {{-- Operacoes --}}
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#operacoes" aria-expanded="false" class="side-nav-link">
                        <i class="mdi mdi-playlist-check"></i>
                        <span> Operacoes </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="operacoes">
                        <ul class="side-nav-second-level">
                            <li><a href="{{ route('kit.index') }}">Producao</a></li>
                            <li><a href="{{ route('setores.recebimento.painel') }}">Recebimento</a></li>
                            
                            {{-- <li class="nav-item">
    <a class="nav-link" href="{{ route('transferencia.index') }}">
        <i class="bi bi-box-seam"></i> Transferencias
    </a>
</li> --}}
                            
                            <li><a href="{{ route('armazenagem.index') }}">Armazenagem</a></li>
                            <li><a href="{{ route('separacao.index') }}">Separacao</a></li>
                            {{-- <li><a href="#">Expedicao</a></li> --}}
                            {{-- <li><a href="{{ route('etiquetas.html') }}">Etiquetas de Expedicao</a></li> --}}
                            <li><a href="{{ route('demandas.index') }}">Expedicao</a></li>
                        </ul>
                    </div>
                </li>

                {{-- Estoque --}}
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sidebarEstoque" aria-expanded="false" class="side-nav-link">
                        <i class="uil-archive"></i>
                        <span> Gestao Estoque </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarEstoque">
                        <ul class="side-nav-second-level">
                            <!-- Saldo de SKUs -->
                            <li>
                                <a href="{{ route('inventario.saldos') }}">
                                    <i class="bi bi-box-seam"></i>
                                    <span> Saldo de SKUs </span>
                                </a>
                            </li>

                            {{-- Importar lista de SKUs --}}
                            <li>
                                <a href="{{ route('inventario.importar') }}">Importar SKUs</a>
                            </li>

                            {{-- Requisicoes de Inventario --}}
                            <li>
                                <a href="{{ route('inventario.requisicoes') }}">Inv. Movimentacoes</a>
                            </li>

                            {{-- Validacao (visivel apenas para admin/analista) --}}
                            @php
                            $tipo = strtolower(session('tipo', ''));
                            @endphp
                            @if($tipo === 'admin' || $tipo === 'analista')
                            <li>
                                <a href="{{ route('inventario.validacao', ['id_inventario' => 1]) }}">Validar Inventario</a>
                            </li>
                            @endif

                            <li><a href="{{ route('contagem.itens.index') }}">Contagem Insumos</a></li>
                            {{-- <li><a href="{{ route('mb52.upload') }}">Importar MB52</a></li> --}}

                            <li>
                                <a href="{{ route('contagem.livre.form') }}">
                                    <i class="bi bi-box-seam"></i>
                                    <span>Contagem Livre</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('contagem.livre.lista') }}">
                                    <i class="bi bi-list-check"></i>
                                    <span>Listar Contagens</span>
                                </a>
                            </li>

                            {{-- Fichas de Contagem --}}
                            {{-- <li>
                                <a href="{{ route('inventario.fichas.form') }}">
                                    <i class="bi bi-printer"></i>
                                    <span> Fichas de Contagem </span>
                                </a>
                            </li> --}}
                        </ul>
                    </div>
                </li>

                {{-- Cadastros --}}
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#cadastros" aria-expanded="false" class="side-nav-link">
                        <i class="mdi mdi-database-plus-outline"></i>
                        <span> Cadastros </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="cadastros">
                        <ul class="side-nav-second-level">
                            <li><a href="{{ route('multipack.create') }}">Cadastro Multipack</a></li>
                            <li><a href="{{ route('equipamentos.index') }}">Equipamentos</a></li>
                            <li><a href="{{ route('inventario.posicoes') }}">Posicoes</a></li>
                            <li>
                                <a href="{{ route('produtos.index') }}">
                                    <div data-i18n="Produtos">Produtos</div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                {{-- Gestao --}}
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#gestao" aria-expanded="false" class="side-nav-link">
                        <i class="mdi mdi-account-cog-outline"></i>
                        <span> Gestao </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="gestao">
                        <ul class="side-nav-second-level">
                            <li><a href="{{ route('usuarios.index') }}">Usuarios</a></li>
                            <li><a href="{{ route('logs.index') }}">Logs de Usuario</a></li>
                            <li><a href="{{ route('relatorios.index') }}">Relatorios</a></li>
                            <li><a href="{{ route('sugestoes.index') }}">Atualizacoes</a></li>
                        </ul>
                    </div>
                </li>

                <li class="side-nav-item">
                    <a href="{{ route('painel.tv') }}" class="side-nav-link">
                        <i class="mdi mdi-monitor-dashboard"></i>
                        <span> Painel de Controle TV </span>
                    </a>
                </li>

                {{-- Configuracoes --}}
                {{-- <li class="side-nav-item">
                    <a href="#" class="side-nav-link">
                        <i class="mdi mdi-cog-outline"></i>
                        <span> Configuracoes </span>
                    </a>
                </li> --}}

                @endif

                @if($nivel === 'operador')
                <li class="side-nav-title">Operador</li>

                <li class="side-nav-item">
                    <a href="{{ route('armazenagem.index') }}" class="side-nav-link">
                        <i class="mdi mdi-warehouse"></i>
                        <span> Armazenagem </span>
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="{{ route('separacao.index') }}" class="side-nav-link">
                        <i class="mdi mdi-format-list-bulleted-square"></i>
                        <span> Separacao </span>
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="{{ route('contagem.paletes.index') }}" class="side-nav-link">
                        <i class="uil uil-box"></i>
                        <span> Contagem de Paletes </span>
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>
