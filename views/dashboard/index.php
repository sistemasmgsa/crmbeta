<?php require_once 'views/layout/header.php'; ?>

<h1>Bienvenido al CRM</h1>
<p>Hola, <?php echo $_SESSION['usuario']['nombre_usuario']; ?>.</p>

<?php require_once 'views/layout/footer.php'; ?>
