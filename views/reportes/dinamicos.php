<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
:root {
    --primary: #7d0000ff;
    --secondary: #a3a3a3ff;
    --success: #28a745;
    --warning: #ffc107;
    --info: #17a2b8;
    --light: #f8f9fa;
    --dark: #343a40;
    --radius: 6px;
    --shadow: 0 2px 6px rgba(0,0,0,0.1);
    --transition: 0.2s ease;
}

body {
    font-family: 'Segoe UI', Tahoma, sans-serif;
    background-color: #f4f6f9;
    color: var(--dark);
    margin: 0;
    padding: 0px;
}

.container-fluid {
    max-width: 1200px;
    margin: 0 auto;
}

h1.h3 {
    font-size: 1.8rem;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 25px;
}

/* Tarjetas */
.card {
    background: #fff;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    margin-bottom: 25px;
    border: 1px solid #e0e0e0;
}

.card-header {
    background: var(--primary);
    color: #fff;
    padding: 12px 20px;
    border-radius: var(--radius) var(--radius) 0 0;
}

.card-header h6 {
    margin: 0;
    font-weight: 600;
}

.card-body {
    padding: 20px;
}

/* Etiquetas y campos */
label {
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 6px;
    display: block;
}

select, input[type="text"] {
    width: 100%;
    padding: 8px 10px;
    border-radius: var(--radius);
    border: 1px solid #ccc;
    outline: none;
    transition: border-color var(--transition);
    background-color: white;
}

select:focus, input[type="text"]:focus {
    border-color: var(--primary);
}

/* Botones */
button {
    cursor: pointer;
    border: none;
    border-radius: var(--radius);
    padding: 8px 14px;
    font-weight: 500;
    transition: all var(--transition);
}

.btn-primary { background: var(--primary); color: #fff; }
.btn-success { background: var(--success); color: #fff; }
.btn-warning { background: var(--warning); color: #fff; }
.btn-info { background: var(--info); color: #fff; }
.btn-secondary { background: var(--secondary); color: #fff; }

button:hover {
    filter: brightness(1.1);
}

/* Grupos de entrada */
.input-group {
    display: flex;
    gap: 6px;
}

.input-group input {
    flex: 1;
}

/* Sistema de columnas */
.row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.col-md-4 {
    flex: 1;
    min-width: 250px;
}

/* Dual List Box */
.dual-list-box {
    display: flex;
    justify-content: space-between;
    align-items: stretch;
    gap: 20px;
    flex-wrap: wrap;
}

.dual-list-box .list-box {
    flex: 1;
    min-width: 250px;
    border: 1px solid #ddd;
    border-radius: var(--radius);
    background: white;
    padding: 10px;
    height: 300px;
    overflow-y: auto;
    box-shadow: var(--shadow);
}

.dual-list-box .list-box h5 {
    margin: 0 0 10px;
    font-weight: 600;
    font-size: 1rem;
    color: var(--dark);
    border-bottom: 1px solid #eee;
    padding-bottom: 5px;
}

.dual-list-box .list-box ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.dual-list-box .list-box ul li {
    padding: 8px 12px;
    border-radius: 4px;
    margin-bottom: 4px;
    cursor: pointer;
    transition: background var(--transition);
}

.dual-list-box .list-box ul li:hover {
    background: #f4f4f4;
}

.dual-list-box .list-box ul li.selected {
    background: var(--primary);
    color: white;
}

.dual-list-box .actions {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 12px;
}

/* Botones circulares */
.dual-list-box .actions button {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    font-size: 1.2rem;
    font-weight: bold;
    background: var(--secondary);
    color: #fff;
    border: none;
    box-shadow: var(--shadow);
    transition: all var(--transition);
}

.dual-list-box .actions button:hover {
    background: var(--primary);
    transform: scale(1.08);
}


/* Tabla */
.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    font-size: 0.95rem;
}

.table th, .table td {
    padding: 10px;
    border: 1px solid #ddd;
}

.table th {
    background: var(--light);
    text-align: left;
    font-weight: 600;
}

/* Responsivo */
@media (max-width: 768px) {
    .dual-list-box {
        flex-direction: column;
    }
}



/* Tabla responsive para muchas columnas */
#report_result_container {
    overflow-x: auto; /* Scroll horizontal si no caben todas las columnas */
    -webkit-overflow-scrolling: touch; /* Suaviza el scroll en móviles */
}

#report_table {
    table-layout: auto; /* Se ajusta según contenido */
    width: 100%;
    min-width: 800px; /* Evita que se colapse con muchas columnas */
}

#report_table th, #report_table td {
    white-space: nowrap; /* Evita que el texto se rompa en varias líneas */
    text-align: left;
    padding: 8px 12px;
    border: 1px solid #ddd;
    font-size: 0.85rem;
}

/* Opcional: hover para filas */
#report_table tbody tr:hover {
    background-color: #f1f1f1;
}


</style>

<div class="container-fluid">
    <h1 class="h2 mb-4 text-gray-800"><?php echo $data['titulo']; ?></h1>

    <!-- Report Type and Templates -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary" style="font-size: 16px;">Configuración del Reporte</h6>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="report_type">Tipo de Reporte</label>
                    <select id="report_type" class="form-control">
                        <option value="oportunidades" selected>Oportunidades</option>
                        <option value="actividades">Actividades</option>
                        <option value="clientes">Clientes</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="template_select">Cargar Plantilla</label>
                    <div class="input-group">
                        <select id="template_select" class="form-control">
                            <option value="">Seleccione una plantilla</option>
                        </select>
                        <button class="btn btn-primary" id="load_template_btn">Cargar</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="template_name">Guardar Plantilla</label>
                    <div class="input-group">
                        <input type="text" id="template_name" class="form-control" placeholder="Nombre de la plantilla">
                        <button class="btn btn-success" id="save_template_btn">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Column Selection -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary" style="font-size: 16px;">Selección de Columnas</h6>
    </div>
    <div class="card-body">
        <div class="dual-list-box">
            <div class="list-box">
                <h5>Columnas Disponibles</h5>
                <ul id="available_columns"></ul>
            </div>

            <div class="actions">
                <!-- Botones existentes + nuevos -->
                <button class="btn btn-secondary" id="add_all_btn">&raquo;</button>
                <button class="btn btn-secondary" id="add_column_btn">&gt;</button>
                <button class="btn btn-secondary" id="remove_column_btn">&lt;</button>
                <button class="btn btn-secondary" id="remove_all_btn">&laquo;</button>
            </div>

            <div class="list-box">
                <h5>Columnas Seleccionadas</h5>
                <ul id="selected_columns"></ul>
            </div>
        </div>
    </div>
</div>


    <!-- Report Generation and Results -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary" style="font-size: 16px;">Generar y Ver Reporte</h6>
        </div>
        <div class="card-body">
            <div class="mt-4">
                <button class="btn btn-info" id="generate_report_btn">Generar Reporte</button>
                <button class="btn btn-warning" id="export_excel_btn" style="display:none;">Exportar a Excel</button>
            </div>

            <div class="table-responsive mt-4" id="report_result_container" style="display:none;">
                <table class="table" id="report_table" width="100%" cellspacing="0">
                    <thead></thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>


