<?php require_once 'views/layout/header.php'; ?>

<style>
    .kanban-board {
        display: flex;
        gap: 15px;
        overflow-x: auto;
        padding-bottom: 15px;
    }
    .kanban-column {
        flex: 1;
        min-width: 280px;
        background-color: #f2f2f2;
        border-radius: 5px;
        padding: 10px;
    }
    .kanban-column h3 {
        margin-top: 0;
        padding-bottom: 10px;
        border-bottom: 2px solid #ddd;
    }
    .kanban-card {
        background-color: #fff;
        border-radius: 4px;
        padding: 15px;
        margin-bottom: 10px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        cursor: grab;
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
    .dragging {
        opacity: 0.5;
    }
</style>

<h1>Oportunidades (Kanban)</h1>
<a href="<?php echo SITE_URL; ?>index.php?controller=oportunidades&action=crear" class="btn btn-primary" style="margin-bottom: 20px;">Nueva Oportunidad</a>

<div class="kanban-board">
    <?php foreach ($data['oportunidades_por_etapa'] as $etapa => $oportunidades) : ?>
        <div class="kanban-column" id="etapa-<?php echo str_replace(' ', '-', $etapa); ?>" data-etapa="<?php echo $etapa; ?>">
            <h3><?php echo $etapa; ?></h3>
            <?php foreach ($oportunidades as $op) : ?>
                <div class="kanban-card" draggable="true" id="op-<?php echo $op['id_oportunidad']; ?>" data-id="<?php echo $op['id_oportunidad']; ?>">
                    <h4><?php echo $op['nombre_oportunidad']; ?></h4>
                    <p><?php echo $op['nombre_cliente']; ?></p>
                    <p><strong>Valor:</strong> S/. <?php echo number_format($op['valor_estimado'], 2); ?></p>
                    <a href="<?php echo SITE_URL; ?>index.php?controller=oportunidades&action=editar&id=<?php echo $op['id_oportunidad']; ?>" style="font-size: 12px; margin-top: 5px; display: inline-block;">Editar</a>
                    <a href="<?php echo SITE_URL; ?>index.php?controller=oportunidades&action=eliminar&id=<?php echo $op['id_oportunidad']; ?>" onclick="return confirm('¿Está seguro de eliminar esta oportunidad?');" style="font-size: 12px; margin-top: 5px; display: inline-block; color: red; margin-left: 10px;">Eliminar</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.kanban-card');
    const columns = document.querySelectorAll('.kanban-column');

    cards.forEach(card => {
        card.addEventListener('dragstart', () => {
            card.classList.add('dragging');
        });

        card.addEventListener('dragend', () => {
            card.classList.remove('dragging');
        });
    });

    columns.forEach(column => {
        column.addEventListener('dragover', e => {
            e.preventDefault();
            const afterElement = getDragAfterElement(column, e.clientY);
            const draggable = document.querySelector('.dragging');
            if (afterElement == null) {
                column.appendChild(draggable);
            } else {
                column.insertBefore(draggable, afterElement);
            }
        });

        column.addEventListener('drop', e => {
            e.preventDefault();
            const draggable = document.querySelector('.dragging');
            const idOportunidad = draggable.dataset.id;
            const nuevaEtapa = column.dataset.etapa;

            // Actualizar la etapa en el backend
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
            const offset = y - box.top - box.height / 2;
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }
});
</script>

<?php require_once 'views/layout/footer.php'; ?>
