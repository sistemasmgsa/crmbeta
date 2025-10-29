<?php require_once 'views/layout/header.php'; ?>

<!-- ✅ Agregar FullCalendar (CSS y JS) antes de usarlo -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<style>
    .dashboard-container {
        display: flex;
        gap: 20px;
    }
    #calendar-container {
        flex: 1;
        max-width: 65%;
    }
    #stats-container {
        flex: 1;
        max-width: 35%;
        border: 1px solid #ccc;
        padding: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        border-radius: 5px;
        background-color: #fff;
    }
    #calendar {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .fc-event {
        cursor: pointer;
    }
</style>

<h1>Bienvenido al CRM, <?php echo $_SESSION['usuario']['nombre_usuario']; ?></h1>
<p>Aquí tienes un resumen de tu actividad:</p>

<div class="dashboard-container">
    <div id="calendar-container">
        <h2>Calendario de Actividades</h2>
        <div id='calendar'></div>
    </div>
    <div id="stats-container">
        <h2>Estadísticas</h2>
        <p>Próximamente...</p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');

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
                    ]
                ];
            }
            echo json_encode($calendar_events);
        ?>;

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: events,

            eventContent: function(arg) {
                // Formatear hora en formato AM/PM
                const hora = arg.event.start
                    ? arg.event.start.toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true
                    }).toUpperCase()
                    : '';

                // Crear elementos
                const horaEl = document.createElement('div');
                horaEl.textContent = hora;
                horaEl.style.fontWeight = 'bold';
                horaEl.style.fontSize = '12px';

                const titleEl = document.createElement('div');
                titleEl.textContent = arg.event.title;
                titleEl.style.fontSize = '13px';
                titleEl.style.fontWeight = '600';

                // Contenedor principal (cuadro azul)
                const container = document.createElement('div');
                container.style.display = 'flex';
                container.style.flexDirection = 'column';
                container.style.alignItems = 'flex-start'; // alineado a la izquierda
                container.style.backgroundColor = '#007BFF'; // azul
                container.style.color = 'white'; // texto blanco
                container.style.padding = '3px 6px';
                container.style.borderRadius = '4px';
                container.style.boxShadow = '0 1px 2px rgba(0,0,0,0.2)';
                container.style.lineHeight = '1.2';
                container.style.width = '100%';
                container.style.textAlign = 'left';

                container.appendChild(horaEl);
                container.appendChild(titleEl);

                return { domNodes: [container] };
            },

            eventClick: function(info) {
                alert(
                    'Cliente: ' + info.event.extendedProps.cliente + "\n" +
                    'Asunto: ' + info.event.title + "\n" +
                    'Tipo: ' + info.event.extendedProps.type + "\n" +
                    'Descripción: ' + info.event.extendedProps.description + "\n" +
                    'Fecha: ' + info.event.start.toLocaleString()
                );
            }
        });

        calendar.render();
    });
</script>




<?php require_once 'views/layout/footer.php'; ?>
