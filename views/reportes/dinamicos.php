<style>
    .dual-list-box {
        display: flex;
        justify-content: space-between;
    }
    .dual-list-box .list-box {
        width: 45%;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 10px;
        height: 300px;
        overflow-y: auto;
    }
    .dual-list-box .list-box h5 {
        margin-top: 0;
        border-bottom: 1px solid #eee;
        padding-bottom: 5px;
    }
    .dual-list-box .list-box ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }
    .dual-list-box .list-box ul li {
        padding: 8px 12px;
        cursor: pointer;
        border-radius: 3px;
    }
    .dual-list-box .list-box ul li:hover, .dual-list-box .list-box ul li.selected {
        background-color: #f0f0f0;
    }
    .dual-list-box .actions {
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 0 15px;
    }
    .dual-list-box .actions button {
        margin-bottom: 10px;
    }
</style>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?php echo $data['titulo']; ?></h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Configuración del Reporte</h6>
        </div>
        <div class="card-body">
            <!-- Report Type and Templates -->
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
                        <div class="input-group-append">
                            <button class="btn btn-primary" id="load_template_btn">Cargar</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="template_name">Guardar Plantilla</label>
                    <div class="input-group">
                        <input type="text" id="template_name" class="form-control" placeholder="Nombre de la plantilla">
                        <div class="input-group-append">
                            <button class="btn btn-success" id="save_template_btn">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Column Selection -->
            <div class="dual-list-box">
                <div class="list-box">
                    <h5>Columnas Disponibles</h5>
                    <ul id="available_columns"></ul>
                </div>
                <div class="actions">
                    <button class="btn btn-secondary" id="add_column_btn">&gt;</button>
                    <button class="btn btn-secondary" id="remove_column_btn">&lt;</button>
                </div>
                <div class="list-box">
                    <h5>Columnas Seleccionadas</h5>
                    <ul id="selected_columns"></ul>
                </div>
            </div>

            <div class="mt-4">
                <button class="btn btn-info" id="generate_report_btn">Generar Reporte</button>
                <button class="btn btn-warning" id="export_excel_btn" style="display:none;">Exportar a Excel</button>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4" id="report_result_card" style="display:none;">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Resultados del Reporte</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="report_table" width="100%" cellspacing="0">
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

    function updateColumnsAndTemplates() {
        const reportType = reportTypeSelect.value;
        fetch(`index.php?controller=reportes&action=ajax&action=get_columns_and_templates&report_type=${reportType}`)
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
        if (e.target.tagName === 'LI' && e.target.parentElement.classList.contains('list-box')) {
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
            alert('Por favor, seleccione al menos una columna.');
            return;
        }

        const formData = new FormData();
        formData.append('report_type', reportType);
        selectedColumns.forEach(col => formData.append('selected_columns[]', col));

        fetch('index.php?controller=reportes&action=ajax&action=generate_report', {
            method: 'POST',
            body: formData
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

                document.getElementById('report_result_card').style.display = 'block';
                exportExcelBtn.style.display = 'inline-block';
            }
        });
    });

    saveTemplateBtn.addEventListener('click', function() {
        const templateName = document.getElementById('template_name').value.trim();
        const reportType = reportTypeSelect.value;
        const selectedColumns = Array.from(selectedColumnsUl.children).map(li => li.dataset.value);

        if (!templateName) {
            alert('Por favor, ingrese un nombre para la plantilla.');
            return;
        }
        if (selectedColumns.length === 0) {
            alert('Por favor, seleccione al menos una columna para guardar en la plantilla.');
            return;
        }

        const formData = new FormData();
        formData.append('template_name', templateName);
        formData.append('report_type', reportType);
        selectedColumns.forEach(col => formData.append('selected_columns[]', col));

        fetch('index.php?controller=reportes&action=ajax&action=save_template', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Plantilla guardada exitosamente.');
                templateSelect.innerHTML = '<option value="">Seleccione una plantilla</option>';
                data.data.templates.forEach(template => {
                    const option = document.createElement('option');
                    option.value = template.id_plantilla;
                    option.textContent = template.nombre_plantilla;
                    templateSelect.appendChild(option);
                });
            } else {
                alert('Error al guardar la plantilla.');
            }
        });
    });

    loadTemplateBtn.addEventListener('click', function() {
        const templateId = templateSelect.value;
        if (!templateId) {
            alert('Por favor, seleccione una plantilla para cargar.');
            return;
        }

        fetch(`index.php?controller=reportes&action=ajax&action=load_template&template_id=${templateId}`)
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