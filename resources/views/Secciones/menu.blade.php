<nav class="navbar navbar-expand-lg" style="background-color: #6a0dad;">
    <!--"navbar navbar-expand-lg bg-primary" data-bs-theme="dark" color azul-->
    <div class="container-fluid">
               <a class="navbar-brand text-white" href="{{ url('/') }}">
            KuroCute
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01"
            aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarColor01">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">Inicio</a>
                </li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('clientes.index') }}">Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('perfiles.index') }}">Perfiles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ url('/facturas') }}">Facturas</a>
                    </li>
                @endauth
            </ul>
            <!-- Opciones de autenticación -->
            <ul class="navbar-nav ms-auto">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Iniciar sesión</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Registrarse</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="userDropdown">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item" type="submit">Cerrar sesión</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
            <!-- Fin opciones de autenticación -->
            <form class="d-flex" action="{{ url('/') }}" method="GET">
                <input class="form-control me-sm-2" type="search" name="q" placeholder="Buscar">
                <button class="btn btn-secondary my-2 my-sm-0" type="submit">Buscar</button>
            </form>
        </div>
    </div>
</nav>
