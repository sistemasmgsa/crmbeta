<?php require_once 'views/layout/header.php'; ?>

<h1>Editar Ubigeo</h1>

<?php if (isset($data['error'])) : ?>
    <p class="error-message"><?php echo $data['error']; ?></p>
<?php endif; ?>

<form action="<?php echo SITE_URL; ?>index.php?controller=ubigeos&action=editar" method="POST">
    <input type="hidden" name="id_ubigeo" value="<?php echo $data['ubigeo']['id_ubigeo']; ?>">
    <div class="form-group">
        <label for="departamento">Departamento</label>
        <input type="text" name="departamento" id="departamento" value="<?php echo $data['ubigeo']['departamento']; ?>" required>
    </div>
    <div class="form-group">
        <label for="provincia">Provincia</label>
        <input type="text" name="provincia" id="provincia" value="<?php echo $data['ubigeo']['provincia']; ?>" required>
    </div>
    <div class="form-group">
        <label for="distrito">Distrito</label>
        <input type="text" name="distrito" id="distrito" value="<?php echo $data['ubigeo']['distrito']; ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="<?php echo SITE_URL; ?>index.php?controller=ubigeos&action=index" class="btn btn-secondary">Cancelar</a>
</form>

<?php require_once 'views/layout/footer.php'; ?>
