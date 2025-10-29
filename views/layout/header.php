<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($data['titulo']) ? $data['titulo'] . ' - CRM Beta' : 'CRM Beta'; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/style.css">
</head>
<body>
    <div class="page-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>CRM Beta</h2>
            </div>
            <nav class="menu">
                <ul>
                    <li><a href="<?php echo SITE_URL; ?>index.php?controller=dashboard&action=index"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li class="menu-item has-submenu">
                        <a href="#"><i class="fas fa-briefcase"></i> Comercial</a>
                        <ul class="submenu">
                            <li><a href="<?php echo SITE_URL; ?>index.php?controller=clientes&action=index">Clientes</a></li>
                            <li><a href="<?php echo SITE_URL; ?>index.php?controller=oportunidades&action=index">Oportunidades</a></li>
                        </ul>
                    </li>
                    <?php if ($_SESSION['usuario']['id_perfil'] == 1) : ?>
                    <li class="menu-item has-submenu">
                        <a href="#"><i class="fas fa-cog"></i> Configuración</a>
                        <ul class="submenu">
                            <li><a href="<?php echo SITE_URL; ?>index.php?controller=tiposdocumentoidentidad&action=index">Tipos de Documento</a></li>
                            <li><a href="<?php echo SITE_URL; ?>index.php?controller=ubigeos&action=index">Ubigeos</a></li>
                            <li><a href="<?php echo SITE_URL; ?>index.php?controller=usuarios&action=index">Usuarios</a></li>
                            <li><a href="<?php echo SITE_URL; ?>index.php?controller=perfiles&action=index">Perfiles</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </aside>
        <div class="main-content">
            <header class="topbar">
                <div class="welcome-message">
                    <h1>Bienvenido al CRM</h1>
                </div>
                <div class="user-menu">
                    <div class="user-info">
                        <span class="user-avatar">ML</span>
                        <span class="user-name"><?php echo $_SESSION['usuario']['nombre_usuario']; ?></span>
                    </div>
                    <div class="dropdown-content">
                        <a href="<?php echo SITE_URL; ?>index.php?controller=login&action=logout">Cerrar Sesión</a>
                    </div>
                </div>
            </header>
            <main class="content">
