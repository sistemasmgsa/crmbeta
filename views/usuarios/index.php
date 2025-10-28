<?php require_once 'views/layout/header.php'; ?>

<h1>Mantenimiento de Usuarios</h1>

<a href="<?php echo SITE_URL; ?>index.php?controller=usuarios&action=crear" class="btn btn-primary">Nuevo Usuario</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Correo</th>
            <th>Perfil</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data['usuarios'] as $usuario) : ?>
            <tr>
                <td><?php echo $usuario['id_usuario']; ?></td>
                <td><?php echo $usuario['nombre_usuario']; ?></td>
                <td><?php echo $usuario['apellido_usuario']; ?></td>
                <td><?php echo $usuario['correo_usuario']; ?></td>
                <td><?php echo $usuario['nombre_perfil']; ?></td>
                <td>
                    <a href="<?php echo SITE_URL; ?>index.php?controller=usuarios&action=editar&id=<?php echo $usuario['id_usuario']; ?>" class="btn btn-secondary">Editar</a>
                    <a href="<?php echo SITE_URL; ?>index.php?controller=usuarios&action=eliminar&id=<?php echo $usuario['id_usuario']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar este usuario?');">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once 'views/layout/footer.php'; ?>
