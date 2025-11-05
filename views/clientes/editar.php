<?php require_once 'views/layout/header.php'; ?>

<h1>Editar Cliente</h1>

<!-- ✅ Incluye SweetAlert2 (solo una vez, en tu layout o aquí mismo) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (isset($data['error'])) : ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "<?php echo addslashes($data['error']); ?>",
                confirmButtonText: "Aceptar",
                confirmButtonColor: "#d33"
            });
        });
    </script>
<?php endif; ?>

<style>
/* --- Contenedor general de tabs --- */
.tab-container {
    margin-top: 20px;
    background: #fff;
    border: 2px solid #dee2e6;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.08);
    overflow: hidden;
}

/* --- Barra de navegación de tabs --- */
.tab-nav {
    display: flex;
    background: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

/* --- Botones de las pestañas --- */
.tab-link {
    flex: 1;
    text-align: center;
    background: #f8f9fa;
    color: #495057;
    font-weight: 600;
    font-size: 16px;
    padding: 12px 0;
    border: none;
    outline: none;
    cursor: pointer;
    transition: all 0.25s ease;
    border-right: 1px solid #dee2e6;
}

.tab-link:last-child {
    border-right: none;
}

/* --- Efecto hover --- */
.tab-link:hover {
    background: #e9ecef;
    color: #212529;
}

/* --- Tab activo --- */
.tab-link.active {
    background: #007bff;
    color: #000000ff;
    box-shadow: inset 0 -4px 0 #800000ff;
}

/* --- Contenido de cada pestaña --- */
.tab-content { 
    display: none;
    padding: 25px;
    background: #fff;
    border-radius: 0 0 10px 10px;
    min-height: 300px;
}

/* --- Contenido visible (activo) --- */
.tab-content.active {
    display: block;
    animation: fadeIn 0.3s ease-in-out;
}

/* --- Animación suave al cambiar --- */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(5px); }
    to { opacity: 1; transform: translateY(0); }
}

/* --- Responsive (móviles) --- */
@media (max-width: 768px) {
    .tab-link {
        font-size: 14px;
        padding: 10px;
    }
    .tab-content {
        padding: 15px;
    }
}
</style>


