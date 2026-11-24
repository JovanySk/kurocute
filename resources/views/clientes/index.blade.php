@extends('master')

@section('title', 'Listado de Clientes')

@section('contenido')
<div class="container mt-4">
    <h2 class="mb-4">Listado de Clientes</h2>

    {{-- Botón para crear nuevo cliente --}}
    @auth
        @if(auth()->user()->perfil_id == 1)
            <button type="button" class="btn btn-outline-success mb-3" data-bs-toggle="modal" data-bs-target="#crearClienteModal">
                <i class="bi bi-plus-circle"></i> Crear Cliente
            </button>
        @endif
    @endauth

    {{-- Incluir modales --}}
    @include('clientes.crear')
    @include('clientes.editar')

    {{-- Tabla de clientes --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>RFC</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    @if(auth()->user()->perfil_id == 1)
                        <th>Acciones</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($clientes as $cliente)
                    <tr>
                        <td>{{ $cliente->id }}</td>
                        <td>{{ $cliente->nombre }}</td>
                        <td>{{ $cliente->rfc }}</td>
                        <td>{{ $cliente->email }}</td>
                        <td>{{ $cliente->telefono }}</td>
                        @if(auth()->user()->perfil_id == 1)
                            <td class="d-flex">
                                {{-- Botón para actualizar (Editar) --}}
                                <button type="button" class="btn btn-outline-info btn-sm me-2 edit-btn" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editarClienteModal"
                                    data-id="{{ $cliente->id }}"
                                    data-nombre="{{ $cliente->nombre }}"
                                    data-rfc="{{ $cliente->rfc }}"
                                    data-direccion="{{ $cliente->direccion }}"
                                    data-telefono="{{ $cliente->telefono }}"
                                    data-email="{{ $cliente->email }}"
                                    data-descripcion="{{ $cliente->descripcion }}"
                                    data-imagen="{{ $cliente->imagen ? asset('storage/'.$cliente->imagen) : asset('images/default.png') }}">
                                    <i class="bi bi-pencil-square"></i> Actualizar
                                </button>
                                
                                {{-- Formulario para eliminar --}}
                                <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                        onclick="return confirm('¿Estás seguro de eliminar este cliente?')">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $clientes->links('pagination::bootstrap-5') }}
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    // Variable global para la instancia de CKEditor
    let editor;

    /**
     * Función para llenar el modal de edición con los datos del cliente.
     * @param {object} data - Un objeto con los datos del cliente.
     */
    function fillEditModal(data) {
        const modal = $('#editarClienteModal');
        
        // Asignar dinámicamente la acción del formulario y el ID oculto.
        modal.find('form').attr('action', `/clientes/${data.id}`);
        modal.find('#cliente-id').val(data.id);
        
        // Llenar los campos del formulario con los datos recibidos.
        modal.find('.modal-title').text('Editar Cliente #' + data.id);
        modal.find('#nombre').val(data.nombre);
        modal.find('#rfc').val(data.rfc);
        modal.find('#direccion').val(data.direccion);
        modal.find('#telefono').val(data.telefono);
        modal.find('#email').val(data.email);
        
        // Actualizar la vista previa de la imagen.
        modal.find('#imagen-preview').attr('src', data.imagen);
        
        // Destruir la instancia anterior de CKEditor si existe para evitar errores.
        if (editor) {
            editor.destroy().catch(error => console.error("Error al destruir CKEditor:", error));
            editor = null;
        }

        // Inicializar CKEditor y establecer el contenido de la descripción.
        // Se inicializa solo si el elemento existe en el DOM.
        const descripcionElement = document.querySelector('#descripcion');
        if (descripcionElement) {
            ClassicEditor
                .create(descripcionElement, {
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
                .then(newEditor => {
                    editor = newEditor;
                    editor.setData(data.descripcion || '');
                })
                .catch(error => {
                    console.error("Error al inicializar CKEditor:", error);
                });
        }
    }

    $(document).ready(function(){
        // Evento que se dispara al hacer clic en el botón de "Actualizar" (antes "Editar").
        $('.edit-btn').on('click', function() {
            const clienteData = {
                id: $(this).data('id'),
                nombre: $(this).data('nombre'),
                rfc: $(this).data('rfc'),
                direccion: $(this).data('direccion'),
                telefono: $(this).data('telefono'),
                email: $(this).data('email'),
                descripcion: $(this).data('descripcion'),
                imagen: $(this).data('imagen')
            };
            
            // Llenar el modal con los datos del cliente seleccionado.
            fillEditModal(clienteData);
        });

        // Manejar la previsualización de la imagen al seleccionar un archivo.
        $('#editarClienteModal').on('change', 'input[name="imagen"]', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    $('#imagen-preview').attr('src', e.target.result);
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
        
        // Manejar el caso donde hay errores de validación de Laravel.
        @if($errors->any())
            const oldData = {
                id: '{{ old("id") }}',
                nombre: '{{ old("nombre") }}',
                rfc: '{{ old("rfc") }}',
                direccion: '{{ old("direccion") }}',
                telefono: '{{ old("telefono") }}',
                email: '{{ old("email") }}',
                descripcion: `{!! old("descripcion") !!}`,
                imagen: '{{ old("imagen_url") ?? asset("images/default.png") }}'
            };
            
            // Solo muestra el modal si se tiene un ID.
            if (oldData.id) {
                fillEditModal(oldData);
                $('#editarClienteModal').modal('show');
            }
        @endif

        // Cuando el modal de edición se cierra, destruye la instancia de CKEditor.
        $('#editarClienteModal').on('hidden.bs.modal', function() {
            if (editor) {
                editor.destroy().catch(error => console.error("Error al destruir CKEditor:", error));
                editor = null;
            }
        });
    });
</script>
@endpush

@endsection