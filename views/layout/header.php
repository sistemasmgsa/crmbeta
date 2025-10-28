<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($data['titulo']) ? $data['titulo'] . ' - CRM Beta' : 'CRM Beta'; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/style.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>CRM Beta</h2>
            </div>
            <nav class="menu">
                <ul>
                    <li><a href="<?php echo SITE_URL; ?>index.php?controller=dashboard&action=index">Dashboard</a></li>
                    <?php if ($_SESSION['usuario']['id_perfil'] == 1) : ?>
                        <li><a href="<?php echo SITE_URL; ?>index.php?controller=usuarios&action=index">Usuarios</a></li>
                        <li><a href="<?php echo SITE_URL; ?>index.php?controller=perfiles&action=index">Perfiles</a></li>
                    <?php endif; ?>
                    <li><a href="<?php echo SITE_URL; ?>index.php?controller=login&action=logout">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </aside>
        <main class="content">
