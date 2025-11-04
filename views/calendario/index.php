<?php require_once 'views/layout/header.php'; ?>

<style>
/* Contenedor general estilo dashboard */
.dashboard-container {
    display: flex;
    gap: 20px;
    padding: 20px;
}

/* Contenedor calendario */
#calendar-container {
    flex: 3;
    min-width: 400px; /* más pequeño */
    max-width: 700px; /* opcional */
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    padding: 10px; /* menos padding */
    height: auto;
}

/* Contenedor agenda */
#agenda {
    flex: 1;
    min-width: 250px;
    background: white;
    border-radius: 10px;
    padding: 15px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    overflow-y: auto;
    max-height: 90vh;
}

/* Items de agenda */
.agenda-item {
    border-left: 5px solid #007bff;
    padding: 10px;
    margin-bottom: 10px;
    background: #f9fafc;
    border-radius: 5px;
    transition: 0.3s;
}
.agenda-item:hover {
    background-color: #eef4ff;
}

.filtros-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 15px;
    background: #f8f9fa;
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 25px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}

.filtro-item label {
    font-weight: 600;
    color: #333;
    font-size: 14px;
    margin-bottom: 5px;
    display: block;
}

.filtro-item select {
    border-radius: 6px;
    border: 1px solid #bbb;
    padding: 10px 12px;
    font-size: 15px;
    width: 100%;
    background-color: #fff;
    box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.05);
    transition: all 0.2s ease;
}

.filtro-item select:focus {
    border-color: #007bff;
    box-shadow: 0 0 3px rgba(0, 123, 255, 0.4);
    outline: none;
}




