<?php require_once 'views/layout/header.php'; ?>

<p>Aquí tienes un resumen de tu actividad:</p>

<div class="dashboard-cards">
    <div class="card">
        <h3>Total de Clientes</h3>
        <p><?php echo $data['estadisticas']['total_clientes']; ?></p>
    </div>
    <div class="card">
        <h3>Oportunidades Activas</h3>
        <p><?php echo $data['estadisticas']['oportunidades_activas']; ?></p>
    </div>
    <div class="card">
        <h3>Actividades Pendientes</h3>
        <p><?php echo $data['estadisticas']['actividades_pendientes']; ?></p>
    </div>
</div>

<?php require_once 'views/layout/footer.php'; ?>