<div class="tab-container">
    <div class="tab-nav">
        <button class="tab-link active" onclick="openTab(event, 'cliente')">Cliente</button>

        <button class="tab-link" onclick="openTab(event, 'actividades')">Nueva Actividad</button>
        <button class="tab-link" onclick="openTab(event, 'registro')">Historial</button>


        <button class="tab-link" onclick="openTab(event, 'contacto')">Contacto</button>
    </div>

    <!-- Contenido de la Pestaña Cliente -->
    <div id="cliente" class="tab-content active">
        <form action="<?php echo SITE_URL; ?>index.php?controller=clientes&action=editar" method="POST">
            <input type="hidden" name="id_cliente" id="id_cliente_form" value="<?php echo $data['cliente']['id_cliente']; ?>">

            <div class="form-group"
                style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                <label for="id_tipo_documento">Tipo de Documento</label>
                <select name="id_tipo_documento" id="id_tipo_documento"
                    style="font-size: 16px; padding: 6px; width: 200px;">
                    <?php foreach ($data['tipos_documento'] as $tipo) : ?>
                        <option value="<?php echo $tipo['id_tipo_documento']; ?>"
                            <?php echo ($data['cliente']['id_tipo_documento'] == $tipo['id_tipo_documento']) ? 'selected' : ''; ?>>
                            <?php echo $tipo['nombre_documento']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                 <label for="numero_documento"
                    style="font-size: 16px; margin: 0; white-space: nowrap;">
                    Número de Documento
                </label>
                <input type="text" name="numero_documento" id="numero_documento"
                    style="font-size: 16px; padding: 6px; width: 200px;"
                    value="<?php echo $data['cliente']['numero_documento']; ?>"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                    required>
                <button type="button" id="btnBuscarSunat"
                        style="background: #007bff; color: #fff; border: none;
                            padding: 6px 12px; font-size: 14px; cursor: pointer;
                            border-radius: 4px; margin: 0;">
                    Buscar SUNAT
                </button>

            </div>


            <div class="form-group">
                <label for="nombre_cliente">Nombre del Cliente</label>
                <input type="text" name="nombre_cliente" id="nombre_cliente"
                    value="<?php echo htmlspecialchars($data['cliente']['nombre_cliente']); ?>"
                    style="font-size: 16px; padding: 6px; width: 800px;"
                    required>
            </div>

            <div class="form-group">
                <label for="direccion_cliente">Dirección</label>
                <input type="text" name="direccion_cliente" id="direccion_cliente"
                    value="<?php echo htmlspecialchars($data['cliente']['direccion_cliente']); ?>"
                    style="font-size: 16px; padding: 6px; width: 800px;">
            </div>


            <div class="form-group" 
            style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">

            <label for="departamento">Departamento</label>
            <select name="departamento" id="departamento"
                    style="font-size: 16px; padding: 6px; width: 250px;">
                    <?php foreach ($data['departamentos'] as $departamento) : ?>
                        <option value="<?php echo $departamento; ?>"
                            <?php echo ($data['cliente']['departamento'] == $departamento) ? 'selected' : ''; ?>>
                            <?php echo $departamento; ?>
                        </option>
                    <?php endforeach; ?>
            </select>

                <label for="provincia">Provincia</label>
                <select name="provincia" id="provincia"
                    style="font-size: 16px; padding: 6px; width: 250px;">
                </select>

                <label for="distrito">Distrito</label>
                <select name="distrito" id="distrito"
                    style="font-size: 16px; padding: 6px; width: 250px;">
                </select>
        


            </div>


            <input type="hidden" name="id_ubigeo" id="id_ubigeo"
                value="<?php echo $data['cliente']['id_ubigeo']; ?>">


            <div class="form-group" 
                style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">

                <label for="telefono_cliente">Teléfono</label>
                <input type="text" name="telefono_cliente" id="telefono_cliente"
                    value="<?php echo htmlspecialchars($data['cliente']['telefono_cliente']); ?>"
                    style="font-size: 16px; padding: 6px; width: 200px;">

                                    <label for="correo_electronico">Correo Electrónico</label>
                <input type="email" name="correo_electronico" id="correo_electronico"
                    value="<?php echo htmlspecialchars($data['cliente']['correo_electronico']); ?>"
                    style="font-size: 16px; padding: 6px; width: 200px;">

                <label for="website_cliente">Sitio Web</label>
                <input type="url" name="website_cliente" id="website_cliente"
                    value="<?php echo htmlspecialchars($data['cliente']['website_cliente']); ?>"
                    style="font-size: 16px; padding: 6px; width: 350px;">


            </div>

            <div class="form-group">
                <label for="observaciones">Observaciones</label>
                <textarea name="observaciones" id="observaciones"
                    style="font-size: 16px; padding: 6px; width: 1000px;"><?php echo htmlspecialchars($data['cliente']['observaciones']); ?></textarea>
            </div>



            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="<?php echo SITE_URL; ?>index.php?controller=clientes&action=index" class="btn btn-secondary">Cancelar</a>

            <a href="<?php echo SITE_URL; ?>index.php?controller=calendario&action=index" 
            class="btn btn-secondary" style="background-color: #007bff; margin-left: 890px;">Ver Calendario</a>
        </form>
    </div>

    <!-- Contenido de la Pestaña Actividades -->
    <div id="actividades" class="tab-content">

        <form id="form-nueva-actividad">
            <input type="hidden" name="id_cliente" value="<?php echo $data['cliente']['id_cliente']; ?>">
            <div class="form-group"
            style="display: flex; align-items: center; gap: 25px; margin-bottom: 20px;">

                <label for="tipo_actividad">Tipo</label>
                <select name="tipo_actividad" id="tipo_actividad" style="font-size: 16px; padding: 6px; width: 300px;" required>
                    <option value="Mensajes">Mensajes</option>                    
                    <option value="Llamada">Llamada</option>
                    <option value="Reunión">Reunión</option>
                    <option value="Correo">Correo</option>
                    <option value="Tarea">Tarea</option>
                </select>

                  <label for="fecha_actividad">Fecha y Hora</label>
                <input type="datetime-local" name="fecha_actividad" id="fecha_actividad" 
                style="font-size: 16px; padding: 6px; width: 300px;" required>

            </div>


            <div class="form-group">
                <label for="id_contacto_actividad">Contacto (Opcional)</label>
                <select name="id_contacto" id="id_contacto_actividad" style="font-size: 16px; padding: 6px; width: 300px;">
                    <option value="">-- Sin Contacto --</option>
                </select>
            </div>

            <div class="form-group">
                <label for="asunto">Asunto</label>
                <input type="text" name="asunto" id="asunto" style="font-size: 16px; padding: 6px; width: 1000px;" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea name="descripcion" id="descripcion" rows="3" style="font-size: 16px; padding: 6px; width: 1000px;"></textarea>
            </div>


            <button type="submit" class="btn btn-primary">Registrar Actividad</button>
             
        </form>
     

    </div>



    <div id="registro" class="tab-content">

        <div id="historial-actividades">
            <!-- Las actividades se cargarán aquí con AJAX -->
        </div>
        <div id="paginacion-actividades" style="margin-top: 20px; display:flex; gap:5px; flex-wrap:wrap;">
            <!-- Botones de paginación aparecerán aquí -->
        </div>
      

    </div>

    

    <!-- Contenido de la Pestaña Contacto -->
    <div id="contacto" class="tab-content">
    
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
            <div style="background:#fff; width:500px; margin:100px auto; padding:30px; border-radius:5px;">
                <h3 id="modal-titulo"></h3>
                <form id="form-contacto">
                    <input type="hidden" name="id_contacto" id="id_contacto">
                    <input type="hidden" name="id_cliente" id="id_cliente_contacto" value="<?php echo $data['cliente']['id_cliente']; ?>">
                    <div class="form-group">
                        <label for="nombre_contacto">Nombre</label>
                        <input type="text" name="nombre_contacto" id="nombre_contacto" style="font-size: 16px; padding: 6px; width: 480px;" required>
                    </div>
                    <div class="form-group">
                        <label for="cargo_contacto">Cargo</label>
                        <input type="text" name="cargo_contacto" id="cargo_contacto" style="font-size: 16px; padding: 6px; width: 480px;">
                    </div>
                    <div class="form-group">
                        <label for="correo_contacto">Correo</label>
                        <input type="email" name="correo_contacto" id="correo_contacto" style="font-size: 16px; padding: 6px; width: 480px;">
                    </div>
                    <div class="form-group">
                        <label for="telefono_contacto">Teléfono</label>
                        <input type="text" name="telefono_contacto" id="telefono_contacto" style="font-size: 16px; padding: 6px; width: 480px;">
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" id="btn-cancelar-modal" class="btn btn-secondary">Cancelar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>


