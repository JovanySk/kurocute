@extends('master') {{-- Asegúrate de que esto extienda tu layout principal --}}

@section('contenido')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg p-4 border-0">
                <div class="card-body text-center">
                    <h3 class="card-title mb-4">Verifica tu Correo Electrónico</h3>
                    
                    {{-- Mensaje de información principal --}}
                    <div class="alert alert-info mb-4">
                        ¡Gracias por registrarte! Antes de comenzar, por favor verifica tu dirección de correo electrónico haciendo clic en el enlace que te acabamos de enviar. Si no recibiste el correo, te podemos enviar otro con gusto.
                    </div>

                    {{-- Mensaje de éxito si se reenvía el enlace --}}
                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            Se ha enviado un nuevo enlace de verificación a la dirección de correo que proporcionaste durante el registro.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="d-grid gap-2 mb-3">
                        {{-- Formulario para reenviar el correo --}}
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">
                                Reenviar Correo de Verificación
                            </button>
                        </form>
                    </div>

                    {{-- Formulario para cerrar sesión --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-link text-muted">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection