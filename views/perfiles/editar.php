<?php require_once 'views/layout/header.php'; ?>

<h1>Editar Perfil</h1>

<?php if (isset($data['error'])) : ?>
    <p class="error-message"><?php echo $data['error']; ?></p>
<?php endif; ?>

<form action="<?php echo SITE_URL; ?>index.php?controller=perfiles&action=editar" method="POST">
    <input type="hidden" name="id_perfil" value="<?php echo $data['perfil']['id_perfil']; ?>">
    <div class="form-group">
        <label for="nombre_perfil">Nombre del Perfil</label>
        <input type="text" name="nombre_perfil" id="nombre_perfil" value="<?php echo $data['perfil']['nombre_perfil']; ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="<?php echo SITE_URL; ?>index.php?controller=perfiles&action=index" class="btn btn-secondary">Cancelar</a>
</form>

<?php require_once 'views/layout/footer.php'; ?>
