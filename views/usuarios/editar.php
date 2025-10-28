<?php require_once 'views/layout/header.php'; ?>

<h1>Editar Usuario</h1>

<?php if (isset($data['error'])) : ?>
    <p class="error-message"><?php echo $data['error']; ?></p>
<?php endif; ?>

<form action="<?php echo SITE_URL; ?>index.php?controller=usuarios&action=editar" method="POST">
    <input type="hidden" name="id_usuario" value="<?php echo $data['usuario']['id_usuario']; ?>">
    <div class="form-group">
        <label for="nombre_usuario">Nombre</label>
        <input type="text" name="nombre_usuario" id="nombre_usuario" value="<?php echo $data['usuario']['nombre_usuario']; ?>" required>
    </div>
    <div class="form-group">
        <label for="apellido_usuario">Apellido</label>
        <input type="text" name="apellido_usuario" id="apellido_usuario" value="<?php echo $data['usuario']['apellido_usuario']; ?>" required>
    </div>
    <div class="form-group">
        <label for="correo_usuario">Correo Electrónico</label>
        <input type="email" name="correo_usuario" id="correo_usuario" value="<?php echo $data['usuario']['correo_usuario']; ?>" required>
    </div>
    <div class="form-group">
        <label for="clave_usuario">Contraseña (dejar en blanco para no cambiar)</label>
        <input type="password" name="clave_usuario" id="clave_usuario">
    </div>
    <div class="form-group">
        <label for="id_perfil">Perfil</label>
        <select name="id_perfil" id="id_perfil" required>
            <?php foreach ($data['perfiles'] as $perfil) : ?>
                <option value="<?php echo $perfil['id_perfil']; ?>" <?php echo ($perfil['id_perfil'] == $data['usuario']['id_perfil']) ? 'selected' : ''; ?>>
                    <?php echo $perfil['nombre_perfil']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="<?php echo SITE_URL; ?>index.php?controller=usuarios&action=index" class="btn btn-secondary">Cancelar</a>
</form>

<?php require_once 'views/layout/footer.php'; ?>
