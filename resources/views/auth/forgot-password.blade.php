@extends('master') {{-- Asegúrate de que esto extienda tu layout principal --}}

@section('contenido')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg p-4 border-0">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">¿Olvidaste tu contraseña?</h3>
                    
                    {{-- Mensaje de información --}}
                    <div class="alert alert-info mb-4 text-center">
                        No hay problema. Introduce tu dirección de correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
                    </div>

                    {{-- Alerta de estado de sesión --}}
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        {{-- Campo de Correo Electrónico --}}
                        <div class="mb-4">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input id="email" 
                                   type="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   required 
                                   autofocus 
                                   placeholder="Tu correo electrónico">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Botón de Enviar --}}
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Enviar Enlace para Restablecer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection