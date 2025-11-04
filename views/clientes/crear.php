<?php require_once 'views/layout/header.php'; ?>

<h1>Crear Cliente</h1>

<!-- ✅ Incluye SweetAlert2 (solo una vez, en tu layout o aquí mismo) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (isset($data['error'])) : ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "<?php echo addslashes($data['error']); ?>",
                confirmButtonText: "Aceptar",
                confirmButtonColor: "#d33"
            });
        });
    </script>
<?php endif; ?>


<form action="<?php echo SITE_URL; ?>index.php?controller=clientes&action=crear" method="POST">


    <div class="form-group" 
        style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">

        <label for="id_tipo_documento">Tipo de Documento</label>
        <select name="id_tipo_documento" id="id_tipo_documento" 
        style="font-size: 16px; padding: 6px; width: 150px;">
            <?php foreach ($data['tipos_documento'] as $tipo) : ?>
                <option value="<?php echo $tipo['id_tipo_documento']; ?>"><?php echo $tipo['nombre_documento']; ?></option>
            <?php endforeach; ?>
        </select>

        
        <label for="numero_documento" 
            style="font-size: 16px; margin: 0; white-space: nowrap;">
            Número de Documento
        </label>

        <input type="text" name="numero_documento" id="numero_documento"
            style="font-size: 16px; padding: 6px; width: 200px; margin: 10;"
            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
            required>

        <button type="button" id="btnBuscarSunat"
                style="background: #007bff; color: #fff; border: none; padding: 6px 12px;
                    font-size: 14px; cursor: pointer; border-radius: 4px; margin: 0;">
            Buscar SUNAT
        </button>
    </div>


        <div class="form-group">
        <label for="nombre_cliente">Nombre del Cliente</label>
        <input type="text" name="nombre_cliente" id="nombre_cliente" 
        style="font-size: 16px; padding: 6px; width: 800px;"
        required>
    </div>
    <div class="form-group">
        <label for="direccion_cliente">Dirección</label>
        <input type="text" name="direccion_cliente" id="direccion_cliente"
        style="font-size: 16px; padding: 6px; width: 800px;"
        required>
    </div>


    <div class="form-group" 
        style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">

        
        <label for="departamento">Departamento</label>
        <select name="departamento" id="departamento" 
                style="font-size: 16px; padding: 6px; width: 250px;">
            <?php foreach ($data['departamentos'] as $departamento) : ?>
                <option value="<?php echo $departamento; ?>">
                    <?php echo $departamento; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="provincia">Provincia</label>
        <select name="provincia" id="provincia"
        style="font-size: 16px; padding: 6px; width: 250px;">
        </select>

        <label for="distrito">Distrito</label>
        <select name="distrito" id="distrito"
            style="font-size: 16px; padding: 6px; width: 250px;">
        </select>

    </div>

    
    <input type="hidden" name="id_ubigeo" id="id_ubigeo">

    <div class="form-group" 
        style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">

        <label for="telefono_cliente">Teléfono</label>
        <input type="text" name="telefono_cliente" id="telefono_cliente"
        style="font-size: 16px; padding: 6px; width: 250px;"
        required>

        <label for="correo_electronico">Correo Electrónico</label>
        <input type="email" name="correo_electronico" id="correo_electronico"
        style="font-size: 16px; padding: 6px; width: 250px;">

        <label for="website_cliente">Sitio Web</label>
        <input type="url" name="website_cliente" id="website_cliente"
        style="font-size: 16px; padding: 6px; width: 350px;">




    </div>




    <div class="form-group">
        <label for="observaciones">Observaciones</label>
        <textarea name="observaciones" id="observaciones"
        style="font-size: 16px; padding: 6px; width: 1000px;"
        ></textarea>
    </div>



    
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="<?php echo SITE_URL; ?>index.php?controller=clientes&action=index" class="btn btn-secondary">Cancelar</a>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {


// ➤ API para buscar  SUNAT
document.getElementById("btnBuscarSunat").addEventListener("click", function() {
    const numero = document.getElementById("numero_documento").value;
    const tipo = document.getElementById("id_tipo_documento").value;

    if (numero.trim() === "") {
        alert("Ingrese un número de documento");
        return;
    }

    if (tipo !== "1" && tipo !== "2") {
        alert("SUNAT solo consulta DNI o RUC");
        return;
    }

    fetch("<?php echo SITE_URL; ?>buscar_sunat.php?numero=" + numero + "&tipo=" + tipo)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // 🟢 Completar datos básicos
                document.getElementById("nombre_cliente").value = data.nombre;
                document.getElementById("direccion_cliente").value = data.direccion;

                // 🟢 Si viene información de ubigeo
                if (data.departamento && data.provincia && data.distrito) {
                    const dep = data.departamento.trim().toUpperCase();
                    const prov = data.provincia.trim().toUpperCase();
                    const dist = data.distrito.trim().toUpperCase();

                    // Buscar si existe el departamento en la lista
                    const depOption = [...departamentoSelect.options]
                        .find(o => o.value.trim().toUpperCase() === dep);
                    if (depOption) {
                        departamentoSelect.value = depOption.value;
                        populateProvincias();

                        // Buscar provincia
                        const provOption = [...provinciaSelect.options]
                            .find(o => o.value.trim().toUpperCase() === prov);
                        if (provOption) {
                            provinciaSelect.value = provOption.value;
                            populateDistritos();

                            // Buscar distrito
                            const distOption = [...distritoSelect.options]
                                .find(o => o.value.trim().toUpperCase() === dist);
                            if (distOption) {
                                distritoSelect.value = distOption.value;
                                updateUbigeoId();
                            }
                        }
                    }
                }

            } else {
                alert("No se encontró información en SUNAT");
            }
        })
        .catch(() => alert("Error consultando SUNAT"));
});



    // ➤ UBIGEO dinámico
    const tipoDocumento = document.getElementById("id_tipo_documento");
    const numeroDocumento = document.getElementById("numero_documento");
    const nombreCliente = document.getElementById("nombre_cliente");
    const direccion_cliente = document.getElementById("direccion_cliente");
    const departamentoSelect = document.getElementById("departamento");
    const provinciaSelect = document.getElementById("provincia");
    const distritoSelect = document.getElementById("distrito");
    const idUbigeoInput = document.getElementById("id_ubigeo");

    const ubigeos = <?php echo json_encode($data['ubigeos']); ?>;

     // --- FUNCIONES DE UBIGEO ---
    function populateProvincias() {
        const selectedDepartamento = departamentoSelect.value;
        provinciaSelect.innerHTML = '';
        distritoSelect.innerHTML = '';

        const provincias = [...new Set(
            ubigeos.filter(u => u.departamento === selectedDepartamento).map(u => u.provincia)
        )];

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

        const distritos = [...new Set(
            ubigeos.filter(u => u.provincia === selectedProvincia).map(u => u.distrito)
        )];

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

        const ubigeo = ubigeos.find(u =>
            u.departamento === selectedDepartamento &&
            u.provincia === selectedProvincia &&
            u.distrito === selectedDistrito
        );

        if (ubigeo) {
            idUbigeoInput.value = ubigeo.id_ubigeo;
        }
    }

    departamentoSelect.addEventListener('change', populateProvincias);
    provinciaSelect.addEventListener('change', populateDistritos);
    distritoSelect.addEventListener('change', updateUbigeoId);

    // Inicializar
    populateProvincias();


    // --- CAMBIO DE TIPO DE DOCUMENTO ---
    tipoDocumento.addEventListener("change", function () {
        const tipo = tipoDocumento.value;

        // 🔹 Limpiar los campos de texto
        numeroDocumento.value = "";
        nombreCliente.value = "";
        direccion_cliente.value = "";

        // 🔹 Reiniciar ubigeo al valor inicial (guiones)
        departamentoSelect.selectedIndex = 0;
        populateProvincias();
        provinciaSelect.selectedIndex = 0;
        populateDistritos();
        distritoSelect.selectedIndex = 0;
        updateUbigeoId();

        // 🔹 Configurar longitud según tipo de documento
        if (tipo === "1") { // DNI
            numeroDocumento.maxLength = 8;
        } else if (tipo === "2") { // RUC
            numeroDocumento.maxLength = 11;
        } else {
            numeroDocumento.maxLength = 20;
        }
    });
});

</script>

<?php require_once 'views/layout/footer.php'; ?>