</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const reportTypeSelect = document.getElementById('report_type');
    const availableColumnsUl = document.getElementById('available_columns');
    const selectedColumnsUl = document.getElementById('selected_columns');
    const templateSelect = document.getElementById('template_select');
    const addColumnBtn = document.getElementById('add_column_btn');
    const removeColumnBtn = document.getElementById('remove_column_btn');
    const generateReportBtn = document.getElementById('generate_report_btn');
    const exportExcelBtn = document.getElementById('export_excel_btn');
    const saveTemplateBtn = document.getElementById('save_template_btn');
    const loadTemplateBtn = document.getElementById('load_template_btn');
    const reportResultContainer = document.getElementById('report_result_container');

    function updateColumnsAndTemplates() {
        const reportType = reportTypeSelect.value;
        fetch(`index.php?controller=reportes&action=ajax&ajax_action=get_columns_and_templates&report_type=${reportType}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Populate available columns
                    availableColumnsUl.innerHTML = '';
                    data.data.columns.forEach(col => {
                        const li = document.createElement('li');
                        li.textContent = col;
                        li.dataset.value = col;
                        availableColumnsUl.appendChild(li);
                    });

                    // Populate templates
                    templateSelect.innerHTML = '<option value="">Seleccione una plantilla</option>';
                    data.data.templates.forEach(template => {
                        const option = document.createElement('option');
                        option.value = template.id_plantilla;
                        option.textContent = template.nombre_plantilla;
                        templateSelect.appendChild(option);
                    });
                }
            });
    }

    reportTypeSelect.addEventListener('change', function() {
        selectedColumnsUl.innerHTML = ''; // Clear selected columns
        updateColumnsAndTemplates();
    });

    // Handle column selection
    document.addEventListener('click', function(e) {
        if (e.target.tagName === 'LI' && e.target.parentElement.parentElement.classList.contains('list-box')) {
            e.target.classList.toggle('selected');
        }
    });

    addColumnBtn.addEventListener('click', function() {
        const selected = availableColumnsUl.querySelectorAll('.selected');
        selected.forEach(li => {
            selectedColumnsUl.appendChild(li);
            li.classList.remove('selected');
        });
    });

    removeColumnBtn.addEventListener('click', function() {
        const selected = selectedColumnsUl.querySelectorAll('.selected');
        selected.forEach(li => {
            availableColumnsUl.appendChild(li);
            li.classList.remove('selected');
        });
    });

    generateReportBtn.addEventListener('click', function() {
        const reportType = reportTypeSelect.value;
        const selectedColumns = Array.from(selectedColumnsUl.children).map(li => li.dataset.value);

        if (selectedColumns.length === 0) {

                Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Por favor, seleccione al menos una columna.',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar'
            });



            return;
        }

        const formData = new FormData();
        formData.append('report_type', reportType);
        selectedColumns.forEach(col => formData.append('selected_columns[]', col));

        fetch('index.php?controller=reportes&action=ajax&ajax_action=generate_report&report_type=' + reportType, {
            method: 'POST',
            body: new URLSearchParams(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const tableHead = document.querySelector('#report_table thead');
                const tableBody = document.querySelector('#report_table tbody');

                tableHead.innerHTML = '';
                tableBody.innerHTML = '';

                // Header
                const trHead = document.createElement('tr');
                selectedColumns.forEach(col => {
                    const th = document.createElement('th');
                    th.textContent = col;
                    trHead.appendChild(th);
                });
                tableHead.appendChild(trHead);

                // Body
                data.data.forEach(row => {
                    const trBody = document.createElement('tr');
                    selectedColumns.forEach(col => {
                        const td = document.createElement('td');
                        td.textContent = row[col];
                        trBody.appendChild(td);
                    });
                    tableBody.appendChild(trBody);
                });

                reportResultContainer.style.display = 'block';
                exportExcelBtn.style.display = 'inline-block';
            }
        });
    });

    saveTemplateBtn.addEventListener('click', function() {
        const templateName = document.getElementById('template_name').value.trim();
        const reportType = reportTypeSelect.value;
        const selectedColumns = Array.from(selectedColumnsUl.children).map(li => li.dataset.value);

        if (!templateName) {


                Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Por favor, ingrese un nombre para la plantilla.',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar'
            });


            return;
        }


        if (selectedColumns.length === 0) {


            Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'Por favor, seleccione al menos una columna para guardar en la plantilla.',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Aceptar'
        });


            
            return;
        }

        const formData = new FormData();
        formData.append('template_name', templateName);
        selectedColumns.forEach(col => formData.append('selected_columns[]', col));

        fetch('index.php?controller=reportes&action=ajax&ajax_action=save_template&report_type=' + reportType, {
            method: 'POST',
            body: new URLSearchParams(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {

                Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: 'Plantilla guardada exitosamente.',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar'
            });


                templateSelect.innerHTML = '<option value="">Seleccione una plantilla</option>';
                data.data.templates.forEach(template => {
                    const option = document.createElement('option');
                    option.value = template.id_plantilla;
                    option.textContent = template.nombre_plantilla;
                    templateSelect.appendChild(option);
                });
            } else {

                Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al guardar la plantilla.',
                confirmButtonColor: '#d33',
                confirmButtonText: 'Aceptar'
            });
                
            }
        });
    });

    loadTemplateBtn.addEventListener('click', function() {
        const templateId = templateSelect.value;
        if (!templateId) {

            Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'Por favor, seleccione una plantilla para cargar.',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Aceptar'
        });


            return;
        }

        fetch(`index.php?controller=reportes&action=ajax&ajax_action=load_template&template_id=${templateId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                selectedColumnsUl.innerHTML = '';
                const availableCols = Array.from(availableColumnsUl.children);
                data.data.columns.forEach(colName => {
                    const colLi = availableCols.find(li => li.dataset.value === colName);
                    if (colLi) {
                        selectedColumnsUl.appendChild(colLi);
                    }
                });
            }
        });
    });


    const addAllBtn = document.getElementById('add_all_btn');
    const removeAllBtn = document.getElementById('remove_all_btn');

    // 👉 Mover todas las columnas disponibles a seleccionadas
    addAllBtn.addEventListener('click', function() {
        const allAvailable = Array.from(availableColumnsUl.children);
        allAvailable.forEach(li => {
            selectedColumnsUl.appendChild(li);
            li.classList.remove('selected');
        });
    });

    // 👉 Mover todas las columnas seleccionadas a disponibles
    removeAllBtn.addEventListener('click', function() {
        const allSelected = Array.from(selectedColumnsUl.children);
        allSelected.forEach(li => {
            availableColumnsUl.appendChild(li);
            li.classList.remove('selected');
        });
    });

    exportExcelBtn.addEventListener('click', function() {
        const reportType = reportTypeSelect.value;
        const selectedColumns = Array.from(selectedColumnsUl.children).map(li => li.dataset.value);

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'index.php?controller=reportes&action=exportar_excel';

        const reportTypeInput = document.createElement('input');
        reportTypeInput.type = 'hidden';
        reportTypeInput.name = 'report_type';
        reportTypeInput.value = reportType;
        form.appendChild(reportTypeInput);

        const selectedColumnsInput = document.createElement('input');
        selectedColumnsInput.type = 'hidden';
        selectedColumnsInput.name = 'selected_columns';
        selectedColumnsInput.value = JSON.stringify(selectedColumns);
        form.appendChild(selectedColumnsInput);

        document.body.appendChild(form);
        form.submit();
    });

    // Initial load
    updateColumnsAndTemplates();
});
</script>
