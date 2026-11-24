<div class="modal fade" id="crearPerfilModal" tabindex="-1" aria-labelledby="crearPerfilModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="crearPerfilModalLabel">Crear Nuevo Perfil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="crearPerfilForm" action="{{ route('perfiles.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" id="nombre" required value="{{ old('nombre') }}">
                    </div>

                    <div class="mb-3">
                        <label for="fechaRegistro" class="form-label">Fecha de Registro</label>
                        <input type="date" name="fechaRegistro" class="form-control" id="fechaRegistro" required value="{{ old('fechaRegistro') }}">
                    </div>

                    <div class="mb-3">
                        <label for="estadoUsuario" class="form-label">Estado del Usuario</label>
                        <input type="text" name="estadoUsuario" class="form-control" id="estadoUsuario" required value="{{ old('estadoUsuario') }}">
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea name="descripcion" id="descripcion-crear" class="form-control" rows="10">{{ old('descripcion') }}</textarea>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-outline-success">Guardar Perfil</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Script para CKEditor dentro del modal --}}
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializa CKEditor solo cuando el modal se muestra para evitar problemas
        $('#crearPerfilModal').on('shown.bs.modal', function () {
            ClassicEditor
                .create(document.querySelector('#descripcion-crear'), {
                    toolbar: {
                        items: [
                            'undo', 'redo', '|',
                            'bold', 'italic', 'underline', '|',
                            'bulletedList', 'numberedList', '|',
                            'alignment', '|',
                            'link', 'blockQuote', '|',
                            'insertTable', '|',
                            'removeFormat', 'help'
                        ]
                    },
                    language: 'es'
                })
                .catch(error => {
                    console.error(error);
                });
        });
    });
</script>