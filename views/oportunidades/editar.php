<?php require_once 'views/layout/header.php'; ?>

<h1>Editar Oportunidad</h1>

<?php if (isset($data['error'])) : ?>
    <p class="error-message"><?php echo $data['error']; ?></p>
<?php endif; ?>

<form action="<?php echo SITE_URL; ?>index.php?controller=oportunidades&action=editar" method="POST">
    <input type="hidden" name="id_oportunidad" value="<?php echo $data['oportunidad']['id_oportunidad']; ?>">
    <div class="form-group">
        <label for="nombre_oportunidad">Nombre de la Oportunidad</label>
        <input type="text" name="nombre_oportunidad" id="nombre_oportunidad" value="<?php echo $data['oportunidad']['nombre_oportunidad']; ?>" required>
    </div>
    <div class="form-group">
        <label for="id_cliente">Cliente</label>
        <select name="id_cliente" id="id_cliente" required>
            <?php foreach ($data['clientes'] as $cliente) : ?>
                <option value="<?php echo $cliente['id_cliente']; ?>" <?php echo ($cliente['id_cliente'] == $data['oportunidad']['id_cliente']) ? 'selected' : ''; ?>>
                    <?php echo $cliente['nombre_cliente']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="valor_estimado">Valor Estimado (S/.)</label>
        <input type="number" step="0.01" name="valor_estimado" id="valor_estimado" value="<?php echo $data['oportunidad']['valor_estimado']; ?>">
    </div>
    <div class="form-group">
        <label for="fecha_cierre">Fecha de Cierre Prevista</label>
        <input type="date" name="fecha_cierre" id="fecha_cierre" value="<?php echo $data['oportunidad']['fecha_cierre']; ?>">
    </div>
    <div class="form-group">
        <label for="etapa">Etapa</label>
        <select name="etapa" id="etapa" required>
            <option value="Calificación" <?php echo ($data['oportunidad']['etapa'] == 'Calificación') ? 'selected' : ''; ?>>Calificación</option>
            <option value="Propuesta" <?php echo ($data['oportunidad']['etapa'] == 'Propuesta') ? 'selected' : ''; ?>>Propuesta</option>
            <option value="Negociación" <?php echo ($data['oportunidad']['etapa'] == 'Negociación') ? 'selected' : ''; ?>>Negociación</option>
            <option value="Ganada" <?php echo ($data['oportunidad']['etapa'] == 'Ganada') ? 'selected' : ''; ?>>Ganada</option>
            <option value="Perdida" <?php echo ($data['oportunidad']['etapa'] == 'Perdida') ? 'selected' : ''; ?>>Perdida</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="<?php echo SITE_URL; ?>index.php?controller=oportunidades&action=index" class="btn btn-secondary">Cancelar</a>
</form>

<?php require_once 'views/layout/footer.php'; ?>
