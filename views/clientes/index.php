<?php require_once 'views/layout/header.php'; ?>

<h1>Clientes</h1>

<a href="<?php echo SITE_URL; ?>index.php?controller=clientes&action=crear" class="btn btn-primary">Nuevo Cliente</a>

<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Teléfono</th>
            <th>Website</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data['clientes'] as $cliente) : ?>
            <tr>
                <td><?php echo $cliente['nombre_cliente']; ?></td>
                <td><?php echo $cliente['telefono_cliente']; ?></td>
                <td><a href="<?php echo $cliente['website_cliente']; ?>" target="_blank"><?php echo $cliente['website_cliente']; ?></a></td>
                <td>
                    <a href="<?php echo SITE_URL; ?>index.php?controller=clientes&action=editar&id=<?php echo $cliente['id_cliente']; ?>" class="btn btn-secondary">Editar</a>
                    <a href="<?php echo SITE_URL; ?>index.php?controller=clientes&action=eliminar&id=<?php echo $cliente['id_cliente']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar este cliente?');">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once 'views/layout/footer.php'; ?>
