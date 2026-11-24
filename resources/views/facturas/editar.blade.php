<div class="modal fade" id="editarFacturaModal" tabindex="-1" aria-labelledby="editarFacturaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarFacturaModalLabel">Editar Factura</h5>
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
                
                {{-- Formulario para editar una factura --}}
                <form id="editarFacturaForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    {{-- Campo oculto para almacenar el ID de la factura --}}
                    <input type="hidden" name="id" id="factura-id">

                    <div class="mb-3">
                        <label for="numero_edit" class="form-label">Número</label>
                        <input type="text" name="numero" class="form-control" id="numero_edit" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="valor_edit" class="form-label">Valor</label>
                        <input type="number" name="valor" class="form-control" id="valor_edit" step="0.01" required>
                    </div>

                    <div class="mb-3">
                        <label for="cliente_id_edit" class="form-label">Cliente</label>
                        <select name="cliente_id" id="cliente_id_edit" class="form-select" required>
                            <option value="">Seleccione un cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="forma_pago_id_edit" class="form-label">Forma de Pago</label>
                        <select name="forma_pago_id" id="forma_pago_id_edit" class="form-select" required>
                            <option value="">Seleccione una forma de pago</option>
                            @foreach($formasPago as $forma)
                                <option value="{{ $forma->id }}">{{ $forma->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="estado_id_edit" class="form-label">Estado</label>
                        <select name="estado_id" id="estado_id_edit" class="form-select" required>
                            <option value="">Seleccione un estado</option>
                            @foreach($estados as $estado)
                                <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="detalles_edit" class="form-label">Detalles</label>
                        <textarea name="detalles" id="detalles_edit" class="form-control"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="archivo_edit" class="form-label">Archivo de Factura (PDF, XML)</label>
                        <input type="file" name="archivo" class="form-control" id="archivo_edit">
                        <div id="archivo-preview" class="mt-2"></div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-outline-info">Actualizar Factura</button>
                </form>
            </div>
        </div>
    </div>
</div>