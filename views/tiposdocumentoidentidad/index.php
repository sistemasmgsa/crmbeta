<?php require_once 'views/layout/header.php'; ?>

<h1>Tipos de Documento de Identidad</h1>

<a href="<?php echo SITE_URL; ?>index.php?controller=tiposdocumentoidentidad&action=crear" class="btn btn-primary">Nuevo Tipo de Documento</a>

<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data['tiposdocumento'] as $tipodocumento) : ?>
            <tr>
                <td><?php echo $tipodocumento['nombre_documento']; ?></td>
                <td>
                    <a href="<?php echo SITE_URL; ?>index.php?controller=tiposdocumentoidentidad&action=editar&id=<?php echo $tipodocumento['id_tipo_documento']; ?>" class="btn btn-secondary">Editar</a>
                    <a href="<?php echo SITE_URL; ?>index.php?controller=tiposdocumentoidentidad&action=eliminar&id=<?php echo $tipodocumento['id_tipo_documento']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar este tipo de documento?');">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once 'views/layout/footer.php'; ?>
