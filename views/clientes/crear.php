<?php require_once 'views/layout/header.php'; ?>

<h1>Crear Cliente</h1>

<?php if (isset($data['error'])) : ?>
    <p class="error-message"><?php echo $data['error']; ?></p>
<?php endif; ?>

<form action="<?php echo SITE_URL; ?>index.php?controller=clientes&action=crear" method="POST">
    <div class="form-group">
        <label for="nombre_cliente">Nombre del Cliente</label>
        <input type="text" name="nombre_cliente" id="nombre_cliente" required>
    </div>
    <div class="form-group">
        <label for="direccion_cliente">Dirección</label>
        <input type="text" name="direccion_cliente" id="direccion_cliente">
    </div>
    <div class="form-group">
        <label for="telefono_cliente">Teléfono</label>
        <input type="text" name="telefono_cliente" id="telefono_cliente">
    </div>
    <div class="form-group">
        <label for="website_cliente">Sitio Web</label>
        <input type="url" name="website_cliente" id="website_cliente">
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="<?php echo SITE_URL; ?>index.php?controller=clientes&action=index" class="btn btn-secondary">Cancelar</a>
</form>

<?php require_once 'views/layout/footer.php'; ?>
