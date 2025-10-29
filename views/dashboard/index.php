<?php require_once 'views/layout/header.php'; ?>

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
                    'title' => $actividad['asunto'],
                    'start' => $actividad['fecha_actividad'],
                    'extendedProps' => [
                        'description' => $actividad['descripcion'],
                        'type' => $actividad['tipo_actividad']
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
            eventClick: function(info) {
                alert(
                    'Actividad: ' + info.event.title + "\n" +
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
