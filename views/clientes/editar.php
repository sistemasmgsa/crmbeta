<?php require_once 'views/layout/header.php'; ?>

<h1>Editar Cliente</h1>

<?php if (isset($data['error'])) : ?>
    <p class="error-message"><?php echo $data['error']; ?></p>
<?php endif; ?>

<form action="<?php echo SITE_URL; ?>index.php?controller=clientes&action=editar" method="POST">
    <input type="hidden" name="id_cliente" id="id_cliente_form" value="<?php echo $data['cliente']['id_cliente']; ?>">
    <div class="form-group">
        <label for="nombre_cliente">Nombre del Cliente</label>
        <input type="text" name="nombre_cliente" id="nombre_cliente" value="<?php echo $data['cliente']['nombre_cliente']; ?>" required>
    </div>
    <div class="form-group">
        <label for="direccion_cliente">Dirección</label>
        <input type="text" name="direccion_cliente" id="direccion_cliente" value="<?php echo $data['cliente']['direccion_cliente']; ?>">
    </div>
    <div class="form-group">
        <label for="telefono_cliente">Teléfono</label>
        <input type="text" name="telefono_cliente" id="telefono_cliente" value="<?php echo $data['cliente']['telefono_cliente']; ?>">
    </div>
    <div class="form-group">
        <label for="website_cliente">Sitio Web</label>
        <input type="url" name="website_cliente" id="website_cliente" value="<?php echo $data['cliente']['website_cliente']; ?>">
    </div>
    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="<?php echo SITE_URL; ?>index.php?controller=clientes&action=index" class="btn btn-secondary">Cancelar</a>
</form>

<hr>

<h2>Actividades</h2>
<div id="historial-actividades">
    <!-- Las actividades se cargarán aquí con AJAX -->
</div>
<hr>
<h3>Registrar Nueva Actividad</h3>
<form id="form-nueva-actividad">
    <input type="hidden" name="id_cliente" value="<?php echo $data['cliente']['id_cliente']; ?>">
    <div class="form-group">
        <label for="tipo_actividad">Tipo</label>
        <select name="tipo_actividad" id="tipo_actividad" required>
            <option value="Llamada">Llamada</option>
            <option value="Reunión">Reunión</option>
            <option value="Correo">Correo</option>
            <option value="Tarea">Tarea</option>
        </select>
    </div>
    <div class="form-group">
        <label for="asunto">Asunto</label>
        <input type="text" name="asunto" id="asunto" required>
    </div>
    <div class="form-group">
        <label for="descripcion">Descripción</label>
        <textarea name="descripcion" id="descripcion" rows="3"></textarea>
    </div>
    <div class="form-group">
        <label for="fecha_actividad">Fecha y Hora</label>
        <input type="datetime-local" name="fecha_actividad" id="fecha_actividad" required>
    </div>
    <button type="submit" class="btn btn-primary">Registrar Actividad</button>
</form>

<hr>

<h2>Contactos</h2>
<button id="btn-nuevo-contacto" class="btn btn-primary">Nuevo Contacto</button>
<table id="tabla-contactos">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Cargo</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <!-- Los contactos se cargarán aquí con AJAX -->
    </tbody>
</table>

