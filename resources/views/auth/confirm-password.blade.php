@extends('master') {{-- Asegúrate de que esto extienda tu layout principal --}}

@section('contenido')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg p-4 border-0">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">Confirma tu Contraseña</h3>
                    
                    {{-- Mensaje de seguridad --}}
                    <div class="alert alert-info mb-4 text-center">
                        Esta es un área segura de la aplicación. Por favor, confirma tu contraseña para continuar.
                    </div>

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        {{-- Campo de Contraseña --}}
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input id="password" 
                                   type="password" 
                                   name="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   required 
                                   autocomplete="current-password"
                                   placeholder="Ingresa tu contraseña">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Botón de Confirmar --}}
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Confirmar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection