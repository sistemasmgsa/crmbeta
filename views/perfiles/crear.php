<?php require_once 'views/layout/header.php'; ?>

<h1>Crear Perfil</h1>

<?php if (isset($data['error'])) : ?>
    <p class="error-message"><?php echo $data['error']; ?></p>
<?php endif; ?>

<form action="<?php echo SITE_URL; ?>index.php?controller=perfiles&action=crear" method="POST">
    <div class="form-group">
        <label for="nombre_perfil">Nombre del Perfil</label>
        <input type="text" name="nombre_perfil" id="nombre_perfil" 
        style="font-size: 16px; padding: 6px; width: 500px;"
        required>
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="<?php echo SITE_URL; ?>index.php?controller=perfiles&action=index" class="btn btn-secondary">Cancelar</a>
</form>

<?php require_once 'views/layout/footer.php'; ?>