<!-- Modal para crear/editar contactos (inicialmente oculto) -->
<div id="modal-contacto" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5);">
    <div style="background:#fff; width:500px; margin:100px auto; padding:20px; border-radius:5px;">
        <h3 id="modal-titulo"></h3>
        <form id="form-contacto">
            <input type="hidden" name="id_contacto" id="id_contacto">
            <input type="hidden" name="id_cliente" id="id_cliente_contacto" value="<?php echo $data['cliente']['id_cliente']; ?>">
            <div class="form-group">
                <label for="nombre_contacto">Nombre</label>
                <input type="text" name="nombre_contacto" id="nombre_contacto" required>
            </div>
            <div class="form-group">
                <label for="cargo_contacto">Cargo</label>
                <input type="text" name="cargo_contacto" id="cargo_contacto">
            </div>
            <div class="form-group">
                <label for="correo_contacto">Correo</label>
                <input type="email" name="correo_contacto" id="correo_contacto">
            </div>
            <div class="form-group">
                <label for="telefono_contacto">Teléfono</label>
                <input type="text" name="telefono_contacto" id="telefono_contacto">
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
            <button type="button" id="btn-cancelar-modal" class="btn btn-secondary">Cancelar</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const idCliente = document.getElementById('id_cliente_form').value;
    const historialActividades = document.getElementById('historial-actividades');
    const formNuevaActividad = document.getElementById('form-nueva-actividad');
    const tablaContactosBody = document.querySelector('#tabla-contactos tbody');
    const modal = document.getElementById('modal-contacto');
    const formContacto = document.getElementById('form-contacto');
    let editando = false;

    function cargarActividades() {
        fetch(`<?php echo SITE_URL; ?>index.php?controller=actividades&action=listarPorCliente&id_cliente=${idCliente}`)
            .then(response => response.json())
            .then(data => {
                historialActividades.innerHTML = '';
                if (data.length === 0) {
                    historialActividades.innerHTML = '<p>No hay actividades registradas.</p>';
                    return;
                }
                data.forEach(actividad => {
                    const actividadDiv = document.createElement('div');
                    actividadDiv.style.border = '1px solid #ddd';
                    actividadDiv.style.padding = '10px';
                    actividadDiv.style.marginBottom = '10px';
                    actividadDiv.innerHTML = `
                        <strong>${actividad.tipo_actividad}: ${actividad.asunto}</strong>
                        <p>${actividad.descripcion}</p>
                        <small>Fecha: ${new Date(actividad.fecha_actividad).toLocaleString()} | Registrado por: ${actividad.nombre_usuario}</small>
                    `;
                    historialActividades.appendChild(actividadDiv);
                });
            });
    }

    formNuevaActividad.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch(`<?php echo SITE_URL; ?>index.php?controller=actividades&action=crear`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                formNuevaActividad.reset();
                cargarActividades();
            } else {
                alert(data.message);
            }
        });
    });

    function cargarContactos() {
        fetch(`<?php echo SITE_URL; ?>index.php?controller=contactos&action=listarPorCliente&id_cliente=${idCliente}`)
            .then(response => response.json())
            .then(data => {
                tablaContactosBody.innerHTML = '';
                data.forEach(contacto => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${contacto.nombre_contacto}</td>
                        <td>${contacto.cargo_contacto}</td>
                        <td>${contacto.correo_contacto}</td>
                        <td>${contacto.telefono_contacto}</td>
                        <td>
                            <button class="btn btn-secondary btn-editar-contacto" data-id="${contacto.id_contacto}">Editar</button>
                            <button class="btn btn-danger btn-eliminar-contacto" data-id="${contacto.id_contacto}">Eliminar</button>
                        </td>
                    `;
                    tablaContactosBody.appendChild(tr);
                });
            });
    }

    function abrirModal(titulo, data = {}) {
        document.getElementById('modal-titulo').textContent = titulo;
        formContacto.reset();
        document.getElementById('id_cliente_contacto').value = idCliente;
        if (data.id_contacto) {
            editando = true;
            document.getElementById('id_contacto').value = data.id_contacto;
            document.getElementById('nombre_contacto').value = data.nombre_contacto || '';
            document.getElementById('cargo_contacto').value = data.cargo_contacto || '';
            document.getElementById('correo_contacto').value = data.correo_contacto || '';
            document.getElementById('telefono_contacto').value = data.telefono_contacto || '';
        } else {
            editando = false;
        }
        modal.style.display = 'block';
    }

    document.getElementById('btn-nuevo-contacto').addEventListener('click', () => {
        abrirModal('Nuevo Contacto');
    });

    document.getElementById('btn-cancelar-modal').addEventListener('click', () => {
        modal.style.display = 'none';
    });

    tablaContactosBody.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-editar-contacto')) {
            const idContacto = e.target.dataset.id;
            fetch(`<?php echo SITE_URL; ?>index.php?controller=contactos&action=obtener&id_contacto=${idContacto}`)
                .then(response => response.json())
                .then(data => {
                    abrirModal('Editar Contacto', data);
                });
        }

        if (e.target.classList.contains('btn-eliminar-contacto')) {
            if (confirm('¿Estás seguro de que quieres eliminar este contacto?')) {
                const idContacto = e.target.dataset.id;
                const formData = new FormData();
                formData.append('id_contacto', idContacto);

                fetch(`<?php echo SITE_URL; ?>index.php?controller=contactos&action=eliminar`, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        cargarContactos();
                    } else {
                        alert(data.message);
                    }
                });
            }
        }
    });

    formContacto.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const url = editando ? `<?php echo SITE_URL; ?>index.php?controller=contactos&action=actualizar` : `<?php echo SITE_URL; ?>index.php?controller=contactos&action=crear`;

        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                modal.style.display = 'none';
                cargarContactos();
            } else {
                alert(data.message);
            }
        });
    });

    // Carga inicial
    cargarActividades();
    cargarContactos();
});
</script>

<?php require_once 'views/layout/footer.php'; ?>
