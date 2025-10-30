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
    <input 
        type="date" 
        name="fecha_cierre" 
        id="fecha_cierre"
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

<script>
const fechaInput = document.getElementById('fecha_cierre');

// Evita duplicación o escritura errónea de año
fechaInput.addEventListener('input', function(e) {
    let valor = e.target.value;

    // Si el valor tiene más de 10 caracteres (por ejemplo 20255-10-30)
    if (valor.length > 10) {
        e.target.value = valor.slice(0, 10);
    }

    // Si el usuario está escribiendo el año y pasa de 4 dígitos
    if (/^\d{5,}/.test(valor)) {
        e.target.value = valor.slice(0, 4);
    }

    // Si detecta más de un guion consecutivo o formato inválido
    if (!/^\d{0,4}(-\d{0,2}){0,2}$/.test(valor)) {
        e.target.value = valor.replace(/[^0-9\-]/g, '').slice(0, 10);
    }
});

// Evita el autocompletado visual que genera el bug en Chrome
fechaInput.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') e.preventDefault();
});
</script>
<?php require_once 'views/layout/footer.php'; ?>
