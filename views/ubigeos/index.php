<?php require_once 'views/layout/header.php'; ?>

<h1>Ubigeos</h1>

<a href="<?php echo SITE_URL; ?>index.php?controller=ubigeos&action=crear" class="btn btn-primary">Nuevo Ubigeo</a>

<table>
    <thead>
        <tr>
            <th>Departamento</th>
            <th>Provincia</th>
            <th>Distrito</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data['ubigeos'] as $ubigeo) : ?>
            <tr>
                <td><?php echo $ubigeo['departamento']; ?></td>
                <td><?php echo $ubigeo['provincia']; ?></td>
                <td><?php echo $ubigeo['distrito']; ?></td>
                <td>
                    <a href="<?php echo SITE_URL; ?>index.php?controller=ubigeos&action=editar&id=<?php echo $ubigeo['id_ubigeo']; ?>" class="btn btn-secondary">Editar</a>
                    <a href="<?php echo SITE_URL; ?>index.php?controller=ubigeos&action=eliminar&id=<?php echo $ubigeo['id_ubigeo']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar este ubigeo?');">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once 'views/layout/footer.php'; ?>
