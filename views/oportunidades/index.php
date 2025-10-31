<?php require_once 'views/layout/header.php'; ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">


<style>
/* Contenedor principal Kanban con scroll limitado */
.kanban-container {
    width: 100%;
    max-height: calc(100vh - 180px); /* Ajusta según altura de header + footer */
    overflow-x: auto;  /* scroll horizontal si hay muchas columnas */
    overflow-y: auto;  /* scroll vertical si las tarjetas son muchas */
    padding-bottom: 10px;
    box-sizing: border-box;
}

/* Tablero Kanban */
.kanban-board {
    display: flex;
    gap: 15px;
    min-height: 100px; /* evita colapsar si no hay tarjetas */
}

/* Columnas */
.kanban-column {
    flex: 0 0 230px; /* ancho fijo para columnas */
    background-color: #f2f2f2;
    border-radius: 5px;
    padding: 10px;
    max-height: 100%; /* respeta scroll vertical del contenedor */
    display: flex;
    flex-direction: column;
    overflow-y: auto; /* scroll vertical interno si muchas tarjetas */
}

/* Título de columnas */
.kanban-column h3 {
    margin-top: 0;
    padding-bottom: 10px;
    border-bottom: 2px solid #ddd;
}

/* Tarjetas */
.kanban-card {
    background-color: #fff;
    border-radius: 4px;
    padding: 10px;
    margin-bottom: 10px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    cursor: grab;
    word-break: break-word; /* evita que el texto largo rompa la tarjeta */
}

.kanban-card h4 {
    margin-top: 0;
    margin-bottom: 5px;
}

.kanban-card p {
    margin: 0;
    font-size: 14px;
    color: #666;
}

/* Tarjeta en arrastre */
.dragging {
    opacity: 0.5;
}

/* Botón Crear */
.btn-primary {
    margin-bottom: 20px;
}
</style>

<h1>Oportunidades</h1>
<a href="<?php echo SITE_URL; ?>index.php?controller=oportunidades&action=crear" class="btn btn-primary">Nueva Oportunidad</a>

<form method="GET" action="<?php echo SITE_URL; ?>index.php" 
    class="form-inline mb-3" 
    style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center; background: #f9f9f9; padding: 10px; border-radius: 6px;">

    <input type="hidden" name="controller" value="oportunidades">
    <input type="hidden" name="action" value="index">

    <div class="form-group" style="display: flex; align-items: center; gap: 5px;">
        <label for="anio" style="margin: 0;">Año:</label>
        <select name="anio" id="anio" class="form-control" style="width: 80px;">
            <?php for ($i = date('Y'); $i >= 2025; $i--) : ?>
                <option value="<?php echo $i; ?>" <?php echo ($data['anio'] == $i) ? 'selected' : ''; ?>>
                    <?php echo $i; ?>
                </option>
            <?php endfor; ?>
        </select>
    </div>

<div class="form-group" style="display: flex; align-items: center; gap: 5px;">
    <label for="mes" style="margin: 0;">Mes:</label>
    <select name="mes" id="mes" class="form-control" style="width: 180px;">
        <?php 
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];
        foreach ($meses as $num => $nombre) : ?>
            <option value="<?php echo $num; ?>" <?php echo ($data['mes'] == $num) ? 'selected' : ''; ?>>
                <?php echo $nombre; ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>


    <div class="form-group" style="display: flex; align-items: center; gap: 5px;">
        <label for="etapa" style="margin: 0;">Etapa:</label>
        <select name="etapa" id="etapa" class="form-control" style="width: 180px;">
            <option value="Todos" <?php echo ($data['etapa'] == 'Todos') ? 'selected' : ''; ?>>Todos</option>
            <option value="Calificación" <?php echo ($data['etapa'] == 'Calificación') ? 'selected' : ''; ?>>Calificación</option>
            <option value="Propuesta" <?php echo ($data['etapa'] == 'Propuesta') ? 'selected' : ''; ?>>Propuesta</option>
            <option value="Negociación" <?php echo ($data['etapa'] == 'Negociación') ? 'selected' : ''; ?>>Negociación</option>
            <option value="Ganada" <?php echo ($data['etapa'] == 'Ganada') ? 'selected' : ''; ?>>Ganada</option>
            <option value="Perdida" <?php echo ($data['etapa'] == 'Perdida') ? 'selected' : ''; ?>>Perdida</option>
        </select>
    </div>

    <?php if (isset($_SESSION['usuario']['id_perfil']) && $_SESSION['usuario']['id_perfil'] == 1) : ?>
        <div class="form-group" style="display: flex; align-items: center; gap: 5px;">
            <label for="id_usuario" style="margin: 0;">Usuario:</label>
            <select name="id_usuario" id="id_usuario" class="form-control" style="width: 200px;">
                <option value="0" <?php echo ($data['id_usuario_seleccionado'] == 0) ? 'selected' : ''; ?>>Todos</option>
                <?php foreach ($data['usuarios'] as $usuario) : ?>
                    <option value="<?php echo $usuario['id_usuario']; ?>" 
                        <?php echo ($data['id_usuario_seleccionado'] == $usuario['id_usuario']) ? 'selected' : ''; ?>>
                        <?php echo $usuario['nombre_usuario']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php endif; ?>

