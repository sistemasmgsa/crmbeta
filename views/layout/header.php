<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($data['titulo']) ? $data['titulo'] . ' - CRM Beta' : 'CRM Beta'; ?></title>
    
    <!-- FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/main.min.css" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- CSS del sitio -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/style.css">
</head>
<body>
    <!-- Header móvil para menú hamburguesa -->
    <header class="mobile-header">
        <div class="hamburger-circle">
            <div class="hamburger" id="hamburger"></div>
        </div>
        <h2>CRM Beta</h2>
    </header>

    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>CRM Beta</h2>
            </div>
            <nav class="menu">
                <ul>
                    <li><a href="<?php echo SITE_URL; ?>index.php?controller=dashboard&action=index"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>

                    <li class="has-submenu">
                        <a href="#"><i class="fas fa-cogs"></i> Mantenimiento</a>
                        <ul class="submenu">
                            <li><a href="<?php echo SITE_URL; ?>index.php?controller=tiposdocumentoidentidad&action=index">Tipos de Documento</a></li>
                            <li><a href="<?php echo SITE_URL; ?>index.php?controller=ubigeos&action=index">Ubigeos</a></li>
                            <!-- <li><a href="<?php echo SITE_URL; ?>index.php?controller=usuarios&action=index">Usuarios</a></li>
                            <li><a href="<?php echo SITE_URL; ?>index.php?controller=perfiles&action=index">Perfiles</a></li> -->
                        </ul>
                    </li>

                    <li class="has-submenu">
                        <a href="#"><i class="fas fa-tasks"></i> Operaciones</a>
                        <ul class="submenu">
                            <li><a href="<?php echo SITE_URL; ?>index.php?controller=clientes&action=index">Clientes</a></li>
                            <li><a href="<?php echo SITE_URL; ?>index.php?controller=oportunidades&action=index">Oportunidades</a></li>
                            <li><a href="<?php echo SITE_URL; ?>index.php?controller=calendario&action=index">Calendario</a></li>
                        </ul>
                    </li>

                    <li class="has-submenu">
                        <a href="#"><i class="fas fa-chart-bar"></i> Reportes</a>
                        <ul class="submenu">
                            <li><a href="<?php echo SITE_URL; ?>index.php?controller=reportes&action=dinamicos">Reportes Dinamicos</a></li>
                        </ul>
                    </li>

                    <?php if ($_SESSION['usuario']['id_perfil'] == 1) : ?>
                    <li class="has-submenu">
                        <a href="#"><i class="fas fa-lock"></i> Seguridad</a>
                        <ul class="submenu">
                            <li><a href="<?php echo SITE_URL; ?>index.php?controller=usuarios&action=index">Usuarios</a></li>
                            <li><a href="<?php echo SITE_URL; ?>index.php?controller=perfiles&action=index">Perfiles</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <li><a href="<?php echo SITE_URL; ?>index.php?controller=login&action=logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main content -->
        <main class="content">
