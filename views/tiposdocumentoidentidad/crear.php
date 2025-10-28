<?php require_once 'views/layout/header.php'; ?>

<h1>Crear Tipo de Documento</h1>

<?php if (isset($data['error'])) : ?>
    <p class="error-message"><?php echo $data['error']; ?></p>
<?php endif; ?>

<form action="<?php echo SITE_URL; ?>index.php?controller=tiposdocumentoidentidad&action=crear" method="POST">
    <div class="form-group">
        <label for="nombre_documento">Nombre del Tipo de Documento</label>
        <input type="text" name="nombre_documento" id="nombre_documento" required>
    </div>
    <button type="submit" class="btn btn-primary">Crear</button>
    <a href="<?php echo SITE_URL; ?>index.php?controller=tiposdocumentoidentidad&action=index" class="btn btn-secondary">Cancelar</a>
</form>

<?php require_once 'views/layout/footer.php'; ?>