<div class="form-group" style="display: flex; align-items: center; gap: 5px;">
    <label style="margin: 0; visibility: hidden;">_</label>
    <button type="submit" class="btn btn-secondary" style="padding: 6px 16px; width: 100px; display: flex; align-items: center; justify-content: center; gap: 5px;">
        <i class="bi bi-search"></i> Filtrar
    </button>
</div>

</form>



<div class="kanban-container">
    <div class="kanban-board">
        <?php foreach ($data['oportunidades_por_etapa'] as $etapa => $oportunidades) : ?>
            <div class="kanban-column" id="etapa-<?php echo str_replace(' ', '-', $etapa); ?>" data-etapa="<?php echo $etapa; ?>">
                <h3><?php echo $etapa; ?></h3>
                <?php foreach ($oportunidades as $op) : ?>
                    <div class="kanban-card" draggable="true" id="op-<?php echo $op['id_oportunidad']; ?>" data-id="<?php echo $op['id_oportunidad']; ?>">
                        <h4><?php echo $op['nombre_oportunidad']; ?></h4>
                        <p><?php echo $op['nombre_cliente']; ?></p>
                        <p>---</p>
                        <p style="font-size: 14px; color: #888;"><?php echo $op['nombre_usuario_creacion']; ?> - <?php echo date('d/m/Y H:i', strtotime($op['fecha_creacion'])); ?></p>
                        <p><strong>Valor:</strong> S/. <?php echo number_format($op['valor_estimado'], 2); ?></p>
                        <a href="<?php echo SITE_URL; ?>index.php?controller=oportunidades&action=editar&id=<?php echo $op['id_oportunidad']; ?>" style="font-size: 12px; margin-top: 5px; display: inline-block;">Editar</a>
                        <a href="<?php echo SITE_URL; ?>index.php?controller=oportunidades&action=eliminar&id=<?php echo $op['id_oportunidad']; ?>" onclick="return confirm('¿Está seguro de eliminar esta oportunidad?');" style="font-size: 12px; margin-top: 5px; display: inline-block; color: red; margin-left: 10px;">Eliminar</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.kanban-card');
    const columns = document.querySelectorAll('.kanban-column');

    cards.forEach(card => {
        card.addEventListener('dragstart', () => card.classList.add('dragging'));
        card.addEventListener('dragend', () => card.classList.remove('dragging'));
    });

    columns.forEach(column => {
        column.addEventListener('dragover', e => {
            e.preventDefault();
            const afterElement = getDragAfterElement(column, e.clientY);
            const draggable = document.querySelector('.dragging');
            if (!afterElement) column.appendChild(draggable);
            else column.insertBefore(draggable, afterElement);
        });

        column.addEventListener('drop', e => {
            e.preventDefault();
            const draggable = document.querySelector('.dragging');
            const idOportunidad = draggable.dataset.id;
            const nuevaEtapa = column.dataset.etapa;

            const formData = new FormData();
            formData.append('id_oportunidad', idOportunidad);
            formData.append('etapa', nuevaEtapa);

            fetch('<?php echo SITE_URL; ?>index.php?controller=oportunidades&action=actualizarEtapa', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert('Error al actualizar la etapa. La página se recargará.');
                    location.reload();
                }
            })
            .catch(() => {
                alert('Error de red. La página se recargará.');
                location.reload();
            });
        });
    });

    function getDragAfterElement(column, y) {
        const draggableElements = [...column.querySelectorAll('.kanban-card:not(.dragging)')];
        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height/2;
            if (offset < 0 && offset > closest.offset) return {offset, element: child};
            else return closest;
        }, {offset: Number.NEGATIVE_INFINITY}).element;
    }
});
</script>

<?php require_once 'views/layout/footer.php'; ?>
