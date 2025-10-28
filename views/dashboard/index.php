<?php require_once 'views/layout/header.php'; ?>

<style>
    .dashboard-cards {
        display: flex;
        gap: 20px;
        margin-top: 20px;
    }
    .card {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        flex: 1;
        text-align: center;
    }
    .card h3 {
        margin-top: 0;
        font-size: 18px;
        color: #666;
    }
    .card p {
        font-size: 36px;
        font-weight: bold;
        margin: 10px 0 0;
        color: #8B0000;
    }
</style>

<h1>Bienvenido al CRM, <?php echo $_SESSION['usuario']['nombre_usuario']; ?></h1>
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
