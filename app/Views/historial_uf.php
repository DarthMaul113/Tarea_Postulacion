<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between mb-3">
    <h2>Historial UF</h2>
    <button id="sync-button" class="btn btn-secondary">
        Sincronizar <i class="bi bi-arrow-repeat"></i>
    </button>
</div>
<table id="tabla-uf" class="table table-striped table-bordered" style="width:100%">
    <thead class="table-dark">
        <th>ID</th>
        <th>Fecha</th>
        <th>Valor</th>
        <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<!-- Modal para editar registro -->
<div class="modal fade" id="modal-editar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel">Editar Registro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-editar">
                    <input type="hidden" id="editar-id">
                    <div class="mb-3">
                        <label for="editar-fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="editar-fecha" required>
                    </div>
                    <div class="mb-3">
                        <label for="editar-valor" class="form-label">Valor</label>
                        <input type="number" class="form-control" id="editar-valor" step="0.01" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let tablaUF;

    // Función para cargar los registros
    function cargarRegistros() {
        Swal.fire({
            title: 'Sincronizando datos...',
            text: 'Por favor espera mientras cargamos la información.',
            icon: 'info',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        setTimeout(() => {
            // Destruir DataTable si ya está inicializado
            if ($.fn.DataTable.isDataTable('#tabla-uf')) {
                $('#tabla-uf').DataTable().destroy();
            }

            // Vaciar el contenido de la tabla
            $('#tabla-uf tbody').empty();

            $.get('/historial-uf/get', function(data) {
                let tabla = '';
                data.forEach(row => {
                    tabla += `
                    <tr>
                        <td>${row.id}</td>
                        <td>${row.fecha}</td>
                        <td>${row.valor}</td>
                        <td>
                            <button class="btn btn-secondary btn-sm" onclick="editarRegistro(${row.id})">
                                <i class="bi bi-pencil text-white" style="font-size: 1.2rem;"></i>
                            </button>
                            <button class="btn btn-secondary btn-sm" onclick="eliminarRegistro(${row.id})">
                                <i class="bi bi-trash text-white" style="font-size: 1.2rem;"></i>
                            </button>
                        </td>
                    </tr>
                `;
                });

                // Insertar las filas en la tabla
                $('#tabla-uf tbody').html(tabla);

                // Reinicializar DataTable
                $(document).ready(function() {
                    $('#tabla-uf').DataTable({
                        paging: true,
                        searching: true,
                        ordering: true,
                        autoWidth: false,
                        lengthChange: true,
                        language: {
                            url: 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json'
                        },
                        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                            '<"row"<"col-sm-12"tr>>' +
                            '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                        drawCallback: function() {
                            $('.page-link').addClass('btn btn-secondary btn-sm mx-1');
                        }
                    });
                });

                Swal.fire('¡Sincronización completa!', 'Los datos han sido cargados correctamente.', 'success');
            }).fail(function() {
                Swal.fire('¡Error!', 'No se pudieron cargar los datos. Verifica tu conexión o la API.', 'error');
            });
        }, 1000);
    }

    // Función para editar registro
    function editarRegistro(id) {
        // Obtener datos del registro por ID
        $.get(`/historial-uf/get/${id}`, function(registro) {
            // Precargar datos en el modal
            $('#editar-id').val(registro.id);
            $('#editar-fecha').val(registro.fecha);
            $('#editar-valor').val(registro.valor);

            // Mostrar el modal
            $('#modal-editar').modal('show');
        }).fail(function() {
            alert('Error al cargar los datos del registro.');
        });
    }

    // Función para actualizar tabla
    function actualizarTabla() {
        if ($.fn.DataTable.isDataTable('#tabla-uf')) {
            $('#tabla-uf').DataTable().destroy();
        }

        $('#tabla-uf tbody').empty();

        $.get('/historial-uf/get', function(data) {
            let tabla = '';
            data.forEach(row => {
                tabla += `
                <tr>
                    <td>${row.id}</td>
                    <td>${row.fecha}</td>
                    <td>${row.valor}</td>
                    <td>
                        <button class="btn btn-secondary btn-sm" onclick="editarRegistro(${row.id})">
                            <i class="bi bi-pencil text-white" style="font-size: 1.2rem;"></i>
                        </button>
                        <button class="btn btn-secondary btn-sm" onclick="eliminarRegistro(${row.id})">
                            <i class="bi bi-trash text-white" style="font-size: 1.2rem;"></i>
                        </button>
                    </td>
                </tr>
            `;
            });

            $('#tabla-uf tbody').html(tabla);

            $(document).ready(function() {
                $('#tabla-uf').DataTable({
                    paging: true,
                    searching: true,
                    ordering: true,
                    autoWidth: false,
                    lengthChange: true,
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json'
                    },
                    dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                        '<"row"<"col-sm-12"tr>>' +
                        '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                    drawCallback: function() {
                        $('.page-link').addClass('btn btn-secondary btn-sm mx-1');
                    }
                });
            });

        }).fail(function() {
            console.error('Error al cargar los datos.');
        });
    }

    // Función para eliminar registro
    function eliminarRegistro(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Enviar solicitud para eliminar
                $.ajax({
                    url: `/historial-uf/delete/${id}`,
                    type: 'DELETE',
                    success: function() {
                        Swal.fire('Eliminado', 'El registro ha sido eliminado.', 'success');
                        actualizarTabla();
                    },
                    error: function() {
                        Swal.fire('¡Error!', 'No se pudo eliminar el registro.', 'error');
                    }
                });
            }
        });
    }

    // Manejo del formulario de edición
    $('#form-editar').submit(function(e) {
        e.preventDefault();

        // Obtén los valores de los campos del formulario
        const id = $('#editar-id').val();
        const data = {
            fecha: $('#editar-fecha').val(), // Incluye la fecha
            valor: $('#editar-valor').val()
        };

        // Realiza la solicitud POST para actualizar el registro
        $.post(`/historial-uf/update/${id}`, data, function() {
            Swal.fire('¡Éxito!', 'El registro se ha actualizado correctamente.', 'success');

            // Oculta el modal y recarga la tabla
            $('#modal-editar').modal('hide');
            actualizarTabla();
        }).fail(function() {
            Swal.fire('¡Error!', 'No se pudo actualizar el registro.', 'error');
        });
    });


    // Cargar registros al iniciar
    $(document).ready(function() {
        cargarRegistros();

        // Sincronizar manualmente
        $('#sync-button').click(function() {
            cargarRegistros();
        });
    });
</script>

<?= $this->endSection() ?>