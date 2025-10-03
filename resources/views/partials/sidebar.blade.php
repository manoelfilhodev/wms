<div class="leftside-menu">
    <!-- LOGO -->
    <a href="index.html" class="logo text-center logo-light">
        <span class="logo-lg">
            <img src="images/logo-sem-nome.png" alt="" height="80">
        </span>
        <span class="logo-sm">
            <img src="images/logo-sem-nome.png" alt="" height="50">
        </span>
    </a>

    <div class="h-100" data-simplebar>
        <div class="leftside-menu-container">
            <ul class="side-nav">
                <li class="side-nav-title">Navegação</li>

                <li class="side-nav-item">
                    <a href="{{ route('dashboard') }}" class="side-nav-link">
                        <i class="uil-home-alt"></i>
                        <span> Dashboard </span>
                    </a>
                </li>

                @php
                $nivel = strtolower(session('tipo', ''));
                @endphp

                @if($nivel === 'admin' || $nivel === 'gestor')

                {{-- Operações --}}
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#operacoes" aria-expanded="false" class="side-nav-link">
                        <i class="mdi mdi-playlist-check"></i>
                        <span> Operações </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="operacoes">
                        <ul class="side-nav-second-level">
                            <li><a href="{{ route('setores.recebimento.painel') }}">Recebimento</a></li>
                            <li><a href="{{ route('kit.index') }}">Montagem de Kits</a></li>
                            <li class="nav-item">
    <a class="nav-link" href="{{ route('transferencia.index') }}">
        <i class="bi bi-box-seam"></i> Transferências
    </a>
</li>
                            
                            <li><a href="{{ route('armazenagem.index') }}">Armazenagem</a></li>
                            <li><a href="{{ route('separacao.index') }}">Separação</a></li>
                            <li><a href="#">Expedição</a></li>
                            <li><a href="{{ route('etiquetas.html') }}">Etiquetas de Expedição</a></li>
                             <li><a href="{{ route('demandas.index') }}">Demandas</a></li>

                            <li class="side-nav-item">
                                <a data-bs-toggle="collapse" href="#sidebarEstoque" aria-expanded="false" aria-controls="sidebarEstoque" class="side-nav-link">
                                    <i class="uil-archive"></i>
                                    <span> Estoque </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="sidebarEstoque">
                                    <ul class="side-nav-second-level">

                                        <!-- Saldo de SKUs -->
                                        <li class="side-nav-item">
                                            <a href="{{ route('inventario.saldos') }}" class="side-nav-link">
                                                <i class="bi bi-box-seam"></i>
                                                <span> Saldo de SKUs </span>
                                            </a>
                                        </li>

                                        {{-- Importar lista de SKUs --}}
                                        <li>
                                            <a href="{{ route('inventario.importar') }}">Importar SKUs</a>
                                        </li>

                                        {{-- Requisições de Inventário --}}
                                        <li>
                                            <a href="{{ route('inventario.requisicoes') }}">Inv. Movimentações</a>
                                        </li>

                                        {{-- Validação (visível apenas para admin/analista) --}}
                                        @php
                                        $tipo = strtolower(session('tipo', ''));
                                        @endphp
                                        @if($tipo === 'admin' || $tipo === 'analista')
                                        <li>
                                            <a href="{{ route('inventario.validacao', ['id_inventario' => 1]) }}">Validar Inventário</a>
                                        </li>
                                        @endif
                                        <li><a href="{{ route('contagem.itens.index') }}">Contagem de Itens / Paletes</a></li>


                                        <li><a href="{{ route('mb52.upload') }}">Importar MB52</a></li>

                                        <li class="sidebar-item">
                                            <a href="{{ route('contagem.livre.form') }}" class="sidebar-link">
                                                <i class="bi bi-box-seam"></i> {{-- Ícone do Bootstrap Icons --}}
                                                <span>Contagem Livre</span>
                                            </a>
                                        </li>

                                        <li class="sidebar-item">
                                            <a href="{{ route('contagem.livre.lista') }}" class="sidebar-link">
                                                <i class="bi bi-list-check"></i>
                                                <span>Listar Contagens</span>
                                            </a>
                                        </li>

                                        {{-- Futuro: relatórios, ajustes, históricos --}}
                                        {{-- <li><a href="#">Relatórios</a></li> --}}

                                        {{-- Fichas de Contagem --}}
                                        <li>
                                            <a href="{{ route('inventario.fichas.form') }}" class="side-nav-link">
                                                <i class="bi bi-printer"></i>
                                                <span> Fichas de Contagem </span>
                                            </a>
                                        </li>

                                    </ul>
                                </div>
                            </li>




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
                            <li><a href="{{ route('inventario.posicoes') }}">Posições</a></li>
                            <li class="menu-item">
                                <a href="{{ route('produtos.index') }}" class="menu-link">
                                    <div data-i18n="Produtos">Produtos</div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                {{-- Gestão --}}
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#gestao" aria-expanded="false" class="side-nav-link">
                        <i class="mdi mdi-account-cog-outline"></i>
                        <span> Gestão </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="gestao">
                        <ul class="side-nav-second-level">
                            <li><a href="{{ route('usuarios.index') }}">Usuários</a></li>
                            <li><a href="{{ route('logs.index') }}">Logs de Usuário</a></li>
                            <li><a href="{{ route('relatorios.index') }}">Relatórios</a></li>
                            <li><a href="{{ route('sugestoes.index') }}">Atualizações</a></li>
                        </ul>
                    </div>
                </li>
                <li class="side-nav-item">
                    <a href="{{ route('painel.tv') }}" class="side-nav-link">
                        <i class="mdi mdi-monitor-dashboard"></i>
                        <span> Painel de Controle TV </span>
                    </a>
                </li>

                {{-- Configurações --}}
                <li class="side-nav-item">
                    <a href="#" class="side-nav-link">
                        <i class="mdi mdi-cog-outline"></i>
                        <span> Configurações </span>
                    </a>
                </li>

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
                        <span> Separação </span>
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