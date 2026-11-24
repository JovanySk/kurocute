@extends('master') {{-- Asegúrate de que esto extienda tu layout principal --}}

@section('contenido')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg p-4 border-0">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">Restablecer Contraseña</h3>

                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input id="email" 
                                   type="email" 
                                   name="email" 
                                   value="{{ old('email', $request->email) }}" 
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

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña Nueva</label>
                            <input id="password" 
                                   type="password" 
                                   name="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   required 
                                   autocomplete="new-password">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                            <input id="password_confirmation" 
                                   type="password" 
                                   name="password_confirmation" 
                                   class="form-control @error('password_confirmation') is-invalid @enderror" 
                                   required 
                                   autocomplete="new-password">
                            @error('password_confirmation')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Restablecer Contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection