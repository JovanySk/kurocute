<div class="modal fade" id="editarPerfilModal" tabindex="-1" aria-labelledby="editarPerfilModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarPerfilModalLabel">Editar Perfil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Alertas de validación --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                {{-- Formulario para editar un perfil --}}
                <form id="editarPerfilForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') {{-- Usamos el método PUT para actualizar --}}
                    
                    {{-- Campo oculto para almacenar el ID del perfil --}}
                    <input type="hidden" name="id" id="perfil-id">

                    <div class="mb-3">
                        <label for="nombre_edit" class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" id="nombre_edit" required>
                    </div>

                    <div class="mb-3">
                        <label for="fechaRegistro_edit" class="form-label">Fecha de Registro</label>
                        <input type="date" name="fechaRegistro" class="form-control" id="fechaRegistro_edit" required>
                    </div>

                    <div class="mb-3">
                        <label for="estadoUsuario_edit" class="form-label">Estado del Usuario</label>
                        <input type="text" name="estadoUsuario" class="form-control" id="estadoUsuario_edit" required>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion_edit" class="form-label">Descripción</label>
                        <textarea name="descripcion" id="descripcion_edit" class="form-control" rows="10"></textarea>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-outline-info">Actualizar Perfil</button>
                </form>
            </div>
        </div>
    </div>
</div>