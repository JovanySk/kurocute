@extends('master') {{-- Asegúrate de que esto extienda tu layout principal --}}

@section('contenido')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg p-4 border-0">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">Iniciar Sesión</h3>

                    {{-- Alerta de estado de sesión --}}
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Campo de Correo Electrónico --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input id="email" 
                                   type="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   required 
                                   autofocus 
                                   autocomplete="username">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Campo de Contraseña --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input id="password" 
                                   type="password" 
                                   name="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   required 
                                   autocomplete="current-password">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Recordarme --}}
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                            <label class="form-check-label" for="remember_me">
                                Recordarme
                            </label>
                        </div>

                        {{-- Botones y enlaces --}}
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Iniciar Sesión</button>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            @if (Route::has('password.request'))
                                <a class="text-muted text-decoration-none small" href="{{ route('password.request') }}">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            @endif
                            @if (Route::has('register'))
                                <a class="text-muted text-decoration-none small" href="{{ route('register') }}">
                                    ¿No tienes una cuenta? Regístrate
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection