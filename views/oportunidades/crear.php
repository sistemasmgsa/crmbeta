<?php require_once 'views/layout/header.php'; ?>

<h1>Crear Oportunidad</h1>

<?php if (isset($data['error'])) : ?>
    <p class="error-message"><?php echo $data['error']; ?></p>
<?php endif; ?>

<form action="<?php echo SITE_URL; ?>index.php?controller=oportunidades&action=crear" method="POST">
    <div class="form-group">
        <label for="nombre_oportunidad">Nombre de la Oportunidad</label>
        <input type="text" name="nombre_oportunidad" id="nombre_oportunidad" 
        style="font-size: 16px; padding: 6px; width: 950px;"
        
        required>
    </div>
    <div class="form-group">
        <label for="id_cliente">Cliente</label>
        <select name="id_cliente" id="id_cliente" 
        style="font-size: 16px; padding: 6px; width: 1000px;"
        
        required>
            <option value="">Seleccione un cliente</option>
            <?php foreach ($data['clientes'] as $cliente) : ?>
                <option value="<?php echo $cliente['id_cliente']; ?>"><?php echo $cliente['nombre_cliente']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label for="valor_estimado">Valor Estimado (S/.)</label>
        <input type="number" step="0.01" name="valor_estimado" id="valor_estimado"
        style="font-size: 16px; padding: 6px; width: 200px;"
        >
    </div>


    <div class="form-group">
        <label for="fecha_cierre">Fecha de Cierre Prevista</label>
        <input type="date" name="fecha_cierre" id="fecha_cierre"
        style="font-size: 16px; padding: 6px; width: 300px;"
        >
    </div>
    <div class="form-group">
        <label for="etapa">Etapa</label>
        <select name="etapa" id="etapa" 
        style="font-size: 16px; padding: 6px; width: 250px;"
        required>
            <option value="Calificación">Calificación</option>
            <option value="Propuesta">Propuesta</option>
            <option value="Negociación">Negociación</option>
            <option value="Ganada">Ganada</option>
            <option value="Perdida">Perdida</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="<?php echo SITE_URL; ?>index.php?controller=oportunidades&action=index" class="btn btn-secondary">Cancelar</a>
</form>

<?php require_once 'views/layout/footer.php'; ?>