document.addEventListener('DOMContentLoaded', function() {
    
    // Script para Ubigeo
    const ubigeos = <?php echo json_encode($data['ubigeos']); ?>;
    const departamentoSelect = document.getElementById('departamento');
    const provinciaSelect = document.getElementById('provincia');
    const distritoSelect = document.getElementById('distrito');
    const idUbigeoInput = document.getElementById('id_ubigeo');
    const clienteUbigeo = <?php echo json_encode($data['cliente']['id_ubigeo']); ?>;

    function populateProvincias() {
        const selectedDepartamento = departamentoSelect.value;
        provinciaSelect.innerHTML = '';
        distritoSelect.innerHTML = '';
        const provincias = [...new Set(ubigeos.filter(u => u.departamento === selectedDepartamento).map(u => u.provincia))];
        provincias.forEach(provincia => {
            const option = document.createElement('option');
            option.value = provincia;
            option.textContent = provincia;
            provinciaSelect.appendChild(option);
        });
        populateDistritos();
    }

    function populateDistritos() {
        const selectedProvincia = provinciaSelect.value;
        distritoSelect.innerHTML = '';
        const distritos = [...new Set(ubigeos.filter(u => u.provincia === selectedProvincia).map(u => u.distrito))];
        distritos.forEach(distrito => {
            const option = document.createElement('option');
            option.value = distrito;
            option.textContent = distrito;
            distritoSelect.appendChild(option);
        });
        updateUbigeoId();
    }

    function updateUbigeoId() {
        const selectedDepartamento = departamentoSelect.value;
        const selectedProvincia = provinciaSelect.value;
        const selectedDistrito = distritoSelect.value;
        const ubigeo = ubigeos.find(u => u.departamento === selectedDepartamento && u.provincia === selectedProvincia && u.distrito === selectedDistrito);
        if (ubigeo) {
            idUbigeoInput.value = ubigeo.id_ubigeo;
        }
    }

    departamentoSelect.addEventListener('change', populateProvincias);
    provinciaSelect.addEventListener('change', populateDistritos);
    distritoSelect.addEventListener('change', updateUbigeoId);

    const initialUbigeo = ubigeos.find(u => u.id_ubigeo == clienteUbigeo);
    if (initialUbigeo) {
        departamentoSelect.value = initialUbigeo.departamento;
        populateProvincias();
        provinciaSelect.value = initialUbigeo.provincia;
        populateDistritos();
        distritoSelect.value = initialUbigeo.distrito;
        updateUbigeoId();
    } else {
        populateProvincias();
    }

    // Script para SUNAT
    document.getElementById("btnBuscarSunat").addEventListener("click", function() {
        const numero = document.getElementById("numero_documento").value;
        const tipo = document.getElementById("id_tipo_documento").value;
        if (numero.trim() === "") { alert("Ingrese un número de documento"); return; }
        if (tipo !== "1" && tipo !== "2") { alert("SUNAT solo consulta DNI o RUC"); return; }
        fetch(`<?php echo SITE_URL; ?>buscar_sunat.php?numero=${numero}&tipo=${tipo}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById("nombre_cliente").value = data.nombre;
                    document.getElementById("direccion_cliente").value = data.direccion;
                } else {
                    alert("No se encontró información en SUNAT");
                }
            })
            .catch(() => alert("Error consultando SUNAT"));
    });

    // Scripts para Actividades y Contactos
    const idCliente = document.getElementById('id_cliente_form').value;
    const historialActividades = document.getElementById('historial-actividades');
    const formNuevaActividad = document.getElementById('form-nueva-actividad');
    const contactoActividadSelect = document.getElementById('id_contacto_actividad');
    const tablaContactosBody = document.querySelector('#tabla-contactos tbody');
    const modal = document.getElementById('modal-contacto');
    const formContacto = document.getElementById('form-contacto');
    let editando = false;

function cargarActividades() {
    fetch(`<?php echo SITE_URL; ?>index.php?controller=actividades&action=listarPorCliente&id_cliente=${idCliente}`)
        .then(response => response.json())
        .then(data => {
            const actividadesPorPagina = 5;
            let paginaActual = 1;
            const totalPaginas = Math.ceil(data.length / actividadesPorPagina);

            function mostrarPagina(pagina) {
                historialActividades.innerHTML = '';
                const inicio = (pagina - 1) * actividadesPorPagina;
                const fin = inicio + actividadesPorPagina;
                const actividadesPagina = data.slice(inicio, fin);

                if (actividadesPagina.length === 0) {
                    historialActividades.innerHTML = '<p>No hay actividades registradas.</p>';
                    return;
                }

                actividadesPagina.forEach(actividad => {
                    const actividadDiv = document.createElement('div');
                    actividadDiv.style.border = '1px solid #ddd';
                    actividadDiv.style.padding = '10px';
                    actividadDiv.style.marginBottom = '10px';
                    actividadDiv.innerHTML = `
                        <strong>Tipo: </strong> ${actividad.tipo_actividad}<br>
                        <strong>Asunto: </strong>${actividad.asunto} <br>
                        <strong>Descripción: </strong>${actividad.descripcion}<br>
                        <strong>Fecha: </strong>${new Date(actividad.fecha_actividad).toLocaleString()} 
                        <strong>|</strong>  <strong>Registrado por: </strong>  ${actividad.nombre_usuario}
                    `;
                    historialActividades.appendChild(actividadDiv);
                });

                // Generar botones de paginación
                const paginacionDiv = document.getElementById('paginacion-actividades');
                paginacionDiv.innerHTML = '';
                for (let i = 1; i <= totalPaginas; i++) {
                    const btn = document.createElement('button');
                    btn.textContent = i;
                    btn.style.padding = '5px 10px';
                    btn.style.cursor = 'pointer';
                    btn.style.border = '1px solid #ccc';
                    btn.style.background = (i === pagina) ? '#790000ff' : '#fff';
                    btn.style.color = (i === pagina) ? '#fff' : '#000';
                    btn.addEventListener('click', () => mostrarPagina(i));
                    paginacionDiv.appendChild(btn);
                }
            }

            mostrarPagina(paginaActual);
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
            Swal.fire({
            icon: "error",
            title: "Error",
            text: data.message || "Ocurrió un error al crear la actividad.",
            confirmButtonText: "Aceptar",
            confirmButtonColor: "#d33"
        });
            }
        });
    });

    function cargarContactos() {
        fetch(`<?php echo SITE_URL; ?>index.php?controller=contactos&action=listarPorCliente&id_cliente=${idCliente}`)
            .then(response => response.json())
            .then(data => {
                tablaContactosBody.innerHTML = '';
                contactoActividadSelect.innerHTML = '<option value="">-- Sin Contacto --</option>';
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
                    const option = document.createElement('option');
                    option.value = contacto.id_contacto;
                    option.textContent = contacto.nombre_contacto;
                    contactoActividadSelect.appendChild(option);
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

    document.getElementById('btn-nuevo-contacto').addEventListener('click', () => abrirModal('Nuevo Contacto'));
    document.getElementById('btn-cancelar-modal').addEventListener('click', () => modal.style.display = 'none');

    tablaContactosBody.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-editar-contacto')) {
            const idContacto = e.target.dataset.id;
            fetch(`<?php echo SITE_URL; ?>index.php?controller=contactos&action=obtener&id_contacto=${idContacto}`)
                .then(response => response.json())
                .then(data => abrirModal('Editar Contacto', data));
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
        fetch(url, { method: 'POST', body: formData })
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

    // Carga inicial de datos
    cargarActividades();
    cargarContactos();
});
function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
        tabcontent[i].classList.remove("active");
    }
    tablinks = document.getElementsByClassName("tab-link");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].classList.remove("active");
    }

    document.getElementById(tabName).style.display = "block";
    document.getElementById(tabName).classList.add("active");
    evt.currentTarget.classList.add("active");


}

// Ensure the first tab is displayed by default
document.addEventListener('DOMContentLoaded', function() {
    const tabGuardado = localStorage.getItem("tabActivoEditarCliente") || "cliente";

    document.querySelectorAll(".tab-content").forEach(tc => tc.style.display = "none");
    document.querySelectorAll(".tab-link").forEach(tl => tl.classList.remove("active"));

    const tabActivo = document.getElementById(tabGuardado);
    if (tabActivo) {
        tabActivo.style.display = "block";
        tabActivo.classList.add("active");
        document.querySelector(`.tab-link[onclick*="${tabGuardado}"]`).classList.add("active");
    } else {
        document.getElementById("cliente").style.display = "block";
        document.querySelector('.tab-link').classList.add("active");
    }

    // ✅ Limpia el localStorage después de usarlo
    localStorage.removeItem("tabActivoEditarCliente");
});
</script>

<?php require_once 'views/layout/footer.php'; ?>
