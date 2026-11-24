@extends('master')

@section('title', 'Listado de Perfiles')

@section('contenido')
<div class="container mt-4">
    <h2 class="mb-4">Listado de Perfiles</h2>

    {{-- Botón para crear nuevo perfil --}}
    @auth
        @if(auth()->user()->perfil_id == 1)
            {{-- Este botón abrirá el modal de creación --}}
            <button type="button" class="btn btn-outline-success mb-3" data-bs-toggle="modal" data-bs-target="#crearPerfilModal">
                <i class="bi bi-plus-circle"></i> Crear Perfil
            </button>
        @endif
    @endauth

    {{-- Incluir el modal de creación y edición --}}
    @include('perfiles.crear')
    @include('perfiles.editar')

    {{-- Tabla de perfiles --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Fecha de Registro</th>
                    <th>Estado</th>
                    @if(auth()->user()->perfil_id == 1)
                        <th>Acciones</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($perfiles as $perfil)
                    <tr>
                        <td>{{ $perfil->id }}</td>
                        <td>{{ $perfil->nombre }}</td>
                        <td>{{ $perfil->fechaRegistro }}</td>
                        <td>{{ $perfil->estadoUsuario }}</td>
                        @if(auth()->user()->perfil_id == 1)
                            <td class="d-flex">
                                {{-- Botón para actualizar (Editar) --}}
                                <button type="button" class="btn btn-outline-info btn-sm me-2 edit-btn" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editarPerfilModal"
                                    data-id="{{ $perfil->id }}"
                                    data-nombre="{{ $perfil->nombre }}"
                                    data-fechaRegistro="{{ $perfil->fechaRegistro }}"
                                    data-estadoUsuario="{{ $perfil->estadoUsuario }}"
                                    data-descripcion="{{ $perfil->descripcion }}">
                                    <i class="bi bi-pencil-square"></i> Actualizar
                                </button>
                                
                                {{-- Formulario para eliminar --}}
                                <form action="{{ route('perfiles.destroy', $perfil->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                        onclick="return confirm('¿Estás seguro de eliminar este perfil?')">
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
        {{ $perfiles->links('pagination::bootstrap-5') }}
    </div>
</div>

@push('scripts')
{{-- Incluimos jQuery si no está en master.blade.php --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<script>
    // Variables globales para las instancias de CKEditor
    let crearEditor;
    let editarEditor;
    
    // Función para llenar el modal de edición con los datos del perfil
    function fillEditModal(data) {
        const modal = $('#editarPerfilModal');
        
        // Asignar dinámicamente la acción del formulario y el ID oculto
        modal.find('form').attr('action', `/perfiles/${data.id}`);
        modal.find('#perfil-id').val(data.id);
        
        // Llenar los campos del formulario con los datos recibidos
        modal.find('.modal-title').text('Editar Perfil #' + data.id);
        modal.find('#nombre_edit').val(data.nombre);
        modal.find('#fechaRegistro_edit').val(data.fechaRegistro);
        modal.find('#estadoUsuario_edit').val(data.estadoUsuario);
        
        // Destruir la instancia anterior de CKEditor si existe
        if (editarEditor) {
            editarEditor.destroy().catch(error => console.error("Error al destruir CKEditor:", error));
            editarEditor = null;
        }

        // Inicializar CKEditor para la descripción
        ClassicEditor
            .create(document.querySelector('#descripcion_edit'), {
                toolbar: {
                    items: [
                        'undo', 'redo', '|', 'bold', 'italic', 'underline', '|',
                        'bulletedList', 'numberedList', '|', 'alignment', '|',
                        'link', 'blockQuote', '|', 'insertTable', '|', 'removeFormat', 'help'
                    ]
                },
                language: 'es'
            })
            .then(newEditor => {
                editarEditor = newEditor;
                editarEditor.setData(data.descripcion || '');
            })
            .catch(error => {
                console.error("Error al inicializar CKEditor en el modal de edición:", error);
            });
    }

    $(document).ready(function(){
        // Inicializar el CKEditor para el modal de CREACIÓN cuando se muestra
        $('#crearPerfilModal').on('shown.bs.modal', function() {
            if (!crearEditor) {
                ClassicEditor
                    .create(document.querySelector('#descripcion-crear'), {
                        // ... misma configuración de toolbar ...
                    })
                    .then(newEditor => crearEditor = newEditor)
                    .catch(error => console.error(error));
            }
        }).on('hidden.bs.modal', function() {
            if (crearEditor) {
                crearEditor.destroy().catch(error => console.error(error));
                crearEditor = null;
            }
        });
        
        // Evento que se dispara al hacer clic en el botón de "Actualizar"
        $('.edit-btn').on('click', function() {
            // Recopilar todos los datos del perfil de los atributos `data-*`
            const perfilData = {
                id: $(this).data('id'),
                nombre: $(this).data('nombre'),
                fechaRegistro: $(this).data('fecharegistro'), // Usar el nombre del atributo data
                estadoUsuario: $(this).data('estadousuario'),
                descripcion: $(this).data('descripcion')
            };
            
            // Llenar el modal con los datos del perfil seleccionado
            fillEditModal(perfilData);
        });
        
        // Manejar el caso donde hay errores de validación
        @if($errors->any())
            const lastEditedId = '{{ old("id") }}';
            if (lastEditedId) {
                const oldData = {
                    id: lastEditedId,
                    nombre: '{{ old("nombre") }}',
                    fechaRegistro: '{{ old("fechaRegistro") }}',
                    estadoUsuario: '{{ old("estadoUsuario") }}',
                    descripcion: `{!! old("descripcion") !!}`
                };
                
                fillEditModal(oldData);
                $('#editarPerfilModal').modal('show');
            } else {
                // Si no hay un ID, puede ser el modal de creación el que tiene errores
                $('#crearPerfilModal').modal('show');
                // Nota: El CKEditor de creación se inicializa automáticamente al mostrar el modal.
            }
        @endif

        // Cuando el modal de edición se cierra, destruye la instancia de CKEditor del modal
        $('#editarPerfilModal').on('hidden.bs.modal', function() {
            if (editarEditor) {
                editarEditor.destroy().catch(error => console.error("Error al destruir CKEditor:", error));
                editarEditor = null;
            }
        });
    });
</script>
@endpush

@endsection