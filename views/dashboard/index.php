<?php require_once 'views/layout/header.php'; ?>

<style>

    #stats-container {
        flex: 1;
        max-width: 100%;
        border: 1px solid #ccc;
        padding: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        border-radius: 5px;
        background-color: #fff;
    }

    .fc-event {
        cursor: pointer;
    }
</style>    

<h2>Bienvenido al CRM, <?php echo $_SESSION['usuario']['nombre_usuario']; ?></h2>
<!-- <p>Aquí tienes un resumen de tu actividad:</p> --> 

<div class="dashboard-container">

    <div id="stats-container">
        <h3>Otros Reportes</h3>
        <p>Próximamente...</p>
    </div>
</div>

<script>
    
</script>




<?php require_once 'views/layout/footer.php'; ?>
