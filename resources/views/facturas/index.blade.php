@extends('master')

@section('contenido')
<div class="container mt-4">
    <h2 class="mb-4">Listado de Facturas</h2>

    @if(auth()->user()->perfil_id == 1)
        {{-- Cambiamos el link por un botón que abre el modal --}}
        <button type="button" class="btn btn-outline-success mb-3" data-bs-toggle="modal" data-bs-target="#crearFacturaModal">
            <i class="bi bi-plus-circle"></i> Crear Factura
        </button>
    @endif

    {{-- Incluir los modales de crear y editar --}}
    @include('facturas.crear')
    @include('facturas.editar')

    <table class="table table-bordered table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>id</th>
                <th>Número</th>
                <th>Detalles</th>
                <th>Valor</th>
                <th>Cliente</th>
                <th>Forma de Pago</th>
                <th>Estado</th>
                <th>Archivo</th>
                @if(auth()->user()->perfil_id == 1)
                    <th>Acciones</th> {{-- Agrupamos Actualizar y Eliminar en una columna --}}
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($facturas as $factura)
                <tr>
                    <td>{{ $factura->id }}</td>
                    <td>{{ $factura->numero }}</td>
                    <td>{!! Str::limit(strip_tags($factura->detalles, '<b><i><strong><em><u><p><br><ul><li>'), 100) !!}</td>
                    <td>{{ $factura->valor }}</td>
                    <td>{{ $factura->cliente->nombre ?? 'Sin cliente' }}</td>
                    <td>{{ $factura->formaPago->nombre ?? 'Sin forma' }}</td>
                    <td>{{ $factura->estado->nombre ?? 'Sin estado' }}</td>
                    <td>
                        @if ($factura->archivo)
                            <a href="{{ asset('storage/' . $factura->archivo) }}" target="_blank">Ver archivo</a>
                        @else
                            Sin archivo
                        @endif
                    </td>
                    @if(auth()->user()->perfil_id == 1)
                        <td class="d-flex">
                            {{-- Botón para editar con atributos data-* --}}
                            <button type="button" class="btn btn-sm btn-outline-info me-2 edit-btn" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editarFacturaModal"
                                data-id="{{ $factura->id }}"
                                data-numero="{{ $factura->numero }}"
                                data-detalles="{{ $factura->detalles }}"
                                data-valor="{{ $factura->valor }}"
                                data-cliente_id="{{ $factura->cliente_id }}"
                                data-forma_pago_id="{{ $factura->forma_pago_id }}"
                                data-estado_id="{{ $factura->estado_id }}"
                                data-archivo="{{ $factura->archivo ? asset('storage/'.$factura->archivo) : '' }}">
                                <i class="bi bi-pencil-square"></i> Actualizar
                            </button>
                            
                            {{-- Formulario para eliminar --}}
                            <form action="{{ route('facturas.destroy', $factura->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro de eliminar esta factura?')">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-4">
        {{ $facturas->links('pagination::bootstrap-5') }}
    </div>
</div>

@push('scripts')
{{-- Incluimos jQuery si no está en master.blade.php --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<script>
    // Variables globales para las instancias de CKEditor
    let editarFacturaEditor;

    /**
     * Función para llenar el modal de edición con los datos de la factura.
     * @param {object} data - Un objeto con los datos de la factura.
     */
    function fillEditModal(data) {
        const modal = $('#editarFacturaModal');
        
        // Asignar dinámicamente la acción del formulario y el ID oculto
        modal.find('form').attr('action', `/facturas/${data.id}`);
        modal.find('#factura-id').val(data.id);
        
        // Llenar los campos del formulario con los datos recibidos
        modal.find('.modal-title').text('Editar Factura #' + data.id);
        modal.find('#numero_edit').val(data.numero);
        modal.find('#valor_edit').val(data.valor);
        modal.find('#cliente_id_edit').val(data.cliente_id);
        modal.find('#forma_pago_id_edit').val(data.forma_pago_id);
        modal.find('#estado_id_edit').val(data.estado_id);
        
        // Previsualización del archivo
        const archivoPreview = modal.find('#archivo-preview');
        if (data.archivo) {
            archivoPreview.html(`<a href="${data.archivo}" target="_blank">Ver archivo actual</a>`);
        } else {
            archivoPreview.html('Sin archivo');
        }
        
        // Destruir la instancia anterior de CKEditor si existe
        if (editarFacturaEditor) {
            editarFacturaEditor.destroy().catch(error => console.error("Error al destruir CKEditor:", error));
            editarFacturaEditor = null;
        }

        // Inicializar CKEditor para los detalles
        ClassicEditor
            .create(document.querySelector('#detalles_edit'), {
                toolbar: {
                    items: ['undo', 'redo', '|', 'bold', 'italic', 'underline', '|', 'bulletedList', 'numberedList', '|', 'alignment', '|', 'link', 'blockQuote', '|', 'insertTable', '|', 'removeFormat', 'help']
                },
                language: 'es'
            })
            .then(newEditor => {
                editarFacturaEditor = newEditor;
                editarFacturaEditor.setData(data.detalles || '');
            })
            .catch(error => console.error("Error al inicializar CKEditor en el modal de edición:", error));
    }

    $(document).ready(function(){
        // Evento que se dispara al hacer clic en el botón de "Actualizar"
        $('.edit-btn').on('click', function() {
            // Recopilar todos los datos de la factura de los atributos `data-*`
            const facturaData = {
                id: $(this).data('id'),
                numero: $(this).data('numero'),
                detalles: $(this).data('detalles'),
                valor: $(this).data('valor'),
                cliente_id: $(this).data('cliente_id'),
                forma_pago_id: $(this).data('forma_pago_id'),
                estado_id: $(this).data('estado_id'),
                archivo: $(this).data('archivo')
            };
            
            // Llenar el modal con los datos de la factura seleccionada
            fillEditModal(facturaData);
        });
        
        // Manejar el caso donde hay errores de validación
        @if($errors->any())
            const lastEditedId = '{{ old("id") }}';
            if (lastEditedId) {
                // Si hay un ID en old(), se abre el modal de edición
                const oldData = {
                    id: lastEditedId,
                    numero: '{{ old("numero") }}',
                    valor: '{{ old("valor") }}',
                    detalles: `{!! old("detalles") !!}`,
                    cliente_id: '{{ old("cliente_id") }}',
                    forma_pago_id: '{{ old("forma_pago_id") }}',
                    estado_id: '{{ old("estado_id") }}',
                    archivo: '{{ old("archivo") }}'
                };
                
                fillEditModal(oldData);
                $('#editarFacturaModal').modal('show');
            } else {
                // Si no hay un ID, asumimos que los errores son para el modal de creación
                $('#crearFacturaModal').modal('show');
                // CKEditor se inicializará automáticamente gracias al evento `shown.bs.modal`
            }
        @endif

        // Cuando el modal de edición se cierra, destruye la instancia de CKEditor
        $('#editarFacturaModal').on('hidden.bs.modal', function() {
            if (editarFacturaEditor) {
                editarFacturaEditor.destroy().catch(error => console.error("Error al destruir CKEditor:", error));
                editarFacturaEditor = null;
            }
        });
    });
</script>
@endpush

@endsection