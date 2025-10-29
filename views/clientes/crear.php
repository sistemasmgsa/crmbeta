<?php require_once 'views/layout/header.php'; ?>

<h1>Crear Cliente</h1>

<?php if (isset($data['error'])) : ?>
    <p class="error-message"><?php echo $data['error']; ?></p>
<?php endif; ?>

<form action="<?php echo SITE_URL; ?>index.php?controller=clientes&action=crear" method="POST">

    <div class="form-group">
        <label for="id_tipo_documento">Tipo de Documento</label>
        <select name="id_tipo_documento" id="id_tipo_documento">
            <?php foreach ($data['tipos_documento'] as $tipo) : ?>
                <option value="<?php echo $tipo['id_tipo_documento']; ?>"><?php echo $tipo['nombre_documento']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="numero_documento">Número de Documento</label>
        <input type="text" name="numero_documento" id="numero_documento">
    </div>
        <div class="form-group">
        <label for="nombre_cliente">Nombre del Cliente</label>
        <input type="text" name="nombre_cliente" id="nombre_cliente" required>
    </div>
    <div class="form-group">
        <label for="direccion_cliente">Dirección</label>
        <input type="text" name="direccion_cliente" id="direccion_cliente">
    </div>
    <div class="form-group">
        <label for="departamento">Departamento</label>
        <select name="departamento" id="departamento">
            <?php foreach ($data['departamentos'] as $departamento) : ?>
                <option value="<?php echo $departamento; ?>"><?php echo $departamento; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="provincia">Provincia</label>
        <select name="provincia" id="provincia">
        </select>
    </div>
    <div class="form-group">
        <label for="distrito">Distrito</label>
        <select name="distrito" id="distrito">
        </select>
    </div>
    <input type="hidden" name="id_ubigeo" id="id_ubigeo">
    <div class="form-group">
        <label for="telefono_cliente">Teléfono</label>
        <input type="text" name="telefono_cliente" id="telefono_cliente">
    </div>
    <div class="form-group">
        <label for="correo_electronico">Correo Electrónico</label>
        <input type="email" name="correo_electronico" id="correo_electronico">
    </div>
    <div class="form-group">
        <label for="observaciones">Observaciones</label>
        <textarea name="observaciones" id="observaciones"></textarea>
    </div>
    <div class="form-group">
        <label for="website_cliente">Sitio Web</label>
        <input type="url" name="website_cliente" id="website_cliente">
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="<?php echo SITE_URL; ?>index.php?controller=clientes&action=index" class="btn btn-secondary">Cancelar</a>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ubigeos = <?php echo json_encode($data['ubigeos']); ?>;
    const departamentoSelect = document.getElementById('departamento');
    const provinciaSelect = document.getElementById('provincia');
    const distritoSelect = document.getElementById('distrito');
    const idUbigeoInput = document.getElementById('id_ubigeo');

    function populateProvincias() {
        const selectedDepartamento = departamentoSelect.value;
        provinciaSelect.innerHTML = '';
        distritoSelect.innerHTML = '';
        const provincias = [...new Set(ubigeos.filter(u => u.departamento === selectedDepartamento).map(u => u.provincia))];
        provincias.forEach(provincia => {
            const option = document.createElement('option');
            option.value = provincia;
            option.textContent = provincia;
            provinciaSelect.appendChild(option);
        });
        populateDistritos();
    }

    function populateDistritos() {
        const selectedProvincia = provinciaSelect.value;
        distritoSelect.innerHTML = '';
        const distritos = [...new Set(ubigeos.filter(u => u.provincia === selectedProvincia).map(u => u.distrito))];
        distritos.forEach(distrito => {
            const option = document.createElement('option');
            option.value = distrito;
            option.textContent = distrito;
            distritoSelect.appendChild(option);
        });
        updateUbigeoId();
    }

    function updateUbigeoId() {
        const selectedDepartamento = departamentoSelect.value;
        const selectedProvincia = provinciaSelect.value;
        const selectedDistrito = distritoSelect.value;
        const ubigeo = ubigeos.find(u => u.departamento === selectedDepartamento && u.provincia === selectedProvincia && u.distrito === selectedDistrito);
        if (ubigeo) {
            idUbigeoInput.value = ubigeo.id_ubigeo;
        }
    }

    departamentoSelect.addEventListener('change', populateProvincias);
    provinciaSelect.addEventListener('change', populateDistritos);
    distritoSelect.addEventListener('change', updateUbigeoId);

    populateProvincias();
});
</script>

<?php require_once 'views/layout/footer.php'; ?>