/* FullCalendar estilos */
.fc-toolbar-title { font-size: 1.5rem !important; font-weight: 600 !important; color: #333; }
.fc-button { border-radius: 8px !important; }
.fc-event { border-radius: 6px !important; padding: 2px 4px; font-size: 13px; }

body { background-color: #f4f6f8; }


.agenda-busqueda {
    display: flex;
    gap: 8px;
    margin-bottom: 15px;
}

.agenda-busqueda input {
    flex: 1;
    border-radius: 6px;
    border: 1px solid #ccc;
    padding: 8px 10px;
    font-size: 14px;
}

.agenda-busqueda button {
    padding: 8px 14px;
    border-radius: 6px;
    border: none;
    background-color: #007bff;
    color: white;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.agenda-busqueda button:hover {
    background-color: #0056b3;
}


.agenda-busqueda .btn-secondary {
    background-color: #6c757d;
}

.agenda-busqueda .btn-secondary:hover {
    background-color: #5a6268;
}

</style>

<h2>Calendario de Actividades</h2>


<?php if ($_SESSION['usuario']['id_perfil'] == 1): ?>
<div class="filtros-container mb-4">
    <div class="filtro-item">
        <label for="filtroUsuario">Filtrar por Usuario:</label>
        <select id="filtroUsuario" class="form-control">
            <option value="">Todos</option>
            <?php foreach ($data['usuarios'] as $usuario): ?>
                <option value="<?php echo $usuario['id_usuario']; ?>" 
                    <?php echo (isset($data['id_usuario_seleccionado']) && $data['id_usuario_seleccionado'] == $usuario['id_usuario']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($usuario['nombre_usuario']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<?php else: ?>
<input type="hidden" id="filtroUsuario" value="<?php echo $_SESSION['usuario']['id_usuario']; ?>">
<?php endif; ?>




<div class="dashboard-container">
    <!-- Contenedor calendario -->
    <div id="calendar-container">
        <div id="calendar"></div>
    </div>

    <!-- Contenedor agenda lateral -->
    <div id="agenda">
       <h3 style="text-align:center;">🗓️ Actividades Próximas</h3>

        <div class="agenda-busqueda">
            <input type="text" id="buscarAgenda" placeholder="Buscar..." class="form-control">
            <button id="btnFiltrarAgenda" class="btn btn-primary">Filtrar</button>
            <button id="btnLimpiarAgenda" class="btn btn-secondary">Limpiar</button>
        </div>

        <div id="agenda-list"></div>
    </div>


</div>

<!-- ✅ JS FullCalendar + SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>








document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const agendaList = document.getElementById('agenda-list');

    const events = <?php 
        $calendar_events = [];
        foreach ($data['actividades'] as $actividad) {
            $calendar_events[] = [
                'title' => strtoupper($actividad['asunto']),
                'start' => date('Y-m-d\TH:i:s', strtotime($actividad['fecha_actividad'])),
                'extendedProps' => [
                    'description' => $actividad['descripcion'],
                    'type' => $actividad['tipo_actividad'],
                    'cliente' => $actividad['nombre_cliente'],
                    'id_cliente' => $actividad['id_cliente'],
                    'nombre_usuario' => $actividad['nombre_usuario'],
                ],
                'color' => '#007bff',
            ];
        }
        echo json_encode($calendar_events); 
    ?>;


        // referencias (asegúrate de tenerlas ya definidas)
        const inputBuscar = document.getElementById('buscarAgenda');
        const btnFiltrar = document.getElementById('btnFiltrarAgenda');
        const btnLimpiar = document.getElementById('btnLimpiarAgenda');

        // función de normalización (quita espacios extras y pasa a minúsculas)
        function norm(text) {
            return (text || '').toString().toLowerCase().trim();
        }

        function filtrarAgenda() {
            const texto = norm(inputBuscar.value);
            if (!texto) {
                // si está vacío, mostramos todo
                actualizarAgenda(events);
                return;
            }

            const filtrados = events.filter(e => {
                // campos disponibles
                const title = norm(e.title);
                const type = norm(e.extendedProps && e.extendedProps.type);
                const cliente = norm(e.extendedProps && e.extendedProps.cliente);
                const usuario = norm(e.extendedProps && e.extendedProps.nombre_usuario);
                const desc = norm(e.extendedProps && e.extendedProps.description);

                // fechas: versión larga con weekday y versión corta numérica
                const fechaObj = new Date(e.start);
                const fechaConDia = norm(fechaObj.toLocaleDateString('es-ES', {
                    weekday: 'long', day: '2-digit', month: 'long', year: 'numeric'
                })); // ej: "martes, 04 de noviembre de 2025"
                const fechaCorta = norm(fechaObj.toLocaleDateString('es-ES')); // ej: "04/11/2025"
                const hora = norm(fechaObj.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' })); // ej: "10:00"

                // Buscamos coincidencia parcial en cualquiera de los campos
                return (
                    title.includes(texto) ||
                    type.includes(texto) ||
                    cliente.includes(texto) ||
                    usuario.includes(texto) ||
                    desc.includes(texto) ||
                    fechaConDia.includes(texto) ||
                    fechaCorta.includes(texto) ||
                    hora.includes(texto)
                );
            });

            actualizarAgenda(filtrados);
        }

        // listeners
        btnFiltrar.addEventListener('click', filtrarAgenda);

        inputBuscar.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') filtrarAgenda();
        });

        // Limpiar (restaurar)
        btnLimpiar.addEventListener('click', () => {
            inputBuscar.value = '';
            actualizarAgenda(events);
        });




    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        locale: 'es',
        buttonText: {
            today: 'Hoy',
            dayGridMonth: 'Mes',
            timeGridWeek: 'Semana',
            timeGridDay: 'Día',
            listWeek: 'Lista'
        },
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        events: events,
        eventDidMount: function(info) {
            info.el.title = `${info.event.title}\n${info.event.extendedProps.cliente}\n${info.event.extendedProps.description}`;
        },
        eventClick: function(info) {
            const fecha = info.event.start;
            const fechaFormateada = fecha.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
            const horaFormateada = fecha.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit', hour12: true }).toUpperCase();

            Swal.fire({
                title: info.event.title,
                html: `<b>Cliente:</b> ${info.event.extendedProps.cliente}<br>
                    <b>Tipo:</b> ${info.event.extendedProps.type}<br>
                    <b>Descripción:</b> ${info.event.extendedProps.description}<br>
                    
                    <b>Fecha:</b> ${fechaFormateada} ${horaFormateada}<br>
                    <b>Registrado por:</b> ${info.event.extendedProps.nombre_usuario}`,
                icon: 'info',
                showCancelButton: true,
                cancelButtonText: 'Ver Detalle',
                confirmButtonText: 'Cerrar',
                confirmButtonColor: '#0078d4',
                cancelButtonColor: '#dc3545'
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.cancel) {
                    const idCliente = info.event.extendedProps.id_cliente; 
                    localStorage.setItem("tabActivoEditarCliente", "registro");
                    window.location.href = '<?php echo SITE_URL; ?>index.php?controller=clientes&action=editar&id=' + idCliente;
                }
            });
        },
        datesSet: function() {
            actualizarAgenda(events);
        }
    });

    calendar.render();

function actualizarAgenda(eventos) {
    const todosEventos = eventos.sort((a, b) => new Date(b.start) - new Date(a.start));
    agendaList.innerHTML = '';

    if (todosEventos.length === 0) {
        agendaList.innerHTML = '<p>No hay actividades registradas.</p>';
        return;
    }

    todosEventos.forEach((e, index) => {
        const fecha = new Date(e.start);
        const fechaTexto = fecha.toLocaleDateString('es-ES', { weekday:'long', day:'2-digit', month:'long', year:'numeric' });
        const hora = fecha.toLocaleTimeString([], { hour:'2-digit', minute:'2-digit', hour12:true });

        const div = document.createElement('div');
        div.className = 'agenda-item';
        div.setAttribute('data-index', index); // identificador
        div.innerHTML = `
            <strong>${e.extendedProps.type}: </strong>${e.title} <br>
            <strong>Fecha: </strong>${fechaTexto}<br>
            <strong>Hora: </strong>${hora}<br>
            <strong>Cliente:</strong> ${e.extendedProps.cliente || ''} <br>
            <strong>Creado por: </strong>${e.extendedProps.nombre_usuario || ''}<br>
            <p></p>
            <strong>Comentario: </strong>${e.extendedProps.description || '—'}
        `;
        agendaList.appendChild(div);

        // ✅ Cuando haces clic en una actividad del listado
        div.addEventListener('click', function() {
            const fechaFormateada = fecha.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
            const horaFormateada = fecha.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit', hour12: true }).toUpperCase();

            Swal.fire({
                title: e.title,
                html: `<b>Cliente:</b> ${e.extendedProps.cliente}<br>
                    <b>Tipo:</b> ${e.extendedProps.type}<br>
                    <b>Descripción:</b> ${e.extendedProps.description}<br>
                    <b>Fecha:</b> ${fechaFormateada} ${horaFormateada}<br>
                    <b>Registrado por:</b> ${e.extendedProps.nombre_usuario}`,
                icon: 'info',
                showCancelButton: true,
                cancelButtonText: 'Ver Detalle',
                confirmButtonText: 'Cerrar',
                confirmButtonColor: '#0078d4',
                cancelButtonColor: '#dc3545'
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.cancel) {
                    const idCliente = e.extendedProps.id_cliente;
                    localStorage.setItem("tabActivoEditarCliente", "registro");
                    window.location.href = '<?php echo SITE_URL; ?>index.php?controller=clientes&action=editar&id=' + idCliente;
                }
            });
        });
    });
}



    <?php if ($_SESSION['usuario']['id_perfil'] == 1): ?>
    document.getElementById('filtroUsuario').addEventListener('change', function() {
        var idUsuario = this.value;
        window.location.href = 'index.php?controller=calendario&action=index&id_usuario=' + idUsuario;
    });
    <?php endif; ?>

});




</script>

<?php require_once 'views/layout/footer.php'; ?>
