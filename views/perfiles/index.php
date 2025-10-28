<?php require_once 'views/layout/header.php'; ?>

<h1>Mantenimiento de Perfiles</h1>

<a href="<?php echo SITE_URL; ?>index.php?controller=perfiles&action=crear" class="btn btn-primary">Nuevo Perfil</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data['perfiles'] as $perfil) : ?>
            <tr>
                <td><?php echo $perfil['id_perfil']; ?></td>
                <td><?php echo $perfil['nombre_perfil']; ?></td>
                <td>
                    <a href="<?php echo SITE_URL; ?>index.php?controller=perfiles&action=editar&id=<?php echo $perfil['id_perfil']; ?>" class="btn btn-secondary">Editar</a>
                    <a href="<?php echo SITE_URL; ?>index.php?controller=perfiles&action=eliminar&id=<?php echo $perfil['id_perfil']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar este perfil?');">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once 'views/layout/footer.php'; ?>
