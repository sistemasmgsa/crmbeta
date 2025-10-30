<?php require_once 'views/layout/header.php'; ?>

<!-- ✅ FullCalendar + Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="1">
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css" rel="1">

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
    min-width: 600px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    padding: 15px;
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

/* FullCalendar estilos */
.fc-toolbar-title { font-size: 1.5rem !important; font-weight: 600 !important; color: #333; }
.fc-button { border-radius: 8px !important; }
.fc-event { border-radius: 6px !important; padding: 2px 4px; font-size: 13px; }

body { background-color: #f4f6f8; }
</style>

<h2>Calendario de Actividades</h2>

<div class="dashboard-container">
    <!-- Contenedor calendario -->
    <div id="calendar-container">
        <div id="calendar"></div>
    </div>

    <!-- Contenedor agenda lateral -->
    <div id="agenda">
        <h5>🗓️ Actividades Próximas</h5>
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
                    'id_cliente' => $actividad['id_cliente']
                ],
                'color' => '#007bff',
            ];
        }
        echo json_encode($calendar_events); 
    ?>;

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        locale: 'es',
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
                    <b>Fecha:</b> ${fechaFormateada} ${horaFormateada}`,
                icon: 'info',
                showCancelButton: true,          // botón extra
                cancelButtonText: 'Ver Detalle', // texto del botón rojo
                confirmButtonText: 'Cerrar',     // texto del botón azul
                confirmButtonColor: '#0078d4',
                cancelButtonColor: '#dc3545'
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.cancel) {
                    const idCliente = info.event.extendedProps.id_cliente; 
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
        const proximos = eventos
        .filter(e => new Date(e.start) >= new Date()) // eventos futuros
        .sort((a,b) => new Date(b.start) - new Date(a.start)) // DESC
        .slice(0,10);
        agendaList.innerHTML = '';
        if(proximos.length === 0) {
            agendaList.innerHTML = '<p>No hay actividades próximas.</p>';
            return;
        }
        proximos.forEach(e => {
            const fecha = new Date(e.start);
            const fechaTexto = fecha.toLocaleDateString('es-ES', { weekday:'long', day:'2-digit', month:'short' });
            const hora = fecha.toLocaleTimeString([], { hour:'2-digit', minute:'2-digit', hour12:true });
            const div = document.createElement('div');
            div.className = 'agenda-item';
            div.innerHTML = `<strong>${e.title}</strong><br>
                             <small>${fechaTexto} - ${hora}</small><br>
                             <em>${e.extendedProps.cliente || ''}</em>`;
            agendaList.appendChild(div);
        });
    }
});
</script>

<?php require_once 'views/layout/footer.php'; ?>
