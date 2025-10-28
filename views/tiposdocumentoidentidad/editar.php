<?php require_once 'views/layout/header.php'; ?>

<h1>Editar Tipo de Documento</h1>

<?php if (isset($data['error'])) : ?>
    <p class="error-message"><?php echo $data['error']; ?></p>
<?php endif; ?>

<form action="<?php echo SITE_URL; ?>index.php?controller=tiposdocumentoidentidad&action=editar" method="POST">
    <input type="hidden" name="id_tipo_documento" value="<?php echo $data['tipodocumento']['id_tipo_documento']; ?>">
    <div class="form-group">
        <label for="nombre_documento">Nombre del Tipo de Documento</label>
        <input type="text" name="nombre_documento" id="nombre_documento" value="<?php echo $data['tipodocumento']['nombre_documento']; ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="<?php echo SITE_URL; ?>index.php?controller=tiposdocumentoidentidad&action=index" class="btn btn-secondary">Cancelar</a>
</form>

<?php require_once 'views/layout/footer.php'; ?>
