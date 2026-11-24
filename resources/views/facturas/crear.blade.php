<div class="modal fade" id="crearFacturaModal" tabindex="-1" aria-labelledby="crearFacturaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="crearFacturaModalLabel">Crear Nueva Factura</h5>
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
                
                {{-- Formulario para crear una factura --}}
                <form id="crearFacturaForm" action="{{ route('facturas.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="numero_create" class="form-label">Número</label>
                        <input type="text" name="numero" class="form-control" id="numero_create" value="{{ old('numero') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="valor_create" class="form-label">Valor</label>
                        <input type="number" name="valor" class="form-control" id="valor_create" step="0.01" value="{{ old('valor') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="cliente_id_create" class="form-label">Cliente</label>
                        <select name="cliente_id" id="cliente_id_create" class="form-select" required>
                            <option value="">Seleccione un cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="forma_pago_id_create" class="form-label">Forma de Pago</label>
                        <select name="forma_pago_id" id="forma_pago_id_create" class="form-select" required>
                            <option value="">Seleccione una forma de pago</option>
                            @foreach($formasPago as $forma)
                                <option value="{{ $forma->id }}" {{ old('forma_pago_id') == $forma->id ? 'selected' : '' }}>
                                    {{ $forma->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="estado_id_create" class="form-label">Estado</label>
                        <select name="estado_id" id="estado_id_create" class="form-select" required>
                            <option value="">Seleccione un estado</option>
                            @foreach($estados as $estado)
                                <option value="{{ $estado->id }}" {{ old('estado_id') == $estado->id ? 'selected' : '' }}>
                                    {{ $estado->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="detalles_create" class="form-label">Detalles</label>
                        <textarea name="detalles" id="detalles_create" class="form-control">{{ old('detalles') }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="archivo_create" class="form-label">Archivo de Factura (PDF, XML)</label>
                        <input type="file" name="archivo" class="form-control" id="archivo_create">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-outline-success">Guardar Factura</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- CKEditor 5 Classic build CDN --}}
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    // Inicializa CKEditor para el modal de creación al mostrarse
    document.addEventListener('DOMContentLoaded', function() {
        $('#crearFacturaModal').on('shown.bs.modal', function () {
            ClassicEditor
                .create(document.querySelector('#detalles_create'), {
                    // Toolbar y configuración de CKEditor
                    toolbar: {
                        items: ['undo', 'redo', '|', 'bold', 'italic', 'underline', '|', 'bulletedList', 'numberedList', '|', 'alignment', '|', 'link', 'blockQuote', '|', 'insertTable', '|', 'removeFormat', 'help']
                    },
                    language: 'es'
                })
                .then(newEditor => {
                    window.crearFacturaEditor = newEditor; // Guarda la instancia en el objeto global window
                })
                .catch(error => console.error(error));
        }).on('hidden.bs.modal', function() {
            // Destruye la instancia al cerrar el modal
            if (window.crearFacturaEditor) {
                window.crearFacturaEditor.destroy().catch(error => console.error(error));
                window.crearFacturaEditor = null;
            }
        });
    });
</script>